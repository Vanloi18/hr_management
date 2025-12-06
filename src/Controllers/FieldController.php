<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Field;
use App\Core\Validator; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Mpdf\Mpdf;

class FieldController extends Controller
{
    protected $fieldModel;

    public function __construct()
    {
        parent::__construct();
        $this->fieldModel = new Field();
    }

    public function index()
    {
        $this->requireAdmin();

        // 1. Lấy tham số
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit  = 10;
        $offset = ($page - 1) * $limit;

        // 2. Lấy dữ liệu
        $fields = $this->fieldModel->getPaginated($keyword, $limit, $offset);
        $totalRecords = $this->fieldModel->countAll($keyword);
        $totalPages = ceil($totalRecords / $limit);

        // 3. Đóng gói dữ liệu
        $data = [
            'title'        => 'Quản lý Lĩnh vực',
            'fields'       => $fields,
            'currentPage'  => $page,
            'totalPages'   => $totalPages,
            'totalRecords' => $totalRecords,
            'keyword'      => $keyword
        ];

        // 4. Xử lý AJAX
        if (isAjaxRequest()) {
            return partial('fields/index', $data);
        }

        return view('fields/index', $data);
    }

    public function create()
    {
        $this->requireAdmin();
        
        $data = [
            'title' => 'Thêm Lĩnh vực mới'
        ];

        // 🔥 LOGIC PJAX MỚI
        if (isAjaxRequest()) {
            return partial('fields/create', $data);
        }
        
        return view('fields/create', $data);
    }

    /**
     * Xử lý lưu mới (POST /fields) - Đã tái cấu trúc
     */
    public function store()
    {
        $this->requireAdmin();
        
        $rules = [
            'field_name' => 'required|unique:fields',
            'description' => 'optional'
        ];

        $validator = new Validator();
        if (!$validator->validate($_POST, $rules)) {
            return; // Validator tự redirect
        }

        $data = $validator->validatedData();
        $this->fieldModel->create($data); // Gọi Model

        flash('success', 'Thêm lĩnh vực thành công!');
        redirect('/fields');
    }

    public function edit()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('/fields');

        $field = $this->fieldModel->find($id);
        if (!$field) {
            flash('error', 'Không tìm thấy lĩnh vực.');
            redirect('/fields');
        }

        $data = [
            'title' => 'Chỉnh sửa Lĩnh vực',
            'field' => $field
        ];

        if (isAjaxRequest()) {
            return partial('fields/edit', $data);
        }
        return view('fields/edit', $data);
    }

    /**
     * Xử lý cập nhật (POST /fields/update) - Đã tái cấu trúc
     */
    public function update()
    {
        $this->requireAdmin();
        $id = $_POST['id'] ?? null;
        if (!$id) redirect('/fields');
        
        $rules = [
            'field_name' => 'required|unique:fields,' . $id, // Duy nhất, trừ ID này
            'description' => 'optional'
        ];

        $validator = new Validator();
        if (!$validator->validate($_POST, $rules)) {
            return; // Validator tự redirect
        }
        
        $data = $validator->validatedData();
        $this->fieldModel->update($id, $data); // Gọi Model

        flash('success', 'Cập nhật lĩnh vực thành công!');
        redirect('/fields');
    }

    /**
     * Xử lý xóa (POST /fields/delete) - PHIÊN BẢN AJAX (Giữ nguyên)
     */
    public function destroy()
    {
        $this->requireAdmin();
        
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new \Exception('Thiếu ID của Lĩnh vực.');
            }

            // Gọi Model để xóa
            $this->fieldModel->delete($id);

            // Trả về JSON thành công
            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa lĩnh vực thành công.'
            ]);
            exit();

        } catch (\PDOException $e) {
            // 🔥 LOGIC ĐẶC THÙ: Xử lý lỗi Khóa ngoại
            if ($e->getCode() === '23000' || $e->getCode() === 1451) {
                http_response_code(409); // 409 Conflict
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể xóa lĩnh vực này vì vẫn còn tin tuyển dụng (Positions) liên quan.'
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Lỗi CSDL: ' . $e->getMessage()]);
            }
            exit();
        } catch (\Exception $e) {
            http_response_code(400); // 400 Bad Request
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit();
        }
    }
    /**
     * Xuất Excel
     */
    public function exportExcel()
    {
        $this->requireAdmin();
        $keyword = $_GET['keyword'] ?? '';
        $fields = $this->fieldModel->getAllForExport($keyword);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sách Lĩnh vực');

        // Header
        $headers = ['ID', 'Tên lĩnh vực', 'Mô tả', 'Số tin tuyển dụng'];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Data
        $rows = [];
        foreach ($fields as $field) {
            $rows[] = [
                $field['id'],
                $field['field_name'],
                $field['description'],
                $field['position_count'] // Số lượng tin
            ];
        }

        if (!empty($rows)) {
            $sheet->fromArray($rows, NULL, 'A2');
        }

        // Style
        $lastRow = count($rows) + 1;
        $sheet->getStyle("A1:D{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0d6efd']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        foreach (range('A', 'D') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

        // Output
        $fileName = 'DS_LinhVuc_' . date('dmY_Hi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Xuất PDF
     */
    public function exportPDF()
    {
        $this->requireAdmin();
        $keyword = $_GET['keyword'] ?? '';
        $fields = $this->fieldModel->getAllForExport($keyword);

        $html = '
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
                h2 { text-align: center; color: #0d6efd; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #0d6efd; color: white; padding: 8px; border: 1px solid #333; }
                td { padding: 8px; border: 1px solid #444; text-align: center; }
                .text-left { text-align: left; }
            </style>
        </head>
        <body>
            <h2>DANH MỤC LĨNH VỰC HOẠT ĐỘNG</h2>
            <p style="text-align:center">Ngày xuất: ' . date('d/m/Y H:i') . '</p>
            <table>
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="30%">Tên lĩnh vực</th>
                        <th width="40%">Mô tả</th>
                        <th width="20%">Số tin tuyển dụng</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($fields as $field) {
            $html .= '<tr>
                <td>' . $field['id'] . '</td>
                <td class="text-left">' . $field['field_name'] . '</td>
                <td class="text-left">' . $field['description'] . '</td>
                <td>' . $field['position_count'] . '</td>
            </tr>';
        }

        $html .= '</tbody></table></body></html>';

        try {
            $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
            $mpdf->WriteHTML($html);
            $mpdf->Output('DS_LinhVuc_' . date('dmY') . '.pdf', 'D');
        } catch (\Exception $e) {
            echo "Lỗi xuất PDF: " . $e->getMessage();
        }
        exit;
    }
}

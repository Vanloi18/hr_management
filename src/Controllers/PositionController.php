<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Position;
use App\Models\Recruiter;
use App\Models\Field;
use App\Core\Validator;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Mpdf\Mpdf;

class PositionController extends Controller
{
    protected $positionModel;

    public function __construct()
    {
        parent::__construct();
        $this->positionModel = new Position();
    }

    public function index()
    {
        $this->checkAuthentication();

        $keyword      = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $status       = isset($_GET['status']) ? trim($_GET['status']) : '';
        $recruiter_id = isset($_GET['recruiter_id']) ? trim($_GET['recruiter_id']) : '';
        $page         = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $positions = $this->positionModel->getPaginated($keyword, $status, $recruiter_id, $limit, $offset);
        $totalRecords = $this->positionModel->countAll($keyword, $status, $recruiter_id);
        $totalPages = ceil($totalRecords / $limit);

        // Lấy danh sách cho filter
        $recruitersList = (new Recruiter())->allForDropdown(); 

        $data = [
            'title'        => 'Quản lý Tin tuyển dụng',
            'positions'    => $positions,
            'recruitersList' => $recruitersList,
            'currentPage'  => $page,
            'totalPages'   => $totalPages,
            'totalRecords' => $totalRecords,
            'keyword'      => $keyword,
            'status'       => $status,
            'recruiter_id' => $recruiter_id
        ];

        if (isAjaxRequest()) {
            return partial('positions/index', $data);
        }
        return view('positions/index', $data);
    }

    public function exportExcel()
    {
        $this->checkAuthentication();
        $keyword      = $_GET['keyword'] ?? '';
        $status       = $_GET['status'] ?? '';
        $recruiter_id = $_GET['recruiter_id'] ?? '';

        $positions = $this->positionModel->getAllForExport($keyword, $status, $recruiter_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Tin tuyển dụng');

        $headers = ['ID', 'Tiêu đề', 'Công ty', 'Lĩnh vực', 'Mức lương', 'Trạng thái', 'Ngày tạo'];
        $sheet->fromArray([$headers], NULL, 'A1');

        $rows = [];
        foreach ($positions as $pos) {
            $statusText = ($pos['status'] == 'open') ? 'Đang tuyển' : 'Đã đóng';
            $rows[] = [
                $pos['id'],
                $pos['title'],
                $pos['company_name'],
                $pos['field_name'],
                $pos['salary_range'],
                $statusText,
                date('d/m/Y', strtotime($pos['created_at']))
            ];
        }

        if (!empty($rows)) $sheet->fromArray($rows, NULL, 'A2');

        $lastRow = count($rows) + 1;
        $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0d6efd']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        foreach (range('A', 'G') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

        $fileName = 'DS_TinTuyenDung_' . date('dmY') . '.xlsx';
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
        $this->checkAuthentication();
        $keyword      = $_GET['keyword'] ?? '';
        $status       = $_GET['status'] ?? '';
        $recruiter_id = $_GET['recruiter_id'] ?? '';

        $positions = $this->positionModel->getAllForExport($keyword, $status, $recruiter_id);

        $html = '
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
                h2 { text-align: center; color: #0d6efd; margin-bottom: 15px; }
                table { width: 100%; border-collapse: collapse; }
                th { background-color: #0d6efd; color: white; padding: 6px; border: 1px solid #333; }
                td { padding: 6px; border: 1px solid #444; text-align: center; }
                .text-left { text-align: left; }
            </style>
        </head>
        <body>
            <h2>DANH SÁCH VỊ TRÍ TUYỂN DỤNG</h2>
            <p style="text-align:center">Ngày xuất: ' . date('d/m/Y H:i') . '</p>
            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="25%">Tiêu đề</th>
                        <th width="20%">Công ty</th>
                        <th width="15%">Lĩnh vực</th>
                        <th width="15%">Mức lương</th>
                        <th width="10%">Trạng thái</th>
                        <th width="10%">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($positions as $pos) {
            $statusText = ($pos['status'] == 'open') ? 'Đang tuyển' : 'Đã đóng';
            $html .= '<tr>
                <td>' . $pos['id'] . '</td>
                <td class="text-left">' . $pos['title'] . '</td>
                <td class="text-left">' . $pos['company_name'] . '</td>
                <td>' . $pos['field_name'] . '</td>
                <td>' . $pos['salary_range'] . '</td>
                <td>' . $statusText . '</td>
                <td>' . date('d/m/Y', strtotime($pos['created_at'])) . '</td>
            </tr>';
        }

        $html .= '</tbody></table></body></html>';

        try {
            $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']); // Khổ ngang
            $mpdf->WriteHTML($html);
            $mpdf->Output('DS_TinTuyenDung_' . date('dmY') . '.pdf', 'D');
        } catch (\Exception $e) {
            echo "Lỗi xuất PDF: " . $e->getMessage();
        }
        exit;
    }

    public function create()
    {
        $this->checkAuthentication();
        $recruiters = (new Recruiter())->allForDropdown();
        $fields = (new Field())->allForDropdown();

        $data = [
            'title' => 'Đăng tin Tuyển dụng mới',
            'recruiters' => $recruiters,
            'fields' => $fields
        ];
        
        if (isAjaxRequest()) {
            return partial('positions/create', $data);
        }
        return view('positions/create', $data);
    }

    public function store()
    {
        $this->checkAuthentication();
        $rules = [
            'title' => 'required',
            'recruiter_id' => 'required',
            'field_id' => 'required',
            'description' => 'required',
            'requirements' => 'required',
        ];

        $validator = new Validator();
        if (!$validator->validate($_POST, $rules)) {
            return;
        }

        $data = $validator->validatedData();
        $data['status'] = e($_POST['status'] ?? 'open');
        $data['created_by_user_id'] = $_SESSION['user']['id'];
        $this->positionModel->create($data);
        flash('success', 'Đăng tin tuyển dụng thành công!');
        // [SỬA LỖI REDIRECT]
        redirect('/positions');
    }

    public function edit()
    {
        $this->checkAuthentication();
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('/positions');

        $position = $this->positionModel->find($id);
        if (!$position) {
            flash('error', 'Không tìm thấy vị trí.');
            redirect('/positions');
        }
        
        $recruiters = (new Recruiter())->allForDropdown();
        $fields = (new Field())->allForDropdown();

        $data = [
            'title' => 'Chỉnh sửa Vị trí Tuyển dụng',
            'position' => $position,
            'recruiters' => $recruiters,
            'fields' => $fields
        ];

        if (isAjaxRequest()) {
            return partial('positions/edit', $data);
        }
        return view('positions/edit', $data);
    }

    public function update()
    {
        $this->checkAuthentication();
        $id = $_POST['id'] ?? null;
        if (!$id) redirect('/positions'); // [SỬA LỖI REDIRECT]
        
        $rules = [
            'title' => 'required',
            'recruiter_id' => 'required',
            'field_id' => 'required',
            'description' => 'required',
            'requirements' => 'required',
        ];

        $validator = new Validator();
        if (!$validator->validate($_POST, $rules)) {
            return;
        }

        $data = $validator->validatedData();
        $data['status'] = e($_POST['status'] ?? 'open');
        $this->positionModel->update($id, $data);
        flash('success', 'Cập nhật tin tuyển dụng thành công!');
        // [SỬA LỖI REDIRECT]
        redirect('/positions');
    }

    public function destroy()
    {
        $this->checkAuthentication();
        header('Content-Type: application/json');
        try {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new \Exception('Thiếu ID của Vị trí.');
            }
            $this->positionModel->delete($id);
            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa tin tuyển dụng (và các CV liên quan).'
            ]);
            exit();
        } catch (\Exception $e) {
            // [SỬA LỖI CÚ PHÁP]
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }
    }
}

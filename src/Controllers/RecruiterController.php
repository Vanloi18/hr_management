<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Recruiter;
use App\Core\Validator; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RecruiterController extends Controller
{
    protected $recruiterModel;

    public function __construct()
    {
        parent::__construct();
        $this->recruiterModel = new Recruiter();
    }
    
    public function index()
    {
        $this->requireAdmin();

        // 1. Lấy tham số tìm kiếm và phân trang
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit   = 10; 
        $offset  = ($page - 1) * $limit;

        // 2. Lấy dữ liệu từ Model
        $recruiters = $this->recruiterModel->getPaginated($keyword, $limit, $offset);
        $totalRecords = $this->recruiterModel->countAll($keyword);
        $totalPages = ceil($totalRecords / $limit);

        // 3. Chuẩn bị dữ liệu
        $data = [
            'title'        => 'Quản lý Nhà tuyển dụng',
            'recruiters'   => $recruiters,
            'currentPage'  => $page,
            'totalPages'   => $totalPages,
            'totalRecords' => $totalRecords,
            'keyword'      => $keyword
        ];


        if (isAjaxRequest()) {
            return partial('recruiters/index', $data);
        }
        // --------------------------------------

        // Nếu load trực tiếp từ thanh địa chỉ trình duyệt, trả về full view (có layout)
        return view('recruiters/index', $data);
    }

    public function create()
    {
        $this->requireAdmin();
        
        $data = [
            'title' => 'Thêm Nhà tuyển dụng mới'
        ];
        
        if (isAjaxRequest()) {
            return partial('recruiters/create', $data);
        }
        return view('recruiters/create', $data);
    }

    /**
     * Xử lý lưu mới (POST /recruiters) - Đã tái cấu trúc
     */
    public function store()
    {
        $this->requireAdmin();
        
        $rules = [
            'company_name' => 'required|unique:recruiters',
            'email' => 'optional|email|unique:recruiters', // Email không bắt buộc, nhưng nếu có phải là duy nhất
            'contact_person' => 'optional',
            'phone' => 'optional',
            'address' => 'optional'
        ];

        $validator = new Validator();
        if (!$validator->validate($_POST, $rules)) {
            return; // Validator tự redirect
        }

        $data = $validator->validatedData();
        $this->recruiterModel->create($data); // Gọi Model

        flash('success', 'Thêm nhà tuyển dụng thành công!');
        redirect('/recruiters');
    }

    public function edit()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('/recruiters');

        $recruiter = $this->recruiterModel->find($id);
        if (!$recruiter) {
            flash('error', 'Không tìm thấy nhà tuyển dụng.');
            redirect('/recruiters');
        }

        $data = [
            'title' => 'Chỉnh sửa Nhà tuyển dụng',
            'recruiter' => $recruiter
        ];

        if (isAjaxRequest()) {
            return partial('recruiters/edit', $data);
        }
        return view('recruiters/edit', $data);
    }

    /**
     * Xử lý cập nhật (POST /recruiters/update) - Đã tái cấu trúc
     */
    public function update()
    {
        $this->requireAdmin();
        $id = $_POST['id'] ?? null;
        if (!$id) redirect('/recruiters');

        $rules = [
            'company_name' => 'required|unique:recruiters,' . $id, // Duy nhất, trừ ID này
            'email' => 'optional|email|unique:recruiters,' . $id, // Duy nhất, trừ ID này
            'contact_person' => 'optional',
            'phone' => 'optional',
            'address' => 'optional'
        ];

        $validator = new Validator();
        if (!$validator->validate($_POST, $rules)) {
            return; // Validator tự redirect
        }
        
        $data = $validator->validatedData();
        $this->recruiterModel->update($id, $data); // Gọi Model

        flash('success', 'Cập nhật nhà tuyển dụng thành công!');
        redirect('/recruiters');
    }

    /**
     * Xử lý xóa (POST /recruiters/delete) - PHIÊN BẢN AJAX (Giữ nguyên)
     */
    public function destroy()
    {
        $this->requireAdmin();
        
        // Báo cho trình duyệt biết đây là JSON
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new \Exception('Thiếu ID của nhà tuyển dụng.');
            }

            // Gọi Model để xóa
            $this->recruiterModel->delete($id);

            // Trả về JSON thành công
            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa nhà tuyển dụng thành công.'
            ]);
            exit();

        } catch (\PDOException $e) {
            // 🔥 XỬ LÝ LỖI KHÓA NGOẠI
            // Bắt lỗi 1451 (Cannot delete or update a parent row)
            if ($e->getCode() === '23000' || $e->getCode() === 1451) {
                http_response_code(409); // 409 Conflict
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể xóa nhà tuyển dụng này vì vẫn còn tin tuyển dụng (Positions) liên quan. Bạn phải xóa các tin tuyển dụng trước.'
                ]);
            } else {
                // Lỗi CSDL chung
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Lỗi CSDL: ' . $e->getMessage()
                ]);
            }
            exit();
        } catch (\Exception $e) {
            // Lỗi chung khác (ví dụ: Thiếu ID)
            http_response_code(400); // 400 Bad Request
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }
    }
    public function exportExcel()
    {
        $this->checkAuthentication();
        $keyword = $_GET['keyword'] ?? '';

        $data = $this->recruiterModel->getAllForExport($keyword);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('DS Nhà tuyển dụng');

        // Header
        $headers = ['ID', 'Tên công ty', 'Email', 'SĐT', 'Người liên hệ', 'Địa chỉ', 'Ngày tạo'];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Data
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                $item['id'],
                $item['company_name'],
                $item['email'],
                $item['phone'],
                $item['contact_person'],
                $item['address'],
                date('d/m/Y', strtotime($item['created_at']))
            ];
        }

        if (!empty($rows)) {
            $sheet->fromArray($rows, NULL, 'A2');
        }

        // Style
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

        // Output
        $fileName = 'DS_NhaTuyenDung_' . date('dmY') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}

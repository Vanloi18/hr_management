<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Core\Validator;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Mpdf\Mpdf;

class EmployeeController extends Controller
{
    // Định nghĩa đường dẫn tương đối để lưu vào DB
    private const PHOTO_PATH_PREFIX = 'uploads/employees/photos/';
    private const CONTRACT_PATH_PREFIX = 'uploads/employees/contracts/';
    
    // Định nghĩa đường dẫn tuyệt đối để upload file
    private const PHOTO_DIR = BASE_PATH . 'public/uploads/employees/photos/';
    private const CONTRACT_DIR = BASE_PATH . 'public/uploads/employees/contracts/';

    protected $employeeModel;

    public function __construct()
    {
        parent::__construct();
        $this->employeeModel = new Employee();
    }

    public function index()
    {
        $this->checkAuthentication(); // Chỉ Admin/HR mới xem được

        // 1. Lấy tham số filter
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $status  = isset($_GET['status']) ? trim($_GET['status']) : '';
        $dept_id = isset($_GET['department_id']) ? trim($_GET['department_id']) : '';
        
        // 2. Phân trang
        $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        // 3. Gọi Model lấy dữ liệu
        $employees = $this->employeeModel->getPaginated($keyword, $status, $dept_id, $limit, $offset);
        $totalRecords = $this->employeeModel->countAll($keyword, $status, $dept_id);
        
        // Lấy danh sách phòng ban để hiển thị Select box lọc
        $departments = (new Department())->all(); 

        $totalPages = ceil($totalRecords / $limit);

        $data = [
            'title'        => 'Quản lý Nhân viên',
            'employees'    => $employees,
            'departments'  => $departments,
            'currentPage'  => $page,
            'totalPages'   => $totalPages,
            'totalRecords' => $totalRecords,
            'keyword'      => $keyword,
            'status'       => $status,
            'department_id'=> $dept_id
        ];

        if (isAjaxRequest()) {
            return partial('employees/index', $data);
        }

        return view('employees/index', $data);
    }

    public function create()
    {
        $this->checkAuthentication();
        $departments = (new Department())->all();
        
        $data = [
            'title' => 'Thêm Hồ sơ Nhân viên',
            'departments' => $departments
        ];

        if (isAjaxRequest()) {
            return partial('employees/create', $data);
        }
        return view('employees/create', $data);
    }

    public function store()
    {
        $this->checkAuthentication();

        // 1. Validate dữ liệu
        $validator = new Validator();
        $rules = [
            'full_name'  => 'required',
            'email'      => 'required|email|unique:employees',
            'job_title'  => 'required',
            'start_date' => 'required',
            'department_id' => 'required'
        ];
        
        if (!$validator->validate($_POST, $rules)) {
            // Validator tự động redirect kèm lỗi nếu thất bại
            return; 
        }

        // 2. Chuẩn bị dữ liệu
        $data = $validator->validatedData();
        $data['status'] = e($_POST['status'] ?? 'probation');
        $data['phone'] = e($_POST['phone'] ?? null);
        $data['photo_path'] = null;
        $data['contract_path'] = null;

        // 3. Xử lý Upload File
        try {
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $data['photo_path'] = $this->handleFileUpload(
                    $_FILES['photo'], 
                    self::PHOTO_DIR, 
                    ['jpg', 'jpeg', 'png', 'gif', 'webp'], 
                    self::PHOTO_PATH_PREFIX
                );
            }
            if (isset($_FILES['contract']) && $_FILES['contract']['error'] === UPLOAD_ERR_OK) {
                $data['contract_path'] = $this->handleFileUpload(
                    $_FILES['contract'], 
                    self::CONTRACT_DIR, 
                    ['pdf', 'doc', 'docx'], 
                    self::CONTRACT_PATH_PREFIX
                );
            }
        } catch (\Exception $e) {
            // Nếu lỗi upload, xóa file rác (nếu có) và báo lỗi
            $this->deleteFile($data['photo_path']);
            $this->deleteFile($data['contract_path']);
            
            flash('error', $e->getMessage());
            redirect('/employees/create');
            return;
        }

        // 4. Lưu vào DB
        $this->employeeModel->create($data);

        flash('success', 'Thêm nhân viên mới thành công!');
        redirect('/employees');
    }

    public function edit()
    {
        $this->checkAuthentication();
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('/employees');

        $employee = $this->employeeModel->find($id);
        if (!$employee) {
            flash('error', 'Không tìm thấy nhân viên.');
            redirect('/employees');
            return;
        }
        
        $departments = (new Department())->all();

        $data = [
            'title' => 'Chỉnh sửa Hồ sơ Nhân viên',
            'employee' => $employee,
            'departments' => $departments
        ];

        if (isAjaxRequest()) {
            return partial('employees/edit', $data);
        }
        return view('employees/edit', $data);
    }

    public function update()
    {
        $this->checkAuthentication();
        $id = $_POST['id'] ?? null;
        if (!$id) redirect('/employees');

        // 1. Validate
        $validator = new Validator();
        $rules = [
            'full_name'  => 'required',
            'email'      => 'required|email|unique:employees,' . $id, // Bỏ qua ID hiện tại khi check trùng
            'job_title'  => 'required',
            'start_date' => 'required',
            'department_id' => 'required'
        ];
        
        if (!$validator->validate($_POST, $rules)) {
            return;
        }

        // 2. Lấy dữ liệu cũ để đối chiếu file
        $oldEmployee = $this->employeeModel->find($id);
        
        $data = $validator->validatedData();
        $data['status'] = e($_POST['status'] ?? 'probation');
        $data['phone'] = e($_POST['phone'] ?? null);
        $data['photo_path'] = $oldEmployee['photo_path']; // Mặc định giữ nguyên
        $data['contract_path'] = $oldEmployee['contract_path']; // Mặc định giữ nguyên

        // 3. Xử lý Upload file mới (nếu có)
        try {
            // Ảnh
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $newPhoto = $this->handleFileUpload(
                    $_FILES['photo'], 
                    self::PHOTO_DIR, 
                    ['jpg', 'jpeg', 'png', 'gif', 'webp'], 
                    self::PHOTO_PATH_PREFIX
                );
                $this->deleteFile($oldEmployee['photo_path']); // Xóa ảnh cũ
                $data['photo_path'] = $newPhoto;
            }

            // Hợp đồng
            if (isset($_FILES['contract']) && $_FILES['contract']['error'] === UPLOAD_ERR_OK) {
                $newContract = $this->handleFileUpload(
                    $_FILES['contract'], 
                    self::CONTRACT_DIR, 
                    ['pdf', 'doc', 'docx'], 
                    self::CONTRACT_PATH_PREFIX
                );
                $this->deleteFile($oldEmployee['contract_path']); // Xóa hợp đồng cũ
                $data['contract_path'] = $newContract;
            }
        } catch (\Exception $e) {
            flash('error', $e->getMessage());
            redirect('/employees/edit?id=' . $id);
            return;
        }

        // 4. Update DB
        $this->employeeModel->update($id, $data);

        flash('success', 'Cập nhật hồ sơ thành công!');
        redirect('/employees');
    }

    /**
     * Xóa nhân viên (Sửa lại để Redirect thay vì JSON)
     */
    public function destroy()
    {
        $this->checkAuthentication();
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            flash('error', 'Thiếu ID nhân viên.');
            redirect('/employees');
            return;
        }

        try {
            // Lấy thông tin để xóa file vật lý
            $employee = $this->employeeModel->find($id);

            // Xóa DB
            $this->employeeModel->delete($id);

            // Xóa File
            if ($employee) {
                $this->deleteFile($employee['photo_path']);
                $this->deleteFile($employee['contract_path']);
            }

            flash('success', 'Đã xóa hồ sơ nhân viên.');
        } catch (\Exception $e) {
            flash('error', 'Lỗi: ' . $e->getMessage());
        }
        
        redirect('/employees');
    }

    /* --- PRIVATE HELPER --- */

    private function handleFileUpload($fileInfo, $uploadDir, $allowedExt, $dbPrefix)
    {
        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExt)) {
            throw new \Exception('File không hợp lệ. Chỉ chấp nhận: ' . implode(', ', $allowedExt));
        }

        // Tên file: timestamp_uniqid.ext để tránh trùng tuyệt đối
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($fileInfo['tmp_name'], $destination)) {
            return $dbPrefix . $fileName; // Trả về đường dẫn tương đối lưu DB
        }

        throw new \Exception('Không thể lưu file vào server.');
    }

    private function deleteFile($relativePath)
    {
        if (empty($relativePath)) return;
        $fullPath = BASE_PATH . 'public/' . $relativePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    /**
     * Xuất Excel
     */
    public function exportExcel()
    {
        $this->checkAuthentication();

        // 1. Lấy tham số filter từ URL
        $keyword = $_GET['keyword'] ?? '';
        $status  = $_GET['status'] ?? '';
        $dept_id = $_GET['department_id'] ?? '';

        // 2. Lấy dữ liệu từ Model (Hàm mới tạo ở Bước 1)
        $employees = $this->employeeModel->getAllForExport($keyword, $status, $dept_id);

        // 3. Khởi tạo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sách nhân viên');

        // Header
        $headers = ['ID', 'Họ tên', 'Email', 'SĐT', 'Chức vụ', 'Phòng ban', 'Ngày vào làm', 'Trạng thái'];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Data
        $rows = [];
        foreach ($employees as $emp) {
            $statusText = match($emp['status']) {
                'active' => 'Chính thức',
                'probation' => 'Thử việc',
                'terminated' => 'Đã nghỉ',
                default => $emp['status']
            };

            $rows[] = [
                $emp['id'],
                $emp['full_name'],
                $emp['email'],
                $emp['phone'],
                $emp['job_title'],
                $emp['department_name'],
                date('d/m/Y', strtotime($emp['start_date'])),
                $statusText
            ];
        }

        if (!empty($rows)) {
            $sheet->fromArray($rows, NULL, 'A2');
        }

        // Style (Kẻ bảng + Header đẹp)
        $lastRow = count($rows) + 1;
        $sheet->getStyle("A1:H{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0d6efd']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        foreach (range('A', 'H') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

        // Output file
        $fileName = 'DS_NhanVien_' . date('dmY_Hi') . '.xlsx';
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

        $keyword = $_GET['keyword'] ?? '';
        $status  = $_GET['status'] ?? '';
        $dept_id = $_GET['department_id'] ?? '';

        $employees = $this->employeeModel->getAllForExport($keyword, $status, $dept_id);

        // Tạo nội dung HTML
        $html = '
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
                h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #0d6efd; color: white; padding: 8px; border: 1px solid #333; font-weight: bold; }
                td { padding: 8px; border: 1px solid #444; text-align: center; }
                .text-left { text-align: left; }
            </style>
        </head>
        <body>
            <h2>DANH SÁCH NHÂN SỰ</h2>
            <p style="text-align:center">Ngày xuất: ' . date('d/m/Y H:i') . '</p>
            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="20%">Họ tên</th>
                        <th width="20%">Email</th>
                        <th width="15%">Chức vụ</th>
                        <th width="15%">Phòng ban</th>
                        <th width="10%">Ngày vào</th>
                        <th width="15%">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($employees as $emp) {
            $statusText = match($emp['status']) {
                'active' => 'Chính thức',
                'probation' => 'Thử việc',
                'terminated' => 'Đã nghỉ',
                default => $emp['status']
            };
            
            $html .= '<tr>
                <td>' . $emp['id'] . '</td>
                <td class="text-left">' . $emp['full_name'] . '</td>
                <td class="text-left">' . $emp['email'] . '</td>
                <td>' . $emp['job_title'] . '</td>
                <td>' . ($emp['department_name'] ?? '-') . '</td>
                <td>' . date('d/m/Y', strtotime($emp['start_date'])) . '</td>
                <td>' . $statusText . '</td>
            </tr>';
        }

        $html .= '</tbody></table></body></html>';

        try {
            $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']); 
            $mpdf->WriteHTML($html);
            $mpdf->Output('DS_NhanVien_' . date('dmY') . '.pdf', 'D');
        } catch (\Exception $e) {
            echo "Lỗi xuất PDF: " . $e->getMessage();
        }
        exit;
    }
}
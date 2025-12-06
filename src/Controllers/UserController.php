<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\Validator;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Mpdf\Mpdf;

class UserController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User(); 
    }

    public function index()
    {
        $this->requireAdmin();

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $role    = isset($_GET['role']) ? trim($_GET['role']) : '';
        $page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit   = 10; 
        $offset  = ($page - 1) * $limit;

        $users = $this->userModel->getPaginated($keyword, $role, $limit, $offset);
        $totalRecords = $this->userModel->countAll($keyword, $role);

        $totalPages = ceil($totalRecords / $limit);

        $data = [
            'title'        => 'Quản lý Users',
            'users'        => $users,
            'currentPage'  => $page,
            'totalPages'   => $totalPages,
            'totalRecords' => $totalRecords,
            'keyword'      => $keyword,
            'role'         => $role
        ];

        if (isAjaxRequest()) {
            return partial('users/index', $data);
        }
        return view('users/index', $data);
    }

    public function create()
    {
        $this->requireAdmin();
        return view('users/create', ['title' => 'Thêm User']);
    }

    /**
     * XỬ LÝ TẠO MỚI (STORE)
     */
    public function store()
    {
        $this->requireAdmin();

        // 1. Lấy dữ liệu từ POST
        $postData = $_POST;

        // 2. Validate
        $validator = new Validator();
        $rules = [
            'full_name' => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'role'      => 'required',
            // 'confirm_password' => 'required|matches:password' // Nếu bạn có field này
        ];

        if (!$validator->validate($postData, $rules)) {
            // Validator tự redirect kèm flash errors
            return;
        }

        // 3. CHUẨN BỊ DỮ LIỆU SẠCH ĐỂ LƯU DATABASE
        // (Đây là bước quan trọng để tránh lỗi "Unknown column csrf_token")
        $dataToSave = [
            'full_name' => $postData['full_name'],
            'email'     => $postData['email'],
            'role'      => $postData['role'],
            'password'  => password_hash($postData['password'], PASSWORD_DEFAULT),
            'status'    => 1 // Mặc định hoạt động
        ];

        // 4. Gọi Model
        $this->userModel->create($dataToSave);

        flash('success', 'Thêm tài khoản thành công!');
        redirect('/users');
    }

    public function edit()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            flash('error', 'Không tìm thấy ID.');
            redirect('/users');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            flash('error', 'Người dùng không tồn tại.');
            redirect('/users');
        }

        return view('users/edit', [
            'title' => 'Chỉnh sửa User',
            'user'  => $user
        ]);
    }

    /**
     * XỬ LÝ CẬP NHẬT (UPDATE)
     */
    public function update()
    {
        $this->requireAdmin();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            flash('error', 'Thiếu ID User.');
            redirect('/users');
        }

        $postData = $_POST;

        // 1. Validate cơ bản
        $validator = new Validator();
        $rules = [
            'full_name' => 'required',
            'email'     => 'required|email|unique:users,' . $id, // Check trùng trừ ID hiện tại
            'role'      => 'required'
        ];

        if (!$validator->validate($postData, $rules)) {
            // Nếu lỗi, redirect về trang edit hiện tại
            redirect('/users/edit?id=' . $id);
        }

        // 2. CHUẨN BỊ DỮ LIỆU SẠCH (Lọc bỏ csrf_token tại đây)
        $dataToUpdate = [
            'full_name' => $postData['full_name'],
            'email'     => $postData['email'],
            'role'      => $postData['role']
        ];

        // 3. Xử lý mật khẩu (Chỉ cập nhật nếu có nhập)
        if (!empty($postData['password'])) {
            if (strlen($postData['password']) < 6) {
                flash('error', 'Mật khẩu mới phải có ít nhất 6 ký tự.');
                redirect('/users/edit?id=' . $id);
            }
            // Nếu có nhập mật khẩu mới thì thêm vào mảng update
            $dataToUpdate['password'] = password_hash($postData['password'], PASSWORD_DEFAULT);
        }

        // 4. Gọi Model để update
        // (Lúc này $dataToUpdate chỉ chứa các cột có thật trong DB -> Không bị lỗi nữa)
        $this->userModel->update($id, $dataToUpdate);

        flash('success', 'Cập nhật thông tin thành công!');
        redirect('/users');
    }

    public function destroy()
    {
        $this->requireAdmin();
        
        // Luôn trả về JSON
        header('Content-Type: application/json');

        try {
            // 1. Nhận dữ liệu
            $id = $_POST['id'] ?? null;
            $tokenFromPost = $_POST['csrf_token'] ?? '';
            $tokenFromSession = $_SESSION['csrf_token'] ?? '';

            // Ghi log để debug (Kiểm tra file C:\xampp\apache\logs\error.log nếu lỗi)
            error_log("DELETE USER: ID=$id | TokenPost=$tokenFromPost | TokenSession=$tokenFromSession");

            // 2. Kiểm tra ID
            if (!$id) {
                throw new \Exception('Thiếu ID người dùng.');
            }

            // 3. Kiểm tra User có tồn tại không
            $user = $this->userModel->find($id);
            if (!$user) {
                throw new \Exception('Người dùng không tồn tại hoặc đã bị xóa.');
            }

            // 4. Kiểm tra CSRF (Bảo mật)
            if (empty($tokenFromSession) || $tokenFromPost !== $tokenFromSession) {
                // Tạm thời comment dòng dưới nếu bạn muốn test bỏ qua bảo mật
                throw new \Exception('Lỗi bảo mật: CSRF Token không khớp. Vui lòng F5 lại trang.');
            }

            // 5. Chặn xóa chính mình
            if ((int)$id === (int)$_SESSION['user']['id']) {
                throw new \Exception('Bạn không thể xóa tài khoản đang đăng nhập.');
            }

            // 6. Thực hiện xóa
            $this->userModel->delete($id);

            echo json_encode(['success' => true, 'message' => 'Xóa thành công.']);
            exit();

        } catch (\Exception $e) {
            // Trả về HTTP 200 nhưng success = false để JS xử lý alert
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit();
        }
    }

    /**
     * Xuất Excel danh sách User
     */
    public function exportExcel()
    {
        $this->requireAdmin();

        // 1. Lấy tham số filter
        $keyword = $_GET['keyword'] ?? '';
        $role    = $_GET['role'] ?? '';

        // 2. Lấy dữ liệu
        $users = $this->userModel->getAllForExport($keyword, $role);

        // 3. Khởi tạo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sách Tài khoản');

        // Header
        $headers = ['ID', 'Họ tên', 'Email', 'Vai trò', 'Trạng thái', 'Ngày tạo'];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Data
        $rows = [];
        foreach ($users as $user) {
            // Xử lý hiển thị text
            $roleText = ($user['role'] === 'admin') ? 'Quản trị viên' : 'Nhân sự (HR)';
            $statusText = ($user['status'] == 1) ? 'Hoạt động' : 'Đã khóa';

            $rows[] = [
                $user['id'],
                $user['full_name'],
                $user['email'],
                $roleText,
                $statusText,
                date('d/m/Y H:i', strtotime($user['created_at']))
            ];
        }

        if (!empty($rows)) {
            $sheet->fromArray($rows, NULL, 'A2');
        }

        // Style
        $lastRow = count($rows) + 1;
        $sheet->getStyle("A1:F{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0d6efd']], // Màu xanh Primary
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        foreach (range('A', 'F') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

        // Output
        $fileName = 'DS_TaiKhoan_' . date('dmY_Hi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Xuất PDF danh sách User
     */
    public function exportPDF()
    {
        $this->requireAdmin();

        $keyword = $_GET['keyword'] ?? '';
        $role    = $_GET['role'] ?? '';

        $users = $this->userModel->getAllForExport($keyword, $role);

        // HTML Template
        $html = '
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
                h2 { text-align: center; color: #0d6efd; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #0d6efd; color: white; padding: 8px; border: 1px solid #333; font-weight: bold; }
                td { padding: 8px; border: 1px solid #444; text-align: center; }
                .text-left { text-align: left; }
            </style>
        </head>
        <body>
            <h2>DANH SÁCH TÀI KHOẢN HỆ THỐNG</h2>
            <p style="text-align:center">Ngày xuất: ' . date('d/m/Y H:i') . '</p>
            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="25%">Họ tên</th>
                        <th width="25%">Email</th>
                        <th width="15%">Vai trò</th>
                        <th width="15%">Trạng thái</th>
                        <th width="15%">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($users as $user) {
            $roleText = ($user['role'] === 'admin') ? 'Quản trị viên' : 'Nhân sự';
            $statusText = ($user['status'] == 1) ? 'Hoạt động' : 'Đã khóa';

            $html .= '<tr>
                <td>' . $user['id'] . '</td>
                <td class="text-left">' . $user['full_name'] . '</td>
                <td class="text-left">' . $user['email'] . '</td>
                <td>' . $roleText . '</td>
                <td>' . $statusText . '</td>
                <td>' . date('d/m/Y', strtotime($user['created_at'])) . '</td>
            </tr>';
        }

        $html .= '</tbody></table></body></html>';

        try {
            $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
            $mpdf->WriteHTML($html);
            $mpdf->Output('DS_TaiKhoan_' . date('dmY') . '.pdf', 'D');
        } catch (\Exception $e) {
            echo "Lỗi xuất PDF: " . $e->getMessage();
        }
        exit;
    }
}
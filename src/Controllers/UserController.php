<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;        // <-- Dùng Model
use App\Core\Validator;   // <-- Dùng Validator

class UserController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        // Khởi tạo Model 1 lần để dùng chung
        $this->userModel = new User(); 
    }

    /**
     * Hiển thị danh sách với Logic Tìm kiếm & Phân trang
     */
    public function index()
    {
        $this->requireAdmin();

        // 1. Lấy tham số từ URL
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $role    = isset($_GET['role']) ? trim($_GET['role']) : '';
        $page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit   = 10; // Số dòng mỗi trang
        $offset  = ($page - 1) * $limit;

        // 2. Gọi Model để lấy dữ liệu
        $users = $this->userModel->getPaginated($keyword, $role, $limit, $offset);
        $totalRecords = $this->userModel->countAll($keyword, $role);

        // 3. Tính tổng số trang
        $totalPages = ceil($totalRecords / $limit);

        // 4. Chuẩn bị dữ liệu gửi sang View
        $data = [
            'title'        => 'Quản lý Users',
            'users'        => $users,
            'currentPage'  => $page,
            'totalPages'   => $totalPages,
            'totalRecords' => $totalRecords,
            'keyword'      => $keyword,
            'role'         => $role
        ];

        // Hỗ trợ AJAX (nếu sau này bạn muốn reload bảng mà không tải lại trang)
        if (isAjaxRequest()) {
            return partial('users/index', $data);
        }

        return view('users/index', $data);
    }

    public function create()
    {
        $this->requireAdmin();
        
        $data = [
            'title' => 'Thêm Nhân viên mới'
        ];

        if (isAjaxRequest()) {
            return partial('users/create', $data);
        }
        return view('users/create', $data);
    }

    /**
     * Xử lý lưu mới (đã "tái cấu trúc")
     */
    public function store()
    {
        $this->requireAdmin();

        // 1. Định nghĩa "quy tắc"
        $rules = [
            'full_name' => 'required|min:3',
            'email' => 'required|email|unique:users', // 'unique:users' sẽ tự kiểm tra CSDL
            'password' => 'required|min:6',
            'confirm_password' => 'required|matches:password' // 'matches:password' tự so sánh
        ];

        // 2. Giao cho Validator
        $validator = new Validator();
        
        // 3. Chạy kiểm tra
        if (!$validator->validate($_POST, $rules)) {
            // Validator đã tự động flash lỗi và redirect
            return;
        }

        // 4. Nếu code chạy đến đây -> Dữ liệu HỢP LỆ
        // (Chúng ta truyền $_POST vì UserModel đã tự xử lý hashing)
        $this->userModel->create($_POST); 

        flash('success', 'Thêm nhân viên mới thành công!');
        redirect('/users');
    }

    public function edit()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('/users'); // Giữ nguyên redirect lỗi

        $user = $this->userModel->find($id);
        if (!$user) {
            flash('error', 'Không tìm thấy nhân viên.');
            redirect('/users'); // Giữ nguyên redirect lỗi
        }
        
        $data = [
            'title' => 'Chỉnh sửa Nhân viên',
            'user' => $user
        ];

        if (isAjaxRequest()) {
            return partial('users/edit', $data);
        }
        return view('users/edit', $data);
    }

    /**
     * Xử lý cập nhật (đã "tái cấu trúc")
     */
    public function update()
    {
        $this->requireAdmin();
        
        $data = $_POST;
        $id = $data['id'] ?? null;
        if (!$id) redirect('/users');

        // 1. Định nghĩa "quy tắc"
        $rules = [
            'full_name' => 'required|min:3',
            // 'unique:users,15' -> tự kiểm tra, ngoại trừ ID 15
            'email' => 'required|email|unique:users,' . $id, 
        ];

        // Chỉ validate password nếu người dùng gõ
        if (!empty($data['password'])) {
            $rules['password'] = 'required|min:6';
            $rules['confirm_password'] = 'required|matches:password';
        }

        // 2. Giao cho Validator
        $validator = new Validator();
        
        if (!$validator->validate($data, $rules)) {
            return; // Validator tự redirect
        }

        // 3. Nếu OK -> Gọi Model
        // (UserModel tự xử lý logic "không update pass nếu rỗng")
        $this->userModel->update($id, $data);

        flash('success', 'Cập nhật thông tin nhân viên thành công!');
        redirect('/users');
    }

    /**
     * Xử lý xóa (phiên bản AJAX, không đổi)
     */
    public function destroy()
    {
        $this->requireAdmin();
        
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new \Exception('Thiếu ID của User.');
            }

            if ((int)$id === (int)$_SESSION['user']['id']) {
                throw new \Exception('Bạn không thể tự xóa tài khoản Admin của chính mình.');
            }

            $this->userModel->delete($id);

            echo json_encode(['success' => true, 'message' => 'Đã xóa nhân viên thành công.']);
            exit();

        } catch (\Exception $e) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit();
        }
    }
}
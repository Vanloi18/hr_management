<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;        
use App\Core\Validator;

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
        
        $data = [
            'title' => 'Thêm Nhân viên mới'
        ];

        if (isAjaxRequest()) {
            return partial('users/create', $data);
        }
        return view('users/create', $data);
    }

    public function store()
    {
        $this->requireAdmin();

        $rules = [
            'full_name' => 'required|min:3',
            'email' => 'required|email|unique:users', 
            'password' => 'required|min:6',
            'confirm_password' => 'required|matches:password' 
        ];

        $validator = new Validator();
        
        if (!$validator->validate($_POST, $rules)) {
            return;
        }

        $this->userModel->create($_POST); 

        flash('success', 'Thêm nhân viên mới thành công!');
        redirect('/users');
    }

    public function edit()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('/users'); 

        $user = $this->userModel->find($id);
        if (!$user) {
            flash('error', 'Không tìm thấy nhân viên.');
            redirect('/users'); 
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

    public function update()
    {
        $this->requireAdmin();
        
        $data = $_POST;
        $id = $data['id'] ?? null;
        if (!$id) redirect('/users');

        $rules = [
            'full_name' => 'required|min:3',
            'email' => 'required|email|unique:users,' . $id, 
        ];

        if (!empty($data['password'])) {
            $rules['password'] = 'required|min:6';
            $rules['confirm_password'] = 'required|matches:password';
        }

        $validator = new Validator();
        
        if (!$validator->validate($data, $rules)) {
            return; 
        }

        $this->userModel->update($id, $data);

        flash('success', 'Cập nhật thông tin nhân viên thành công!');
        redirect('/users');
    }

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
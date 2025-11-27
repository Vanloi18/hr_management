<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Department;
use App\Core\Validator; // <-- Import Validator

class DepartmentController extends Controller
{
    protected $departmentModel;

    public function __construct()
    {
        parent::__construct();
        $this->departmentModel = new Department();
    }

    /**
     * Hàm index() giữ nguyên từ file gốc
     */
    public function index()
    {
        $this->checkAuthentication();
        $departments = $this->departmentModel->all();
        
        $data = [
            'title' => 'Quản lý Phòng ban',
            'departments' => $departments
        ];

        // 🔥 LOGIC PJAX MỚI
        if (isAjaxRequest()) {
            // Nếu là AJAX, chỉ trả về "ruột"
            return partial('departments/index', $data);
        }
        
        // Nếu là tải trang bình thường, trả về "vỏ" + "ruột"
        return view('departments/index', $data);
    }

    public function create()
    {
        $this->checkAuthentication();
        
        $data = [
            'title' => 'Thêm Phòng ban mới'
        ];
        
        if (isAjaxRequest()) {
            return partial('departments/create', $data);
        }
        return view('departments/create', $data);
    }

    /**
     * Xử lý lưu mới (POST /departments) - PHIÊN BẢN AJAX + VALIDATOR
     */
    public function store()
    {
        $this->checkAuthentication();
        
        $validator = new Validator();
        $rules = [
            'name' => 'required|unique:departments' // Tự kiểm tra trùng
        ];

        // 1. Chạy Validate
        if (!$validator->validate($_POST, $rules)) {
            // 2. Validate THẤT BẠI
            if (isAjaxRequest()) {
                http_response_code(422); // Lỗi Validation
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'errors' => $validator->errors() // Gửi mảng lỗi về
                ]);
                exit();
            }
            return; // Validator đã tự redirect
        }

        // 3. Validate THÀNH CÔNG
        $name = e($_POST['name']);
        $this->departmentModel->create($name); // Gọi Model

        if (isAjaxRequest()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Thêm phòng ban thành công!',
                'redirect_url' => BASE_URL . '/departments' 
            ]);
            exit();
        }
        
        flash('success', 'Thêm phòng ban thành công!');
        redirect('/departments');
    }

    public function edit()
    {
        $this->checkAuthentication();
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('/departments'); 

        $department = $this->departmentModel->find($id);
        if (!$department) {
            flash('error', 'Không tìm thấy phòng ban.');
            redirect('/departments');
        }

        $data = [
            'title' => 'Chỉnh sửa Phòng ban',
            'department' => $department
        ];
        
        if (isAjaxRequest()) {
            return partial('departments/edit', $data);
        }
        return view('departments/edit', $data);
    }

    /**
     * Xử lý cập nhật (POST /departments/update) - Đã tái cấu trúc
     */
    public function update()
    {
        $this->checkAuthentication();
        
        $id = $_POST['id'] ?? null;
        $validator = new Validator();
        $rules = [
            // Kiểm tra trùng, ngoại trừ ID của chính nó
            'name' => 'required|unique:departments,' . $id 
        ];

        if (!$validator->validate($_POST, $rules)) {
            return; // Validator tự flash lỗi và redirect về form edit
        }
        
        $name = e($_POST['name']);
        $this->departmentModel->update($id, $name); // Gọi Model

        flash('success', 'Cập nhật phòng ban thành công!');
        redirect('/departments');
    }

    /**
     * Xử lý "Chỉnh sửa trực tiếp" (Inline Edit) - PHIÊN BẢN AJAX + VALIDATOR
     * (Hàm mới được thêm vào)
     */
    public function inlineUpdate()
    {
        $this->checkAuthentication();
        
        $id = $_POST['id'] ?? null;
        $name = e(trim($_POST['name'] ?? ''));

        $validator = new Validator();
        $rules = [
            'name' => 'required|unique:departments,' . $id
        ];

        // Dữ liệu truyền vào Validator phải là mảng
        if (!$validator->validate(['name' => $name], $rules)) { 
            // Validate THẤT BẠI
            http_response_code(422); 
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'errors' => $validator->errors() 
            ]);
            exit();
        }

        // Validate THÀNH CÔNG
        $this->departmentModel->update($id, $name); // Gọi Model

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'newName' => $name
        ]);
        exit();
    }

    /**
     * Hàm destroy() (phiên bản AJAX) giữ nguyên từ file gốc
     */
    public function destroy()
    {
        $this->checkAuthentication();
        
        // Báo cho trình duyệt biết đây là JSON
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                // Ném lỗi nếu thiếu ID
                throw new \Exception('Thiếu ID của phòng ban.');
            }

            // Gọi Model để xóa
            $this->departmentModel->delete($id); 

            // Trả về JSON thành công
            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa phòng ban thành công.'
            ]);
            exit();

        } catch (\Exception $e) {
            // Nếu có lỗi, trả về JSON lỗi
            http_response_code(500); // 500 Internal Server Error
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }
    }
}

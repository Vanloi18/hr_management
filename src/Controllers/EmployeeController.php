<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Employee;   // <-- Import Model
use App\Models\Department; // <-- Import Model liên quan
use App\Core\Validator; // 1. [THAY ĐỔI] Thêm import Validator

class EmployeeController extends Controller
{
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
        $this->checkAuthentication();
        $employees = $this->employeeModel->allWithDetails();
        
        $data = [
            'title' => 'Quản lý Nhân viên',
            'employees' => $employees
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

    /**
     * Xử lý lưu mới (POST /employees) - Đã tái cấu trúc
     * 2. [THAY ĐỔI] Cập nhật toàn bộ hàm store()
     */
    public function store()
    {
        $this->checkAuthentication();

        // 1. Validate TEXT trước
        $validator = new Validator();
        $rules = [
            'full_name' => 'required',
            'email' => 'required|email|unique:employees',
            'job_title' => 'required',
            'start_date' => 'required',
        ];
        
        if (!$validator->validate($_POST, $rules)) {
            return; // Validator tự flash lỗi, 'old' data, và redirect
        }

        // 2. Nếu Text OK -> Lấy dữ liệu và Xử lý FILES
        $data = $validator->validatedData(); // Lấy dữ liệu (sạch) từ Validator
        $data['department_id'] = !empty($_POST['department_id']) ? $_POST['department_id'] : null;
        $data['status'] = e($_POST['status'] ?? 'probation');
        $data['phone'] = e($_POST['phone'] ?? null);
        $data['photo_path'] = null;
        $data['contract_path'] = null;

        $fileErrors = [];
        try {
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $data['photo_path'] = $this->handleFileUpload(
                    $_FILES['photo'], self::PHOTO_DIR, 
                    ['jpg', 'jpeg', 'png', 'gif'], 'uploads/employees/photos/'
                );
            }
            if (isset($_FILES['contract']) && $_FILES['contract']['error'] === UPLOAD_ERR_OK) {
                $data['contract_path'] = $this->handleFileUpload(
                    $_FILES['contract'], self::CONTRACT_DIR, 
                    ['pdf', 'doc', 'docx'], 'uploads/employees/contracts/'
                );
            }
        } catch (\Exception $e) {
            $fileErrors[] = $e->getMessage();
        }

        // 3. Nếu File Lỗi -> Quay lại
        if (!empty($fileErrors)) {
            $_SESSION['_flash']['errors'] = $fileErrors; // Flash lỗi file
            $_SESSION['_flash']['old'] = $_POST; // Flash dữ liệu cũ
            // Xóa file đã lỡ upload
            $this->deleteFile($data['photo_path']);
            $this->deleteFile($data['contract_path']);
            redirect('/employees/create');
        }

        // 4. Mọi thứ OK -> Lưu CSDL
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

    /**
     * Xử lý cập nhật (POST /employees/update) - Đã tái cấu trúc
     * 3. [THAY ĐỔI] Cập nhật toàn bộ hàm update()
     */
    public function update()
    {
        $this->checkAuthentication();
        $id = $_POST['id'] ?? null;
        if (!$id) redirect('/employees');

        // 1. Validate TEXT
        $validator = new Validator();
        $rules = [
            'full_name' => 'required',
            'email' => 'required|email|unique:employees,' . $id, // Kiểm tra trùng (trừ ID này)
            'job_title' => 'required',
            'start_date' => 'required',
        ];
        
        if (!$validator->validate($_POST, $rules)) {
            return; // Validator tự redirect
        }

        // 2. Nếu Text OK -> Lấy dữ liệu và Xử lý FILES
        $data = $validator->validatedData();
        $data['department_id'] = !empty($_POST['department_id']) ? $_POST['department_id'] : null;
        $data['status'] = e($_POST['status'] ?? 'probation');
        $data['phone'] = e($_POST['phone'] ?? null);

        $oldEmployee = $this->employeeModel->find($id);
        $data['photo_path'] = $oldEmployee['photo_path']; // Giữ ảnh cũ
        $data['contract_path'] = $oldEmployee['contract_path']; // Giữ hợp đồng cũ

        try {
            // 4. [SỬA] Điền đầy đủ tham số cho handleFileUpload
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $data['photo_path'] = $this->handleFileUpload(
                    $_FILES['photo'], 
                    self::PHOTO_DIR, 
                    ['jpg', 'jpeg', 'png', 'gif'], 
                    'uploads/employees/photos/'
                );
                $this->deleteFile($oldEmployee['photo_path']);
            }
            // 4. [SỬA] Điền đầy đủ tham số cho handleFileUpload
            if (isset($_FILES['contract']) && $_FILES['contract']['error'] === UPLOAD_ERR_OK) {
                $data['contract_path'] = $this->handleFileUpload(
                    $_FILES['contract'], 
                    self::CONTRACT_DIR, 
                    ['pdf', 'doc', 'docx'], 
                    'uploads/employees/contracts/'
                );
                $this->deleteFile($oldEmployee['contract_path']);
            }
        } catch (\Exception $e) {
            flash('error', $e->getMessage()); // Flash lỗi file
            redirect('/employees/edit?id=' . $id);
        }
        
        // 3. Mọi thứ OK -> Cập nhật CSDL
        $this->employeeModel->update($id, $data);

        flash('success', 'Cập nhật hồ sơ nhân viên thành công!');
        redirect('/employees');
    }


    /**
     * Xử lý xóa (POST /employees/delete) - PHIÊN BẢN AJAX
     * (Giữ nguyên)
     */
    public function destroy()
    {
        $this->checkAuthentication();
        
        // Báo cho trình duyệt biết đây là JSON
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new \Exception('Thiếu ID của nhân viên.');
            }

            // 1. Lấy thông tin file trước khi xóa CSDL
            $employee = $this->employeeModel->find($id);

            // 2. Xóa CSDL (Gọi Model)
            $this->employeeModel->delete($id);

            // 3. Xóa file vật lý (nếu có)
            if ($employee) {
                $this->deleteFile($employee['photo_path']);
                $this->deleteFile($employee['contract_path']);
            }
            
            // Trả về JSON thành công
            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa hồ sơ nhân viên (và các file liên quan).'
            ]);
            exit();

        } catch (\Exception $e) {
            // Nếu có lỗi, trả về JSON lỗi
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }
    }


    /* --- CÁC HÀM HELPER (HỖ TRỢ) PRIVATE --- */
    /* (Giữ nguyên các hàm private) */

    /**
     * Xử lý upload file.
     * @return string $relativePath Đường dẫn file (tương đối, để lưu vào DB)
     */
    private function handleFileUpload($fileInfo, $dir, $allowedExt, $relativePathPrefix)
    {
        if ($fileInfo['error'] !== UPLOAD_ERR_OK) throw new \Exception('File upload bị lỗi.');

        $extension = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExt)) {
            throw new \Exception('Định dạng file không hợp lệ. Chỉ cho phép: ' . implode(', ', $allowedExt));
        }
        
        // (Bạn có thể thêm kiểm tra size file ở đây nếu muốn)

        $fileName = uniqid() . '-' . basename($fileInfo['name']);
        $destination = $dir . $fileName;
        $relativePath = $relativePathPrefix . $fileName; 

        if (move_uploaded_file($fileInfo['tmp_name'], $destination)) {
            return $relativePath;
        } else {
            throw new \Exception('Không thể di chuyển file đã upload.');
        }
    }

    /**
     * Xóa 1 file vật lý
     */
    private function deleteFile($relativePath)
    {
        if (empty($relativePath)) return;
        
        // $relativePath là 'uploads/...'
        // Đường dẫn tuyệt đối là BASE_PATH . 'public/' . $relativePath
        $fullPath = BASE_PATH . 'public/' . $relativePath;
        
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
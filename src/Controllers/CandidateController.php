<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Candidate; // <-- Import Model
use App\Models\Position;  // <-- Import Model liên quan
use App\Core\Validator; // <-- 1. Import Validator

class CandidateController extends Controller
{
    private const CV_UPLOAD_DIR = BASE_PATH . 'public/uploads/cvs/';
    private const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx'];
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB

    protected $candidateModel;

    public function __construct()
    {
        parent::__construct();
        $this->candidateModel = new Candidate();
    }

    public function index()
    {
        $this->checkAuthentication();
        $search = trim($_GET['search'] ?? null);
        define('ITEMS_PER_PAGE', 10);
        $currentPage = (int)($_GET['page'] ?? 1);
        if ($currentPage < 1) $currentPage = 1;
        $offset = ($currentPage - 1) * ITEMS_PER_PAGE;

        $totalCandidates = $this->candidateModel->countAll($search);
        $totalPages = ceil($totalCandidates / ITEMS_PER_PAGE);
        $candidates = $this->candidateModel->allWithDetails($search, ITEMS_PER_PAGE, $offset);

        $data = [
            'title' => 'Quản lý Ứng viên (CVs)',
            'candidates' => $candidates,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'search' => $search
        ];
        
        if (isAjaxRequest()) {
            return partial('candidates/index', $data);
        }
        return view('candidates/index', $data);
    }

    public function create()
    {
        $this->checkAuthentication();
        $positions = (new Position())->allOpenForDropdown();
        $selected_position_id = $_GET['position_id'] ?? null;
        
        $data = [
            'title' => 'Thêm Ứng viên mới',
            'positions' => $positions,
            'selected_position_id' => $selected_position_id
        ];

        if (isAjaxRequest()) {
            return partial('candidates/create', $data);
        }
        return view('candidates/create', $data);
    }

    /**
     * Xử lý lưu mới (POST /candidates) - Đã tái cấu trúc
     */
    public function store()
    {
        $this->checkAuthentication();
        
        // 1. Validate TEXT
        $validator = new Validator();
        $rules = [
            'position_id' => 'required',
            'full_name' => 'required',
            'email' => 'required|email|unique:candidates',
            'phone' => 'required',
        ];

        // 2. Ghi chú: Validator tự redirect về trang trước (create) nếu thất bại
        if (!$validator->validate($_POST, $rules)) {
            return; 
        }

        // 3. Nếu Text OK -> Lấy dữ liệu và Xử lý FILES
        $data = $validator->validatedData();
        $data['status'] = e($_POST['status'] ?? 'pending');
        $data['notes'] = e($_POST['notes'] ?? null);
        $data['cv_file_path'] = null;
        
        $file_info = $_FILES['cv_file'] ?? null;
        $fileErrors = [];
        
        try {
            if (empty($file_info) || $file_info['error'] === UPLOAD_ERR_NO_FILE) {
                // File CV là bắt buộc khi TẠO MỚI
                throw new \Exception('File CV là bắt buộc.');
            }
            $data['cv_file_path'] = $this->handleFileUpload($file_info); // Gọi hàm helper
        } catch (\Exception $e) {
            $fileErrors[] = $e->getMessage();
        }
        
        // 4. Nếu File Lỗi -> Quay lại
        if (!empty($fileErrors)) {
            $_SESSION['_flash']['errors'] = $fileErrors;
            $_SESSION['_flash']['old'] = $_POST;
            // 2. Ghi chú: Dùng BASE_URL để đảm bảo đường dẫn
            redirect('/candidates/create');
        }

        // 5. Mọi thứ OK -> Lưu CSDL
        $this->candidateModel->create($data);

        flash('success', 'Thêm ứng viên thành công!');
        redirect('/candidates');
    }

    public function edit()
    {
        $this->checkAuthentication();
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('/candidates');

        $candidate = $this->candidateModel->find($id);
        if (!$candidate) {
            flash('error', 'Không tìm thấy ứng viên.');
            redirect('/candidates');
        }
        
        $positions = (new Position())->allOpenForDropdown();
        
        $data = [
            'title' => 'Chỉnh sửa Ứng viên',
            'candidate' => $candidate,
            'positions' => $positions
        ];

        if (isAjaxRequest()) {
            return partial('candidates/edit', $data);
        }
        return view('candidates/edit', $data);
    }

    /**
     * Xử lý cập nhật (POST /candidates/update) - Đã tái cấu trúc
     */
    public function update()
    {
        $this->checkAuthentication();
        $id = $_POST['id'] ?? null;
        if (!$id) redirect('/candidates');

        // 1. Validate TEXT
        $validator = new Validator();
        $rules = [
            'position_id' => 'required',
            'full_name' => 'required',
            // 2. Ghi chú: Rule 'unique' khi update cần kèm ID
            'email' => 'required|email|unique:candidates,' . $id,
            'phone' => 'required',
        ];

        // Validator tự redirect về trang trước (edit) nếu thất bại
        if (!$validator->validate($_POST, $rules)) {
            return;
        }

        // 2. Nếu Text OK -> Lấy dữ liệu và Xử lý FILES
        $data = $validator->validatedData();
        $data['status'] = e($_POST['status'] ?? 'pending');
        $data['notes'] = e($_POST['notes'] ?? null);
        $file_info = $_FILES['cv_file'] ?? null;

        $oldCandidate = $this->candidateModel->find($id);
        $data['cv_file_path'] = $oldCandidate['cv_file_path']; // Giữ file cũ

        // Chỉ xử lý file nếu có file MỚI được upload
        if ($file_info && $file_info['error'] === UPLOAD_ERR_OK) {
            try {
                $data['cv_file_path'] = $this->handleFileUpload($file_info);
                // Xóa file cũ
                if ($oldCandidate['cv_file_path'] && file_exists(self::CV_UPLOAD_DIR . basename($oldCandidate['cv_file_path']))) {
                    unlink(self::CV_UPLOAD_DIR . basename($oldCandidate['cv_file_path']));
                }
            } catch (\Exception $e) {
                flash('error', $e->getMessage());
                redirect('/candidates/edit?id=' . $id);
            }
        }

        // 3. Cập nhật CSDL
        $this->candidateModel->update($id, $data);

        flash('success', 'Cập nhật ứng viên thành công!');
        redirect('/candidates');
    }

    /**
     * Xử lý xóa (POST /candidates/delete) - PHIÊN BẢN AJAX
     */
    public function destroy()
    {
        $this->checkAuthentication();
        
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new \Exception('Thiếu ID của Ứng viên.');
            }

            // 1. Lấy thông tin file CV trước khi xóa
            $candidate = $this->candidateModel->find($id);

            // 2. Xóa CSDL (Gọi Model)
            $this->candidateModel->delete($id);

            // 3. Xóa file vật lý (nếu có)
            if ($candidate && $candidate['cv_file_path']) {
                $filePath = self::CV_UPLOAD_DIR . basename($candidate['cv_file_path']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Trả về JSON thành công
            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa ứng viên (và file CV liên quan).'
            ]);
            exit();

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }
    }

    // --- Các hàm helper private ---
    
    /**
     * 3. Ghi chú: Xóa hàm validateInput()
     * Hàm này không còn cần thiết vì đã dùng Validator
     */
    // private function validateInput($name, $email, $phone, $pid, $id = null) { ... }
    
    /**
     * HÀM HỖ TRỢ (HELPER)
     * * Xử lý upload file.
     * @return string $relativePath Đường dẫn file (tương đối, để lưu vào DB)
     * @throws \Exception Nếu có lỗi
     */
    private function handleFileUpload($fileInfo)
    {
        if ($fileInfo['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('File upload bị lỗi.');
        }

        if ($fileInfo['size'] > self::MAX_FILE_SIZE) {
            throw new \Exception('File quá lớn. Tối đa 5MB.');
        }

        $extension = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \Exception('Chỉ chấp nhận file định dạng: ' . implode(', ', self::ALLOWED_EXTENSIONS));
        }

        $fileName = uniqid() . '-' . basename($fileInfo['name']);
        $destination = self::CV_UPLOAD_DIR . $fileName;
        $relativePath = 'uploads/cvs/' . $fileName; 

        if (move_uploaded_file($fileInfo['tmp_name'], $destination)) {
            return $relativePath;
        } else {
            throw new \Exception('Không thể di chuyển file đã upload.');
        }
    }
}
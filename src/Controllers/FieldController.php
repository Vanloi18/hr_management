<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Field;
use App\Core\Validator; // <-- Import Validator

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
        $fields = $this->fieldModel->all(); 

        $data = [
            'title' => 'Quản lý Lĩnh vực',
            'fields' => $fields
        ];

        // 🔥 LOGIC PJAX MỚI
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
}

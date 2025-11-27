<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Position;
use App\Models\Recruiter;
use App\Models\Field;
use App\Core\Validator;

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

        // 1. Lấy tham số filter
        $keyword      = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $status       = isset($_GET['status']) ? trim($_GET['status']) : '';
        $recruiter_id = isset($_GET['recruiter_id']) ? trim($_GET['recruiter_id']) : '';
        $page         = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit  = 10;
        $offset = ($page - 1) * $limit;

        // 2. Lấy dữ liệu
        $positions = $this->positionModel->getPaginated($keyword, $status, $recruiter_id, $limit, $offset);
        $totalRecords = $this->positionModel->countAll($keyword, $status, $recruiter_id);
        $totalPages = ceil($totalRecords / $limit);
        
        // Lấy danh sách công ty để hiển thị dropdown lọc
        $recruitersList = $this->positionModel->getRecruitersList();

        // 3. Đóng gói dữ liệu
        $data = [
            'title'          => 'Quản lý Tin tuyển dụng',
            'positions'      => $positions,
            'recruitersList' => $recruitersList, // Truyền list công ty sang view
            'currentPage'    => $page,
            'totalPages'     => $totalPages,
            'totalRecords'   => $totalRecords,
            'keyword'        => $keyword,
            'status'         => $status,
            'recruiter_id'   => $recruiter_id
        ];

        // 4. Xử lý AJAX (Chống lỗi lồng layout)
        if (isAjaxRequest()) {
            return partial('positions/index', $data);
        }

        return view('positions/index', $data);
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

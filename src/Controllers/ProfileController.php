<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\Validator;

class ProfileController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * Hiển thị trang hồ sơ
     */
    public function index()
    {
        $this->checkAuthentication();

        // Lấy thông tin mới nhất từ DB (tránh dùng session cũ)
        $user = $this->userModel->find($_SESSION['user']['id']);

        $data = [
            'title' => 'Hồ sơ cá nhân',
            'user' => $user
        ];

        // Hỗ trợ AJAX navigation
        if (isAjaxRequest()) {
            return partial('profile/index', $data);
        }

        return view('profile/index', $data);
    }

    /**
     * Xử lý cập nhật thông tin
     */
    public function update()
    {
        $this->checkAuthentication();
        $id = $_SESSION['user']['id'];
        $data = $_POST;

        // 1. Validate dữ liệu cơ bản
        $rules = [
            'full_name' => 'required|min:3',
            'email' => 'required|email' // Kiểm tra email trùng sẽ xử lý riêng bên dưới
        ];

        // Nếu có nhập mật khẩu thì validate thêm
        if (!empty($data['password'])) {
            $rules['password'] = 'min:6';
            $rules['confirm_password'] = 'required|matches:password';
        }

        $validator = new Validator();
        if (!$validator->validate($data, $rules)) {
            redirect('/profile');
        }

        // 2. Kiểm tra Email có bị trùng với người khác không
        $existingUser = $this->userModel->findByEmailAndNotId($data['email'], $id);
        if ($existingUser) {
            flash('error', 'Email này đã được sử dụng bởi tài khoản khác.');
            redirect('/profile');
        }

        // 3. Xử lý Avatar (Nếu có upload file)
        // Lưu ý: Cần đảm bảo form HTML có enctype="multipart/form-data"
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['avatar']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $newName = 'avatar_' . $id . '_' . time() . '.' . $ext;
                $uploadDir = 'uploads/avatars/';
                
                // Tạo thư mục nếu chưa có
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $newName)) {
                    $data['avatar'] = $newName; // Thêm tên file vào data để update DB
                }
            }
        } else {
            // Giữ nguyên avatar cũ
            $currentUser = $this->userModel->find($id);
            $data['avatar'] = $currentUser['avatar'];
        }

        // 4. Giữ nguyên role của user (không cho tự sửa quyền)
        $data['role'] = $_SESSION['user']['role'];

        // 5. Cập nhật vào DB
        $this->userModel->update($id, $data);

        // 6. Cập nhật lại Session để hiển thị đúng trên Topbar ngay lập tức
        $_SESSION['user']['full_name'] = $data['full_name'];
        $_SESSION['user']['email'] = $data['email'];
        $_SESSION['user']['avatar'] = $data['avatar'];

        flash('success', 'Cập nhật hồ sơ thành công!');
        redirect('/profile');
    }
}
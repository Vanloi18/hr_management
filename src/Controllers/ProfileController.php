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
     * ================================
     * HIỂN THỊ TRANG PROFILE
     * ================================
     */
    public function index()
    {
        $this->checkAuthentication();

        $user = $this->userModel->find($_SESSION['user']['id']);

        $data = [
            'title' => 'Hồ sơ cá nhân',
            'user' => $user
        ];

        if (isAjaxRequest()) {
            return partial('profile/index', $data);
        }

        return view('profile/index', $data);
    }


    /**
     * ================================
     * CẬP NHẬT THÔNG TIN CÁ NHÂN
     * ================================
     */
    public function updateInfo()
    {
        $this->checkAuthentication();
        $userId = $_SESSION['user']['id'];
        $user = $this->userModel->find($userId);

        $data = [
            'full_name' => trim($_POST['full_name']),
        ];

        // Validate tên
        $validator = new Validator();
        if (!$validator->validate($data, [
            'full_name' => 'required|min:3'
        ])) {
            redirect('/settings?tab=personal');
        }

        /**
         * XỬ LÝ UPLOAD ẢNH
         */
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {

            $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt)) {
                flash('error', 'Định dạng ảnh không hợp lệ.');
                redirect('/settings?tab=personal');
            }

            $uploadDir = BASE_PATH . 'public/uploads/avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileName = 'user_' . $userId . '_' . time() . '.' . $ext;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $fileName)) {

                // Xóa avatar cũ
                if (!empty($user['avatar']) && file_exists($uploadDir . $user['avatar'])) {
                    unlink($uploadDir . $user['avatar']);
                }

                $data['avatar'] = $fileName;

                // Cập nhật session
                $_SESSION['user']['avatar'] = $fileName;
            }
        } else {
            // Không upload => giữ avatar cũ
            $data['avatar'] = $user['avatar'];
        }

        // Update DB
        $this->userModel->update($userId, $data);

        // Update session name
        $_SESSION['user']['full_name'] = $data['full_name'];

        flash('success', 'Đã cập nhật thông tin cá nhân.');
        redirect('/settings?tab=personal');
    }


    /**
     * ================================
     * ĐỔI MẬT KHẨU
     * ================================
     */
    public function changePassword()
    {
        $this->checkAuthentication();
        $userId = $_SESSION['user']['id'];
        $user = $this->userModel->find($userId);

        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        if (!password_verify($current, $user['password'])) {
            flash('error', 'Mật khẩu hiện tại không đúng.');
            redirect('/settings?tab=security');
        }

        if ($new !== $confirm) {
            flash('error', 'Mật khẩu xác nhận không khớp.');
            redirect('/settings?tab=security');
        }

        if (strlen($new) < 6) {
            flash('error', 'Mật khẩu mới phải ít nhất 6 ký tự.');
            redirect('/settings?tab=security');
        }

        // Cập nhật mật khẩu
        $this->userModel->update($userId, [
            'password' => password_hash($new, PASSWORD_DEFAULT)
        ]);

        flash('success', 'Đổi mật khẩu thành công!');
        redirect('/settings?tab=security');
    }
}

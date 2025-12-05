<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    protected $settingModel;

    public function __construct()
    {
        parent::__construct();
        $this->settingModel = new Setting();
    }

    public function index()
    {
        $this->checkAuthentication();
        
        $settings = $this->settingModel->getAllSettings();
        
        // Lấy thông tin user đang đăng nhập để hiển thị vào form Cá nhân
        $userModel = new \App\Models\User();
        $currentUser = $userModel->find($_SESSION['user']['id']);

        // Thông tin hệ thống (Lấy động)
        $systemInfo = [
            'php_version' => phpversion(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'],
            'db_connection' => 'MySQL', // Hoặc lấy từ config
            'app_version' => '1.0.0',
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size')
        ];

        $data = [
            'title' => 'Cài đặt hệ thống',
            'settings' => $settings,
            'user' => $currentUser,
            'systemInfo' => $systemInfo
        ];

        return view('settings/index', $data);
    }

    public function update()
    {
        $this->checkAuthentication();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy mảng settings từ form
            $postSettings = $_POST['settings'] ?? [];

            foreach ($postSettings as $key => $value) {
                // Xử lý dữ liệu đầu vào (trim, htmlspecialchars nếu cần)
                $cleanValue = trim($value);
                $this->settingModel->updateSetting($key, $cleanValue);
            }

            flash('success', 'Cập nhật cấu hình thành công!');
            redirect('/settings');
        }
    }
}
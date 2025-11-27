<?php

namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $this->checkAuthentication();
        $user = $_SESSION['user'];
        
        // 1. Gói dữ liệu
        $data = [
            'title' => 'Bảng điều khiển', // (Thêm title)
            'full_name' => $user['full_name']
        ];

        // 2. Logic PJAX
        if (isAjaxRequest()) {
            return partial('dashboard', $data); // Chỉ trả về "ruột"
        }
        
        return view('dashboard', $data); // Trả về "vỏ" + "ruột"
    }
}
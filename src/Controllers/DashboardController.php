<?php

namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $this->checkAuthentication();
        $user = $_SESSION['user'];
        $data = [
            'title' => 'Bảng điều khiển', 
            'full_name' => $user['full_name']
        ];

        if (isAjaxRequest()) {
            return partial('dashboard', $data); 
        }
        
        return view('dashboard', $data); 
    }
}
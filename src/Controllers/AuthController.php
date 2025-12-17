<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function index()
    {
        if (isset($_SESSION['user'])) {
            redirect('/'); 
        }
        return view('login');
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? null);
        $password = trim($_POST['password'] ?? null);

        if (empty($email) || empty($password)) {
            flash('error', 'Email và mật khẩu là bắt buộc.');
            redirect('/login');
        }

        // Controller gọi Model
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'avatar' => $user['avatar']
            ];
            redirect('/');
        } else {
            flash('error', 'Email hoặc mật khẩu không chính xác.');
            redirect('/login'); 
        }
    }

    public function logout()
{
    if (isset($_SESSION['user'])) {
        unset($_SESSION['user']);
    }
    $_SESSION['auth_message'] = [
        'type' => 'success',
        'text' => 'Bạn đã đăng xuất thành công!'
    ];
    redirect('/login');
}
}
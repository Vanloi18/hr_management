<?php

namespace App\Core;

abstract class Controller
{
    /** @var Database $db */
    protected Database $db;

    public function __construct()
    {

        $this->db = Database::getInstance();
    }

    protected function authorize(bool $condition, string $message = 'This action is unauthorized.')
    {
        if (! $condition) {
            throw new Exceptions\UnauthorizedException($message);
        }
    }

    protected function checkAuthentication(): void
    {
        if (!isset($_SESSION['user'])) {
            flash('error', 'Bạn phải đăng nhập để truy cập trang này.');
            redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->checkAuthentication();
        
        $this->authorize(
            $_SESSION['user']['role'] === 'admin',
            'Bạn không có quyền Admin để thực hiện hành động này.'
        );
    }
}
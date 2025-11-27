<?php

namespace App\Core;

/**
 * Lớp Controller "cha"
 * Chứa các logic chung mà tất cả Controller con sẽ kế thừa
 */
abstract class Controller
{
    /** @var Database $db */
    protected Database $db;

    public function __construct()
    {
        // Tự động lấy kết nối CSDL khi Controller được "new"
        // Lớp con (ví dụ: AuthController) sẽ tự động có $this->db
        $this->db = Database::getInstance();
    }

    /**
     * Kiểm tra quyền (phiên bản method của hàm authorize())
     * Dùng bên trong Controller: $this->authorize(...)
     */
    protected function authorize(bool $condition, string $message = 'This action is unauthorized.')
    {
        if (! $condition) {
            throw new Exceptions\UnauthorizedException($message);
        }
    }

    /**
     * Hàm helper để kiểm tra xem người dùng đã đăng nhập chưa
     */
    protected function checkAuthentication(): void
    {
        if (!isset($_SESSION['user'])) {
            flash('error', 'Bạn phải đăng nhập để truy cập trang này.');
            redirect('/login');
        }
    }
    
    /**
     * Hàm helper để kiểm tra xem có phải Admin không
     */
    protected function requireAdmin(): void
    {
        $this->checkAuthentication();
        
        $this->authorize(
            $_SESSION['user']['role'] === 'admin',
            'Bạn không có quyền Admin để thực hiện hành động này.'
        );
    }
}
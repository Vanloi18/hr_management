<?php

// Định nghĩa các hằng số cấu hình CSDL
// Thay đổi các giá trị này cho phù hợp với môi trường của em
define('DB_HOST', '127.0.0.1'); // Hoặc 'localhost'
define('DB_PORT', '3306');
define('DB_NAME', 'hr_management'); // Tên DB em đã tạo ở Bước 1
define('DB_USER', 'root'); // Username CSDL
define('DB_PASS', '123456');     // Mật khẩu CSDL (để trống nếu không có)
define('DB_CHARSET', 'utf8mb4');

// Định nghĩa đường dẫn gốc của ứng dụng
// Dùng cho các hàm helper (ví dụ: hàm redirect, view...)
define('BASE_PATH', __DIR__ . '/'); // Thư mục gốc của dự án
define('BASE_URL', 'http://localhost/hr_management/public');
?>
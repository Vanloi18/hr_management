# Báo cáo dự án HR Management

## 1. Giới thiệu dự án
- Tên dự án: HR Management
- Loại dự án: Web application quản lý nhân sự
- Công nghệ chính: PHP, MySQL, HTML/PHP view
- Kiến trúc: custom MVC nhẹ, sử dụng autoload PSR-4
- Mục đích: Quản lý người dùng, nhân viên, phòng ban, nhà tuyển dụng, ứng viên, vị trí, thống kê và báo cáo

## 2. Mục tiêu chính
- Quản lý đăng nhập, phân quyền người dùng
- Quản lý CRUD:
  - Người dùng (`users`)
  - Phòng ban (`departments`)
  - Nhân viên (`employees`)
  - Nhà tuyển dụng (`recruiters`)
  - Lĩnh vực (`fields`)
  - Vị trí tuyển dụng (`positions`)
  - Ứng viên (`candidates`)
- Xuất dữ liệu Excel/PDF
- Quản lý hồ sơ ứng viên, upload CV
- Chuyển Ứng viên thành Nhân viên
- Trang công khai tuyển dụng `/careers`

## 3. Công nghệ và thư viện sử dụng
- PHP thuần, OOP
- MySQL
- Composer autoload PSR-4
- Thư viện:
  - `fakerphp/faker` để tạo dữ liệu giả
  - `phpmailer/phpmailer` để gửi email
  - `mpdf/mpdf` để xuất PDF
  - `phpoffice/phpspreadsheet` để xuất Excel

## 4. Kiến trúc hệ thống
- `public/index.php`: entry point, router, xử lý request
- `src/Core/Router.php`: router tùy chỉnh
- `src/Core/Controller.php`: controller base với quyền truy cập và xác thực
- `src/Core/Database.php`: kết nối PDO singleton
- `src/Core/functions.php`: helper chung, view, redirect, flash, CSRF, authorize
- `src/Controllers/`: xử lý nghiệp vụ theo module
- `src/Models/`: truy vấn dữ liệu với Prepared Statements
- `views/`: template giao diện
- `db/db.sql`: file schema và dữ liệu mẫu

## 5. Luồng xử lý chính
1. Người dùng truy cập `public/index.php`
2. Khởi tạo session và CSRF token
3. Autoload và load `config.php`
4. Khởi tạo `Router` và định nghĩa route
5. `Router::dispatch()` gọi controller tương ứng
6. Controller xử lý logic, truy vấn dữ liệu, render view
7. View được load qua `view()` trong `functions.php`

## 6. Cấu hình hệ thống
File cấu hình: `config.php`
- DB_HOST: `127.0.0.1`
- DB_PORT: `3306`
- DB_NAME: `hr_management`
- DB_USER: `root`
- DB_PASS: `123456`
- DB_CHARSET: `utf8mb4`
- BASE_PATH: thư mục gốc dự án
- BASE_URL: `http://localhost/hr_management/public`
- SMTP_HOST: `smtp.gmail.com`
- SMTP_USERNAME: `anhphandoan553@gmail.com`
- SMTP_PASSWORD: `bvhm wubf bbjf cnen`
- SMTP_PORT: `587`
- SMTP_SECURE: `tls`
- MAIL_FROM_NAME: `HR-Management System`

## 7. Hướng dẫn cài đặt và chạy
1. Mở XAMPP, khởi động Apache và MySQL.
2. Đặt mã nguồn vào thư mục `c:\xampp\htdocs\hr_management`.
3. Mở terminal tại thư mục dự án và chạy:
   - `composer install`
4. Tạo database MySQL tên `hr_management`.
5. Import file `db/db.sql` vào database.
6. Kiểm tra `config.php` và sửa thông tin database nếu cần.
7. Mở trình duyệt truy cập:
   - `http://localhost/hr_management/public`

## 8. Chức năng bảo mật và phân quyền
- Sử dụng session để quản lý đăng nhập
- CSRF token cho các form POST
- PDO và Prepared Statements chống SQL Injection
- Phân quyền cơ bản:
  - `admin` có thể truy cập toàn bộ chức năng
  - Người dùng khác cần đăng nhập để truy cập các trang nội bộ

## 9. Các điểm nổi bật
- Xây dựng hệ thống quản lý nhân sự mini với MVC tùy chỉnh
- Hỗ trợ export Excel và PDF
- Có cơ chế thông báo flash message
- Hỗ trợ trang tuyển dụng công khai cho ứng viên ngoài hệ thống
- Có chức năng upload CV và xử lý ứng viên
- Cấu trúc dễ mở rộng và bảo trì

## 10. Ghi chú
- Nếu muốn chạy trên môi trường khác, cập nhật `BASE_URL` và cấu hình database trong `config.php`.
- Nên thay mật khẩu MySQL và SMTP khi deploy môi trường thật.
- `public/` là thư mục gốc (document root) của ứng dụng.

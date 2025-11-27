<?php

use App\Core\Exceptions\UnauthorizedException;

/**
 * Hàm "render" một View.
 *
 * @param string $path Đường dẫn file view (ví dụ: 'users/index')
 * @param array $attributes Các biến muốn truyền vào view (ví dụ: ['users' => $users])
 */
function view($path, $attributes = [])
{
    // Biến mảng $attributes thành các biến riêng lẻ
    extract($attributes);

    // 🔥 SỬA LỖI Ở ĐÂY:
    // Biến $path (ví dụ: 'dashboard') thành đường dẫn đầy đủ
    // (ví dụ: 'C:/.../hr_management/views/dashboard.view.php')
    $path = BASE_PATH . 'views/' . $path . '.view.php';
    
    // Yêu cầu file layout, file layout sẽ tự "require" file $path
    require BASE_PATH . 'views/layouts/master.view.php';
}

/**
 * Hàm điều hướng người dùng sang một URI mới.
 *
 * @param string $path
 */
/**
 * Hàm điều hướng người dùng sang một URI mới.
 *
 * @param string $path (Ví dụ: '/login' hoặc '/users')
 */
function redirect($path)
{
    // 🔥 NÂNG CẤP:
    // Kiểm tra xem $path có phải là một URL đầy đủ hay không
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        // Nếu ĐÚNG (giống như $_SERVER['HTTP_REFERER']),
        // thì redirect thẳng, KHÔNG thêm BASE_URL
        header("Location: {$path}");
        exit();
    }
    if ($path[0] !== '/') {
        $path = '/' . $path;
    }
    
    // Kết quả sẽ là: http://localhost/hr_management/public/users
    header("Location: " . BASE_URL . $path);
    exit();
}

/**
 * Hàm "authorize" - Kiểm tra một điều kiện
 * Nếu sai, ném ra Exception "UnauthorizedException".
 *
 * @param bool $condition Điều kiện để kiểm tra (ví dụ: $_SESSION['user']['role'] === 'admin')
 * @param string $message Thông báo lỗi (mặc định 403)
 */
function authorize($condition, $message = 'This action is unauthorized.')
{
    if (! $condition) {
        // Ném exception 403
        throw new UnauthorizedException($message);
    }
}

/**
 * Hàm xử lý "Flash Message" (tin nhắn chớp)
 *
 * - Khi gọi với 2 tham số (key, message): Sẽ "set" một session flash
 * - Khi gọi với 1 tham số (key): Sẽ "get" (đọc) session flash đó và xóa nó ngay
 * - Khi gọi không tham số: Sẽ xóa tất cả flash cũ (dùng ở cuối layout)
 *
 * @param string|null $key
 * @param string|null $message
 * @return mixed|void
 */
function flash($key = null, $message = null)
{
    if ($key && $message) {
        // Set flash message
        $_SESSION['_flash'][$key] = $message;
        return;
    }

    if ($key) {
        // Get flash message
        $message = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]); // Xóa ngay sau khi đọc
        return $message;
    }
    
    // Nếu gọi flash() mà không có key, chúng ta chỉ cần xóa session
    // (Thực ra việc get đã tự xóa rồi, nhưng để đây cho rõ ràng)
    unset($_SESSION['_flash']);
}


/**
 * Hàm "e" (viết tắt của "escape")
 * Dùng để escape HTML, chống lỗi XSS khi hiển thị dữ liệu từ CSDL ra view.
 *
 * @param string $value
 * @return string
 */
function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function csrf_field()
{
    // In ra thẻ input
    echo '<input type="hidden" name="csrf_token" value="' . e($_SESSION['csrf_token'] ?? '') . '">';
}

/**
 * Kiểm tra CSRF token từ $_POST.
 * Sẽ ném ra Exception nếu token không hợp lệ.
 *
 * @throws \Exception
 */
function validate_csrf()
{
    // 1. Kiểm tra token có được gửi lên không
    if (!isset($_POST['csrf_token'])) {
        throw new \Exception('CSRF token not found.');
    }

    // 2. Kiểm tra token có khớp với session không
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Token không khớp!
        throw new \Exception('Invalid CSRF token.');
    }
}

function isAjaxRequest()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
           && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * Hàm "render" (vẽ) CHỈ MỘT PHẦN (partial) của trang
 * (Dùng để trả lời các request AJAX)
 *
 * @param string $path Đường dẫn file view (ví dụ: 'users/index')
 * @param array $attributes Các biến muốn truyền vào view
 */
function partial($path, $attributes = [])
{
    // Biến mảng $attributes thành các biến riêng lẻ
    extract($attributes);

    // Yêu cầu file "ruột"
    require BASE_PATH . 'views/' . $path . '.view.php';
}

if (!function_exists('e')) {
    function e($string)
    {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}
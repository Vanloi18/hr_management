<?php

namespace App\Core\Exceptions;

// Exception này sẽ được "ném" ra (throw)
// khi người dùng không có quyền truy cập (ví dụ: HR vào trang Admin).
class UnauthorizedException extends \Exception
{
    protected $message = 'You are not authorized to access this page.';
}
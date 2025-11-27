<?php

namespace App\Core\Exceptions;

// Exception này sẽ được "ném" ra (throw)
// khi Router không tìm thấy URI tương ứng.
class RouteNotFoundException extends \Exception
{
    protected $message = '404 Not Found';
}
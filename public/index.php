<?php 

    session_start();
    
    // 1. TẠO CSRF TOKEN (Bảo mật form)
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__ . '/../config.php';
    require BASE_PATH . 'src/Core/functions.php';

    $router = new \App\Core\Router();
    $router->get('/login', 'AuthController@index');
    $router->post('/login', 'AuthController@login');
    $router->get('/logout', 'AuthController@logout');

    // --- ROUTE CHO PROFILE (HỒ SƠ CÁ NHÂN) ---
    $router->get('/profile', 'ProfileController@index');        
    $router->post('/profile/update', 'ProfileController@update');

    // ----- Routes cho Quản lý Nhân viên (chỉ Admin) -----
    $router->get('/users', 'UserController@index');       
    $router->get('/users/create', 'UserController@create');
    $router->post('/users', 'UserController@store');      
    $router->get('/users/edit', 'UserController@edit');   
    $router->post('/users/update', 'UserController@update');
    $router->post('/users/delete', 'UserController@destroy');

    // ----- Routes cho Quản lý Phòng ban (Admin & HR) -----
    $router->get('/departments', 'DepartmentController@index');
    $router->get('/departments/create', 'DepartmentController@create');
    $router->post('/departments', 'DepartmentController@store');
    $router->get('/departments/edit', 'DepartmentController@edit');
    $router->post('/departments/update', 'DepartmentController@update');
    $router->post('/departments/delete', 'DepartmentController@destroy');
    $router->get('/departments/employees', 'DepartmentController@apiGetEmployees');
    
    // ----- Routes cho Quản lý Nhân viên (Admin & HR) -----
    $router->get('/employees', 'EmployeeController@index');
    $router->get('/employees/create', 'EmployeeController@create');
    $router->post('/employees', 'EmployeeController@store');
    $router->get('/employees/edit', 'EmployeeController@edit');
    $router->post('/employees/update', 'EmployeeController@update');
    $router->post('/employees/delete', 'EmployeeController@destroy');

    // ----- Routes cho Quản lý Nhà tuyển dụng (Admin & HR) -----
    $router->get('/recruiters', 'RecruiterController@index');
    $router->get('/recruiters/create', 'RecruiterController@create');
    $router->post('/recruiters', 'RecruiterController@store');
    $router->get('/recruiters/edit', 'RecruiterController@edit');
    $router->post('/recruiters/update', 'RecruiterController@update');
    $router->post('/recruiters/delete', 'RecruiterController@destroy');

    // ----- Routes cho Quản lý Lĩnh vực (Admin & HR) -----
    $router->get('/fields', 'FieldController@index');
    $router->get('/fields/create', 'FieldController@create');
    $router->post('/fields', 'FieldController@store');
    $router->get('/fields/edit', 'FieldController@edit');
    $router->post('/fields/update', 'FieldController@update');
    $router->post('/fields/delete', 'FieldController@destroy');

    // ----- Routes cho Quản lý Vị trí (Admin & HR) -----
    $router->get('/positions', 'PositionController@index');
    $router->get('/positions/create', 'PositionController@create');
    $router->post('/positions', 'PositionController@store');
    $router->get('/positions/edit', 'PositionController@edit');
    $router->post('/positions/update', 'PositionController@update');
    $router->post('/positions/delete', 'PositionController@destroy');

    // ----- Routes cho Quản lý Ứng viên (Admin & HR) -----
    $router->get('/candidates', 'CandidateController@index');
    $router->get('/candidates/create', 'CandidateController@create');
    $router->post('/candidates', 'CandidateController@store');
    $router->get('/candidates/edit', 'CandidateController@edit');
    $router->post('/candidates/update', 'CandidateController@update');
    $router->post('/candidates/delete', 'CandidateController@destroy');

    // ----- Routes cho Thống kê (Admin & HR) -----
    $router->get('/statistics', 'StatisticsController@index');

    // ----- Route mặc định (Trang chủ / Dashboard) -----
    $router->get('/', 'DashboardController@index');
    
    // 6. Lấy URI và Method từ request
    $uri = '/' . ($_GET['uri'] ?? '');


    if (strlen($uri) > 1) {
        $uri = rtrim($uri, '/');
    }
    $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

    if ($method === 'POST') {
        try {
            validate_csrf();
        } catch (\Exception $e) {
            // Nếu token lỗi, dừng ứng dụng ngay lập tức
            http_response_code(403); // 403 Forbidden
            flash('error', 'Lỗi bảo mật: ' . $e->getMessage() . ' Vui lòng thử lại.');
            // Chuyển hướng về trang trước đó (hoặc trang chủ)
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

    // 7. Điều hướng (Dispatch)
    // Router sẽ tìm Controller tương ứng với $uri và $method
    try {
        $router->dispatch($uri, $method);
    } catch (\App\Core\Exceptions\RouteNotFoundException $e) {
        // Xử lý khi không tìm thấy route (404)
        // Em có thể tạo một view 404 đẹp hơn
        http_response_code(404);
        echo "404 - Page Not Found";
    } catch (\App\Core\Exceptions\UnauthorizedException $e) {
        http_response_code(403);
        flash('error', $e->getMessage()); // Hiển thị lỗi
        redirect('/login'); // Đưa về trang login
    } catch (\Exception $e) {
        http_response_code(500);
        echo "<h1>500 - Lỗi Server</h1><p>" . $e->getMessage() . "</p>";
    }
?>
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
    $uri = '/' . ($_GET['uri'] ?? '');
    if (strlen($uri) > 1) {
        $uri = rtrim($uri, '/');
    }
    if (strpos($uri, '/careers') !== false) {

    require_once BASE_PATH . 'src/Controllers/PublicController.php';
    $controller = new \App\Controllers\PublicController();
    if (strpos($uri, '/careers/apply') !== false) {
        $controller->apply();
    } 
    elseif (strpos($uri, '/careers/detail') !== false) {
        $controller->detail();
    }
    else {
        $controller->index();
    }
    
    exit;
}

    $router->get('/login', 'AuthController@index');
    $router->post('/login', 'AuthController@login');
    $router->get('/logout', 'AuthController@logout');

    // --- ROUTE CHO PROFILE (HỒ SƠ CÁ NHÂN) ---
    $router->get('/profile', 'ProfileController@index');        
    $router->post('/profile/update', 'ProfileController@update');
    $router->post('/profile/update-info', 'ProfileController@updateInfo'); 
    $router->post('/profile/change-password', 'ProfileController@changePassword'); 

    // ----- Routes cho Quản lý Nhân viên (chỉ Admin) -----
    $router->get('/users', 'UserController@index');       
    $router->get('/users/create', 'UserController@create');
    $router->post('/users', 'UserController@store');      
    $router->get('/users/edit', 'UserController@edit');   
    $router->post('/users/update', 'UserController@update');
    $router->post('/users/delete', 'UserController@destroy');
    $router->get('/users/export-excel', 'UserController@exportExcel');
    $router->get('/users/export-pdf', 'UserController@exportPDF');

    // ----- Routes cho Quản lý Phòng ban (Admin & HR) -----
    $router->get('/departments', 'DepartmentController@index');
    $router->get('/departments/create', 'DepartmentController@create');
    $router->post('/departments', 'DepartmentController@store');
    $router->get('/departments/edit', 'DepartmentController@edit');
    $router->post('/departments/update', 'DepartmentController@update');
    $router->post('/departments/delete', 'DepartmentController@destroy');
    $router->get('/departments/employees', 'DepartmentController@apiGetEmployees');
    $router->get('/departments/export-excel', 'DepartmentController@exportExcel');
    $router->get('/departments/export-pdf', 'DepartmentController@exportPDF');
    $router->get('/departments/api/employees', 'DepartmentController@apiGetEmployees'); // API cho Modal
    
    // ----- Routes cho Quản lý Nhân viên (Admin & HR) -----
    $router->get('/employees', 'EmployeeController@index');
    $router->get('/employees/create', 'EmployeeController@create');
    $router->post('/employees', 'EmployeeController@store');
    $router->get('/employees/edit', 'EmployeeController@edit');
    $router->post('/employees/update', 'EmployeeController@update');
    $router->post('/employees/delete', 'EmployeeController@destroy');
    $router->get('/employees/export-excel', 'EmployeeController@exportExcel');
    $router->get('/employees/export-pdf', 'EmployeeController@exportPDF');

    // ----- Routes cho Quản lý Nhà tuyển dụng (Admin & HR) -----
    $router->get('/recruiters', 'RecruiterController@index');
    $router->get('/recruiters/create', 'RecruiterController@create');
    $router->post('/recruiters', 'RecruiterController@store');
    $router->get('/recruiters/edit', 'RecruiterController@edit');
    $router->post('/recruiters/update', 'RecruiterController@update');
    $router->post('/recruiters/delete', 'RecruiterController@destroy');
    $router->get('/recruiters/export-excel', 'RecruiterController@exportExcel');

    // ----- Routes cho Quản lý Lĩnh vực (Admin & HR) -----
    $router->get('/fields', 'FieldController@index');
    $router->get('/fields/create', 'FieldController@create');
    $router->post('/fields', 'FieldController@store');
    $router->get('/fields/edit', 'FieldController@edit');
    $router->post('/fields/update', 'FieldController@update');
    $router->post('/fields/delete', 'FieldController@destroy');
    $router->get('/fields/export-excel', 'FieldController@exportExcel');
    $router->get('/fields/export-pdf', 'FieldController@exportPDF');

    // ----- Routes cho Quản lý Vị trí (Admin & HR) -----
    $router->get('/positions', 'PositionController@index');
    $router->get('/positions/create', 'PositionController@create');
    $router->post('/positions', 'PositionController@store');
    $router->get('/positions/edit', 'PositionController@edit');
    $router->post('/positions/update', 'PositionController@update');
    $router->post('/positions/delete', 'PositionController@destroy');
    $router->get('/positions/export-excel', 'PositionController@exportExcel');
    $router->get('/positions/export-pdf', 'PositionController@exportPDF');

    // ----- Routes cho Quản lý Ứng viên (Admin & HR) -----
    $router->get('/candidates', 'CandidateController@index');
    $router->get('/candidates/create', 'CandidateController@create');
    $router->post('/candidates', 'CandidateController@store');
    $router->get('/candidates/edit', 'CandidateController@edit');
    $router->post('/candidates/update', 'CandidateController@update');
    $router->post('/candidates/delete', 'CandidateController@destroy');
    $router->get('/candidates/export-excel', 'CandidateController@exportExcel');
    $router->get('/candidates/export-pdf', 'CandidateController@exportPDF');

    // ----- Routes cho Thống kê (Admin & HR) -----
    $router->get('/statistics', 'StatisticsController@index');
    $router->get('/statistics/export-excel', 'StatisticsController@exportExcel');
    $router->post('/statistics/export-pdf', 'StatisticsController@exportPDF');

    // Routes cho Cài đặt (Settings)
    $router->get('/settings', 'SettingController@index');
    $router->post('/settings/update', 'SettingController@update');
    $router->post('/settings/update', 'SettingController@update');

    // ----- Route mặc định (Trang chủ / Dashboard) -----
    $router->get('/', 'DashboardController@index');



    if (strlen($uri) > 1) {
        $uri = rtrim($uri, '/');
    }
    $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

    if ($method === 'POST') {
        try {
            validate_csrf();
        } catch (\Exception $e) {
            http_response_code(403); 
            flash('error', 'Lỗi bảo mật: ' . $e->getMessage() . ' Vui lòng thử lại.');
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

    try {
        $router->dispatch($uri, $method);
    } catch (\App\Core\Exceptions\RouteNotFoundException $e) {
        http_response_code(404);
        echo "404 - Page Not Found";
    } catch (\App\Core\Exceptions\UnauthorizedException $e) {
        http_response_code(403);
        flash('error', $e->getMessage());
        redirect('/login'); 
    } catch (\Exception $e) {
        http_response_code(500);
        echo "<h1>500 - Lỗi Server</h1><p>" . $e->getMessage() . "</p>";
    }
?>
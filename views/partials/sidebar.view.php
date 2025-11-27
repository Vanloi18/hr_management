<?php

// Lấy đường dẫn URI hiện tại để xác định trang "active"
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Logic xác định "nhóm" module nào đang active
$isAdminModule = (
    strpos($currentPath, '/users') !== false ||
    strpos($currentPath, '/recruiters') !== false ||
    strpos($currentPath, '/fields') !== false
);
$isHrmModule = (
    strpos($currentPath, '/departments') !== false ||
    strpos($currentPath, '/employees') !== false
);
$isRecruitModule = (
    strpos($currentPath, '/positions') !== false ||
    strpos($currentPath, '/candidates') !== false
);
?>
<aside class="sidebar bg-dark text-white">
    <!-- Logo -->
    <a class="sidebar-brand ajax-link" href="#" 
       data-url="<?php echo BASE_URL; ?>/" 
       data-title="HR-Management">
        <i class="bi bi-person-workspace"></i> HR-Management
    </a>
    <hr>

    <ul class="nav nav-pills flex-column mb-auto">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link text-white ajax-link <?php echo ($currentPath === BASE_URL . '/') ? 'active' : ''; ?>"
               href="#"
               data-url="<?php echo BASE_URL; ?>/"
               data-title="Bảng điều khiển">
               <i class="bi bi-grid-fill"></i> Bảng điều khiển
            </a>
        </li>

        <!-- ADMIN MODULE -->
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link text-white <?php echo $isAdminModule ? 'active' : ''; ?>"
               href="#admin-menu" data-bs-toggle="collapse" role="button">
                <i class="bi bi-gear-fill"></i> Quản trị Hệ thống
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <div class="collapse <?php echo $isAdminModule ? 'show' : ''; ?>" id="admin-menu">
                <ul class="nav flex-column ms-3">

                    <li>
                        <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/users') !== false ? 'active' : ''; ?>"
                           href="#"
                           data-url="<?php echo BASE_URL; ?>/users"
                           data-title="Quản lý Users">
                           <i class="bi bi-people-fill"></i> Quản lý Users
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/recruiters') !== false ? 'active' : ''; ?>"
                           href="#"
                           data-url="<?php echo BASE_URL; ?>/recruiters"
                           data-title="Quản lý Nhà tuyển dụng">
                           <i class="bi bi-building"></i> Nhà tuyển dụng
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/fields') !== false ? 'active' : ''; ?>"
                           href="#"
                           data-url="<?php echo BASE_URL; ?>/fields"
                           data-title="Quản lý Lĩnh vực">
                           <i class="bi bi-tags-fill"></i> Lĩnh vực
                        </a>
                    </li>

                </ul>
            </div>
        </li>
        <?php endif; ?>

        <!-- BUSINESS MODULE -->
        <li class="nav-item">
            <a class="nav-link text-white <?php echo ($isHrmModule || $isRecruitModule) ? 'active' : ''; ?>"
               href="#business-menu" data-bs-toggle="collapse" role="button">
                <i class="bi bi-kanban-fill"></i> Nghiệp vụ
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <div class="collapse <?php echo ($isHrmModule || $isRecruitModule) ? 'show' : ''; ?>" id="business-menu">
                <ul class="nav flex-column ms-3">

                    <!-- Nhân sự -->
                    <li><h6 class="nav-link-header text-muted">Nhân sự</h6></li>

                    <li>
                        <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/departments') !== false ? 'active' : ''; ?>"
                           href="#"
                           data-url="<?php echo BASE_URL; ?>/departments"
                           data-title="Quản lý Phòng ban">
                           <i class="bi bi-briefcase-fill"></i> Phòng ban
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/employees') !== false ? 'active' : ''; ?>"
                           href="#"
                           data-url="<?php echo BASE_URL; ?>/employees"
                           data-title="Quản lý Nhân viên">
                           <i class="bi bi-person-vcard-fill"></i> Nhân viên
                        </a>
                    </li>

                    <!-- Tuyển dụng -->
                    <li><h6 class="nav-link-header text-muted">Tuyển dụng</h6></li>

                    <li>
                        <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/positions') !== false ? 'active' : ''; ?>"
                           href="#"
                           data-url="<?php echo BASE_URL; ?>/positions"
                           data-title="Quản lý Vị trí">
                           <i class="bi bi-megaphone-fill"></i> Vị trí
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/candidates') !== false ? 'active' : ''; ?>"
                           href="#"
                           data-url="<?php echo BASE_URL; ?>/candidates"
                           data-title="Quản lý Ứng viên">
                           <i class="bi bi-file-earmark-person-fill"></i> Ứng viên
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        <!-- Statistics -->
        <li class="nav-item">
            <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/statistics') !== false ? 'active' : ''; ?>"
               href="#"
               data-url="<?php echo BASE_URL; ?>/statistics"
               data-title="Thống kê">
               <i class="bi bi-pie-chart-fill"></i> Thống kê
            </a>
        </li>

    </ul>

    <hr>
</aside>

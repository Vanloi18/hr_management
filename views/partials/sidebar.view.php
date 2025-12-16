<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$isAdminModule = (
    strpos($currentPath, '/users') !== false ||
    strpos($currentPath, '/recruiters') !== false ||
    strpos($currentPath, '/fields') !== false
);
?>

<aside class="sidebar d-flex flex-column" style="min-height: 100vh;">
    
    <div class="p-3">
    <?php 
        $brandName = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') 
                     ? 'Admin Workspace' 
                     : 'HR-System'; 
    ?>

    <a class="sidebar-brand ajax-link d-flex align-items-center justify-content-center" href="#" 
       data-url="<?php echo BASE_URL; ?>/" 
       data-title="<?php echo $brandName; ?>"> <i class="bi bi-person-workspace text-primary me-2"></i> 
        
        <span class="font-weight-bold"><?php echo $brandName; ?></span>
    </a>
</div>
    
    <div class="flex-grow-1 px-2 pb-3">
        <ul class="nav flex-column gap-1">

            <li class="nav-item">
                <a class="nav-link ajax-link <?php echo ($currentPath === BASE_URL . '/' || $currentPath === BASE_URL . '/dashboard') ? 'active' : ''; ?>"
                   href="#"
                   data-url="<?php echo BASE_URL; ?>/"
                   data-title="Bảng điều khiển">
                   <i class="bi bi-grid-fill"></i> Bảng điều khiển
                </a>
            </li>

            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <li class="nav-item mt-3">
                <div class="nav-link-header">Quản trị hệ thống</div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center <?php echo $isAdminModule ? 'active' : ''; ?>"
                   href="#admin-menu" data-bs-toggle="collapse" role="button" aria-expanded="<?php echo $isAdminModule ? 'true' : 'false'; ?>">
                    <span><i class="bi bi-gear-fill"></i> Cấu hình</span>
                    <i class="bi bi-chevron-down small ms-auto" style="font-size: 0.8rem;"></i>
                </a>

                <div class="collapse <?php echo $isAdminModule ? 'show' : ''; ?>" id="admin-menu">
                    <ul class="nav flex-column ms-2 mt-1 border-start border-secondary border-opacity-10 ps-1">
                        <li>
                            <a class="nav-link ajax-link <?php echo strpos($currentPath, '/users') !== false ? 'active' : ''; ?>"
                               href="#" data-url="<?php echo BASE_URL; ?>/users" data-title="Quản lý Users">
                               Tài khoản
                            </a>
                        </li>
                        <li>
                            <a class="nav-link ajax-link <?php echo strpos($currentPath, '/recruiters') !== false ? 'active' : ''; ?>"
                               href="#" data-url="<?php echo BASE_URL; ?>/recruiters" data-title="Nhà tuyển dụng">
                               Nhà tuyển dụng
                            </a>
                        </li>
                        <li>
                            <a class="nav-link ajax-link <?php echo strpos($currentPath, '/fields') !== false ? 'active' : ''; ?>"
                               href="#" data-url="<?php echo BASE_URL; ?>/fields" data-title="Lĩnh vực">
                               Lĩnh vực hoạt động
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <li class="nav-item mt-3">
                <div class="nav-link-header">Nghiệp vụ</div>
            </li>

            <li class="nav-item">
                <a class="nav-link ajax-link <?php echo strpos($currentPath, '/departments') !== false ? 'active' : ''; ?>"
                   href="#" data-url="<?php echo BASE_URL; ?>/departments" data-title="Phòng ban">
                   <i class="bi bi-briefcase-fill"></i> Phòng ban
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link ajax-link <?php echo strpos($currentPath, '/employees') !== false ? 'active' : ''; ?>"
                   href="#" data-url="<?php echo BASE_URL; ?>/employees" data-title="Nhân viên">
                   <i class="bi bi-person-vcard-fill"></i> Nhân viên
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link ajax-link <?php echo strpos($currentPath, '/positions') !== false ? 'active' : ''; ?>"
                   href="#" data-url="<?php echo BASE_URL; ?>/positions" data-title="Vị trí tuyển dụng">
                   <i class="bi bi-megaphone-fill"></i> Tin tuyển dụng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link ajax-link <?php echo strpos($currentPath, '/candidates') !== false ? 'active' : ''; ?>"
                   href="#" data-url="<?php echo BASE_URL; ?>/candidates" data-title="Hồ sơ ứng viên">
                   <i class="bi bi-file-earmark-person-fill"></i> Hồ sơ ứng viên
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <div class="nav-link-header">Báo cáo & Tiện ích</div>
            </li>

            <li class="nav-item">
                <a class="nav-link ajax-link <?php echo strpos($currentPath, '/statistics') !== false ? 'active' : ''; ?>"
                   href="#" data-url="<?php echo BASE_URL; ?>/statistics" data-title="Báo cáo thống kê">
                   <i class="bi bi-pie-chart-fill"></i> Thống kê
                </a>
            </li>
        </ul>
    </div>
</aside>
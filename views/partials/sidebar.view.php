<?php
// Lấy đường dẫn URI hiện tại
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Logic xác định module active
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

<aside class="sidebar bg-dark text-white d-flex flex-column" style="min-height: 100vh;">
    
    <div class="p-3">
        <a class="sidebar-brand ajax-link text-white text-decoration-none d-flex align-items-center fw-bold fs-5" href="#" 
           data-url="<?php echo BASE_URL; ?>/" 
           data-title="HR-Management">
            <i class="bi bi-person-workspace text-primary me-2"></i> HR-System
        </a>
    </div>
    
    <hr class="mx-3 my-0 opacity-25">

    <div class="flex-grow-1 p-3">
        <ul class="nav nav-pills flex-column mb-auto gap-1">

            <li class="nav-item">
                <a class="nav-link text-white ajax-link <?php echo ($currentPath === BASE_URL . '/') ? 'active shadow-sm' : ''; ?>"
                   href="#"
                   data-url="<?php echo BASE_URL; ?>/"
                   data-title="Bảng điều khiển">
                   <i class="bi bi-grid-fill me-2"></i> Bảng điều khiển
                </a>
            </li>

            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <li class="nav-item mt-3">
                <small class="text-uppercase text-muted fw-bold ps-3" style="font-size: 0.7rem;">Quản trị</small>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white d-flex justify-content-between align-items-center <?php echo $isAdminModule ? 'bg-secondary bg-opacity-25' : ''; ?>"
                   href="#admin-menu" data-bs-toggle="collapse" role="button" aria-expanded="<?php echo $isAdminModule ? 'true' : 'false'; ?>">
                    <span><i class="bi bi-gear-fill me-2"></i> Hệ thống</span>
                    <i class="bi bi-chevron-down small transition-icon"></i>
                </a>

                <div class="collapse <?php echo $isAdminModule ? 'show' : ''; ?>" id="admin-menu">
                    <ul class="nav flex-column ms-3 mt-1 border-start border-secondary border-opacity-25 ps-2">
                        <li>
                            <a class="nav-link text-white-50 ajax-link py-1 <?php echo strpos($currentPath, '/users') !== false ? 'text-white' : ''; ?>"
                               href="#" data-url="<?php echo BASE_URL; ?>/users" data-title="Quản lý Users">
                               Users
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-white-50 ajax-link py-1 <?php echo strpos($currentPath, '/recruiters') !== false ? 'text-white' : ''; ?>"
                               href="#" data-url="<?php echo BASE_URL; ?>/recruiters" data-title="Nhà tuyển dụng">
                               Nhà tuyển dụng
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-white-50 ajax-link py-1 <?php echo strpos($currentPath, '/fields') !== false ? 'text-white' : ''; ?>"
                               href="#" data-url="<?php echo BASE_URL; ?>/fields" data-title="Lĩnh vực">
                               Lĩnh vực
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <li class="nav-item mt-3">
                <small class="text-uppercase text-muted fw-bold ps-3" style="font-size: 0.7rem;">Nghiệp vụ</small>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/departments') !== false ? 'active shadow-sm' : ''; ?>"
                   href="#" data-url="<?php echo BASE_URL; ?>/departments" data-title="Phòng ban">
                   <i class="bi bi-briefcase-fill me-2"></i> Phòng ban
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/employees') !== false ? 'active shadow-sm' : ''; ?>"
                   href="#" data-url="<?php echo BASE_URL; ?>/employees" data-title="Nhân viên">
                   <i class="bi bi-person-vcard-fill me-2"></i> Nhân viên
                </a>
            </li>

            <li class="nav-item mt-1">
                <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/positions') !== false ? 'active shadow-sm' : ''; ?>"
                   href="#" data-url="<?php echo BASE_URL; ?>/positions" data-title="Vị trí tuyển dụng">
                   <i class="bi bi-megaphone-fill me-2"></i> Tin tuyển dụng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/candidates') !== false ? 'active shadow-sm' : ''; ?>"
                   href="#" data-url="<?php echo BASE_URL; ?>/candidates" data-title="Hồ sơ ứng viên">
                   <i class="bi bi-file-earmark-person-fill me-2"></i> Hồ sơ ứng viên
                </a>
            </li>
            
            <li class="nav-item mt-3 pt-2 border-top border-secondary border-opacity-25">
                <a class="nav-link text-white ajax-link <?php echo strpos($currentPath, '/statistics') !== false ? 'active shadow-sm' : ''; ?>"
                   href="#" data-url="<?php echo BASE_URL; ?>/statistics" data-title="Báo cáo thống kê">
                   <i class="bi bi-pie-chart-fill me-2"></i> Thống kê
                </a>
            </li>

        </ul>
    </div>

</aside>

<style>
    /* CSS Tinh chỉnh nhẹ nhàng */
    .sidebar .nav-link {
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .sidebar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(3px); /* Hiệu ứng trượt nhẹ khi hover */
    }
    .sidebar .nav-link.active {
        background-color: #0d6efd !important; /* Màu xanh chủ đạo */
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    /* Xoay icon mũi tên khi mở menu con */
    .collapse.show ~ a .transition-icon {
        transform: rotate(180deg);
    }
    .transition-icon {
        transition: transform 0.2s;
    }
</style>
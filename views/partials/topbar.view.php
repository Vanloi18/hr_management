<style>
    /* Topbar Styling */
    .topbar {
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        padding: 0.75rem 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        height: 70px;
        width: 100%;
        z-index: 99;
    }

    /* Avatar Image & Circle */
    .avatar-img {
        width: 40px;
        height: 40px;
        object-fit: cover; /* Đảm bảo ảnh không bị méo */
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.1rem;
        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.3);
        border: 2px solid #fff;
    }

    /* Page Title */
    .page-title { font-size: 1.2rem; font-weight: 700; color: #111827; margin: 0; line-height: 1.2; }
    
    /* Notification */
    .notification-btn {
        width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        color: #6b7280; transition: all 0.2s; position: relative; background-color: #f9fafb;
    }
    .notification-btn:hover { background-color: #eff6ff; color: #2563eb; }
    .notification-badge {
        position: absolute; top: 10px; right: 10px; width: 8px; height: 8px;
        background-color: #ef4444; border-radius: 50%; border: 2px solid #fff;
    }

    /* Dropdown */
    .custom-dropdown-menu {
        border: none; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border-radius: 12px; padding: 0.5rem; margin-top: 10px !important; min-width: 240px;
    }
    .custom-dropdown-item {
        padding: 0.75rem 1rem; border-radius: 8px; font-weight: 500; color: #4b5563; display: flex; align-items: center; gap: 12px;
    }
    .custom-dropdown-item:hover { background-color: #f3f4f6; color: #111827; }
    .custom-dropdown-item.text-danger:hover { background-color: #fef2f2; color: #dc2626; }
</style>

<nav class="topbar navbar navbar-expand navbar-light sticky-top">
    <div class="container-fluid px-0">
        
        <div class="d-flex align-items-center">
            <button class="btn btn-light d-lg-none me-3 border-0 shadow-sm rounded-circle" type="button" style="width: 40px; height: 40px;">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <h1 class="page-title"><?php echo $title ?? 'Dashboard'; ?></h1>
                <div class="d-flex align-items-center text-secondary small mt-1">
                    <i class="bi bi-calendar-event me-1"></i>
                    <span><?php echo date('l, d F Y'); ?></span>
                </div>
            </div>
        </div>

        <ul class="navbar-nav ms-auto align-items-center gap-3">
            
            <li class="nav-item">
                <a class="notification-btn" href="#" role="button" data-bs-toggle="tooltip" title="Thông báo">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge"></span>
                </a>
            </li>

            <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link p-0 d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="text-end d-none d-md-block">
                            <div class="fw-bold text-dark small mb-0"><?php echo e($_SESSION['user']['full_name']); ?></div>
                            <div class="text-muted" style="font-size: 0.75rem;"><?php echo ucfirst($_SESSION['user']['role'] ?? 'Staff'); ?></div>
                        </div>
                        
                        <?php 
                            $avatarFile = $_SESSION['user']['avatar'] ?? '';
                            $avatarPath = 'uploads/avatars/' . $avatarFile;
                        ?>
                        
                        <?php if (!empty($avatarFile) && file_exists($avatarPath)): ?>
                            <img src="<?php echo BASE_URL . '/' . $avatarPath; ?>" alt="Avatar" class="avatar-img">
                        <?php else: ?>
                            <div class="avatar-circle">
                                <?php echo strtoupper(substr($_SESSION['user']['full_name'] ?? 'U', 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu animate__animated animate__fadeIn">
                        <li><h6 class="dropdown-header text-uppercase small fw-bold text-muted mb-2 ps-3">Tài khoản</h6></li>
                        <li>
                            <a class="dropdown-item custom-dropdown-item" href="<?php echo BASE_URL; ?>/profile">
                                <i class="bi bi-person-gear fs-5 text-primary"></i> Hồ sơ cá nhân
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item custom-dropdown-item" href="<?php echo BASE_URL; ?>/settings">
                                <i class="bi bi-gear fs-5 text-secondary"></i> Cài đặt
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-2"></li>
                        <li>
                            <a class="dropdown-item custom-dropdown-item text-danger" href="<?php echo BASE_URL; ?>/logout">
                                <i class="bi bi-box-arrow-right fs-5"></i> Đăng xuất
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>

    </div> 
</nav>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
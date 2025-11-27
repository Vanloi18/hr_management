<style>
    .topbar {
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .topbar .nav-link {
        font-weight: 500;
        color: #374151 !important;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
    }
    
    .topbar .nav-link:hover {
        background-color: #f3f4f6;
        color: #111827 !important;
    }
    
    .topbar .nav-link i {
        font-size: 1.25rem;
    }
    
    .topbar .dropdown-menu {
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 0.5rem;
        padding: 0.5rem;
        min-width: 200px;
        margin-top: 0.5rem;
    }
    
    .topbar .dropdown-item {
        padding: 0.625rem 1rem;
        border-radius: 0.375rem;
        color: #374151;
        font-weight: 500;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
    }
    
    .topbar .dropdown-item:hover {
        background-color: #fee2e2;
        color: #dc2626;
    }
    
    .topbar .dropdown-item i {
        font-size: 1.125rem;
    }
    
    @media (max-width: 768px) {
        .topbar {
            padding: 0.75rem 0;
        }
        
        .topbar .nav-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.9rem;
        }
        
        .topbar .nav-link i {
            font-size: 1.125rem;
        }
    }
</style>

<nav class="topbar navbar navbar-expand navbar-light">
    
    <div class="container-fluid">

        <ul class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-2"></i>
                        <span>Chào, <?php echo e($_SESSION['user']['full_name']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>/logout">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                <span>Đăng xuất</span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>

    </div> 
</nav>
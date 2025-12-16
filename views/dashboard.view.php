<?php 
// Set tiêu đề trang
$title = "Bảng điều khiển";

// Controller cần truyền biến $full_name (tên người dùng)
$full_name = $full_name ?? $_SESSION['user']['full_name'] ?? 'Admin';
?>

<div class="container-fluid py-4">
    
    <div class="welcome-banner card border-0 shadow-lg rounded-4 overflow-hidden mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4 p-md-5 position-relative">
            <div class="welcome-bg position-absolute" style="top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div class="row align-items-center position-relative">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <div class="welcome-icon bg-white bg-opacity-25 rounded-circle p-3 me-3">
                            <i class="bi bi-emoji-smile-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h2 class="text-white fw-bold mb-1">
                                Chào mừng trở lại, <?php echo e($full_name); ?>!
                            </h2>
                            <p class="text-white-50 mb-0">
                                <i class="bi bi-calendar3 me-2"></i>
                                <?php echo date('l, d F Y'); ?>
                            </p>
                        </div>
                    </div>
                    <p class="text-white mb-0 fs-5">
                        <i class="bi bi-lightning-charge-fill me-2"></i>
                        Chọn một tác vụ nhanh bên dưới để bắt đầu công việc của bạn.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <div class="welcome-stats">
                        <div class="d-inline-block bg-white bg-opacity-25 rounded-pill px-4 py-2 me-2 mb-2">
                            <i class="bi bi-clock text-white me-2"></i>
                            <span class="text-white fw-semibold" id="current-time"><?php echo date('H:i:s'); ?></span>
                        </div>
                        <div class="d-inline-block bg-white bg-opacity-25 rounded-pill px-4 py-2 mb-2">
                            <i class="bi bi-shield-check text-white me-2"></i>
                            <span class="text-white fw-semibold text-capitalize"><?php echo $_SESSION['user']['role'] ?? 'N/A'; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-header mb-4">
        <h4 class="fw-bold mb-2">
            <i class="bi bi-lightning-fill text-warning me-2"></i>Lối tắt nhanh
        </h4>
        <p class="text-muted mb-0">Truy cập nhanh các chức năng thường dùng</p>
    </div>

    <div class="row g-4">

        <div class="col-lg-3 col-md-6">
            <div class="quick-card card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-gradient position-absolute w-100 h-100" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(102, 126, 234, 0.05) 100%);"></div>
                <div class="card-body d-flex flex-column align-items-center text-center p-4 position-relative">
                    <div class="icon-wrapper mb-3">
                        <div class="icon-circle" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-person-plus-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Thêm Nhân viên</h5>
                    <p class="card-text text-muted small mb-4 flex-grow-1">
                        Thêm hồ sơ nhân viên mới vào hệ thống quản lý.
                    </p>
                    <a href="<?php echo BASE_URL; ?>/employees/create" 
                       class="btn btn-primary rounded-pill px-4 shadow-sm w-100"
                       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="bi bi-arrow-right-circle-fill me-2"></i> Bắt đầu
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="quick-card card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-gradient position-absolute w-100 h-100" style="background: linear-gradient(135deg, rgba(17, 153, 142, 0.1) 0%, rgba(56, 239, 125, 0.05) 100%);"></div>
                <div class="card-body d-flex flex-column align-items-center text-center p-4 position-relative">
                    <div class="icon-wrapper mb-3">
                        <div class="icon-circle" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <i class="bi bi-file-earmark-person-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Thêm Ứng viên</h5>
                    <p class="card-text text-muted small mb-4 flex-grow-1">
                        Thêm hồ sơ ứng viên mới cho tin tuyển dụng.
                    </p>
                    <a href="<?php echo BASE_URL; ?>/candidates/create" 
                       class="btn btn-success rounded-pill px-4 shadow-sm w-100"
                       style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none;">
                        <i class="bi bi-arrow-right-circle-fill me-2"></i> Bắt đầu
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="quick-card card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-gradient position-absolute w-100 h-100" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.05) 100%);"></div>
                <div class="card-body d-flex flex-column align-items-center text-center p-4 position-relative">
                    <div class="icon-wrapper mb-3">
                        <div class="icon-circle" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="bi bi-pie-chart-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Xem Báo cáo</h5>
                    <p class="card-text text-muted small mb-4 flex-grow-1">
                        Xem các biểu đồ và thống kê chi tiết.
                    </p>
                    <a href="<?php echo BASE_URL; ?>/statistics" 
                       class="btn btn-info rounded-pill px-4 shadow-sm w-100"
                       style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; color: white;">
                        <i class="bi bi-arrow-right-circle-fill me-2"></i> Bắt đầu
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="quick-card card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-gradient position-absolute w-100 h-100" style="background: linear-gradient(135deg, rgba(250, 112, 154, 0.1) 0%, rgba(254, 225, 64, 0.05) 100%);"></div>
                <div class="card-body d-flex flex-column align-items-center text-center p-4 position-relative">
                    <div class="icon-wrapper mb-3">
                        <div class="icon-circle" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="bi bi-briefcase-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Quản lý Phòng ban</h5>
                    <p class="card-text text-muted small mb-4 flex-grow-1">
                        Thêm, sửa, xóa các phòng ban công ty.
                    </p>
                    <a href="<?php echo BASE_URL; ?>/departments" 
                       class="btn rounded-pill px-4 shadow-sm w-100"
                       style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border: none; color: #333;">
                        <i class="bi bi-arrow-right-circle-fill me-2"></i> Bắt đầu
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card links-card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 section-title-text">
                        <i class="bi bi-link-45deg text-primary me-2"></i>Liên kết nhanh khác:
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo BASE_URL; ?>/positions" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-megaphone me-1"></i>Quản lý Tin tuyển dụng
                        </a>
                        <a href="<?php echo BASE_URL; ?>/recruiters" class="btn btn-outline-success btn-sm rounded-pill px-3">
                            <i class="bi bi-building me-1"></i>Nhà tuyển dụng
                        </a>
                        <a href="<?php echo BASE_URL; ?>/fields" class="btn btn-outline-info btn-sm rounded-pill px-3">
                            <i class="bi bi-tags me-1"></i>Lĩnh vực
                        </a>
                        <a href="<?php echo BASE_URL; ?>/users" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                            <i class="bi bi-people me-1"></i>Người dùng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card tips-card border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <i class="bi bi-lightbulb-fill text-warning tips-icon" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold mb-2 tips-title">💡 Mẹo sử dụng:</h6>
                            <ul class="mb-0 ps-3 tips-list">
                                <li class="mb-1">Sử dụng thanh tìm kiếm để tìm kiếm nhanh ứng viên hoặc nhân viên</li>
                                <li class="mb-1">Kiểm tra báo cáo thống kê hàng tuần để theo dõi hiệu quả tuyển dụng</li>
                                <li>Cập nhật thông tin nhân viên thường xuyên để đảm bảo dữ liệu chính xác</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== SECTION HEADER ===== */
    .section-header {
        animation: fadeInUp 0.6s ease 0.2s backwards;
    }

    /* ===== QUICK CARDS STYLES ===== */
    .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .welcome-banner h2 { font-size: 1.5rem; }
        .welcome-banner p { font-size: 0.9rem; }
        .icon-circle { width: 60px; height: 60px; }
        .icon-circle i { font-size: 1.5rem !important; }
        .card-title { font-size: 1rem; }
        .welcome-bg { width: 150px !important; height: 150px !important; }
    }

    /* ===== ADDITIONAL ANIMATIONS ===== */
    .btn { transition: all 0.3s ease; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.2); }
    .card { transition: all 0.3s ease; }

    /* =========================================================
        DARK MODE OVERRIDES (QUAN TRỌNG) 
       ========================================================= */
    
    /* 1. Tiêu đề mục (Lối tắt nhanh) */
    [data-theme="dark"] .section-header h4 {
        color: #fff !important;
    }
    [data-theme="dark"] .section-header p.text-muted {
        color: #a0a0a5 !important;
    }

    /* 2. Các thẻ Quick Cards (Thêm nhân viên, Thêm ứng viên...) */
    [data-theme="dark"] .quick-card {
        background-color: #1e1e1e !important; /* Nền card tối */
        border: 1px solid #333 !important;
    }
    [data-theme="dark"] .quick-card .card-title {
        color: #fff !important; /* Tên chức năng màu trắng */
    }
    [data-theme="dark"] .quick-card .text-muted {
        color: #b0b0b5 !important; /* Mô tả màu xám sáng */
    }
    /* Gradient mờ đi trong dark mode để đỡ chói */
    [data-theme="dark"] .card-gradient {
        opacity: 0.05 !important;
    }

    /* 3. Thẻ Liên kết nhanh (Links Card) */
    [data-theme="dark"] .links-card {
        background-color: #1e1e1e !important;
        border: 1px solid #333 !important;
    }
    [data-theme="dark"] .section-title-text {
        color: #fff !important;
    }
    /* Đổi màu các nút outline để dễ đọc hơn */
    [data-theme="dark"] .btn-outline-primary,
    [data-theme="dark"] .btn-outline-success,
    [data-theme="dark"] .btn-outline-info,
    [data-theme="dark"] .btn-outline-warning {
        color: #e0e0e0 !important;
        border-color: #555 !important;
    }
    [data-theme="dark"] .btn-outline-primary:hover,
    [data-theme="dark"] .btn-outline-success:hover,
    [data-theme="dark"] .btn-outline-info:hover,
    [data-theme="dark"] .btn-outline-warning:hover {
        color: #fff !important;
        background-color: #333 !important;
    }

    /* 4. Thẻ Mẹo sử dụng (Tips Card) */
    [data-theme="dark"] .tips-card {
        /* Chuyển từ gradient cam sáng sang gradient tối sang trọng */
        background: linear-gradient(135deg, #2c1a12 0%, #3e2015 100%) !important;
        border: 1px solid #5c3015 !important;
    }
    [data-theme="dark"] .tips-title {
        color: #ffb74d !important; /* Màu cam nhạt nổi bật */
    }
    [data-theme="dark"] .tips-list {
        color: #e0e0e0 !important;
    }
    [data-theme="dark"] .tips-icon {
        color: #ffb74d !important;
    }
</style>

<script>
    // ===== LIVE CLOCK =====
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const clockElement = document.getElementById('current-time');
        if (clockElement) {
            clockElement.textContent = timeString;
        }
    }
    
    // Update clock every second
    setInterval(updateClock, 1000);
    updateClock(); // Initial call
    
    // ===== WELCOME ANIMATION =====
    document.addEventListener('DOMContentLoaded', function() {
        // Add entrance animation to elements
        const cards = document.querySelectorAll('.quick-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${0.3 + (index * 0.1)}s`;
        });
    });
</script>
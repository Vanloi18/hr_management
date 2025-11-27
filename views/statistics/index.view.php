<?php // $title và các biến thống kê được truyền từ Controller ?>

<div class="container-fluid py-4">
    
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <div class="dashboard-icon bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                    <i class="bi bi-speedometer2 text-primary" style="font-size: 2rem;"></i>
                </div>
                <div>
                    <h1 class="mb-1 fw-bold"><?php echo e($title); ?></h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-calendar3 me-2"></i>
                        <?php echo date('l, d F Y'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        
        <!-- Card 1: Vị trí đang mở -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 position-relative">
                    <div class="stat-icon-bg position-absolute" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon rounded-3 p-3 me-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-briefcase-fill text-white fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 fw-semibold">Vị trí đang mở</h6>
                            <h2 class="mb-0 fw-bold" style="color: #667eea;"><?php echo e($totalOpenPositions); ?></h2>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">
                            <i class="bi bi-graph-up-arrow me-1"></i>Đang tuyển dụng
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Tổng số CV -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 position-relative">
                    <div class="stat-icon-bg position-absolute" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"></div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon rounded-3 p-3 me-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="bi bi-file-earmark-text-fill text-white fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 fw-semibold">Tổng số CV</h6>
                            <h2 class="mb-0 fw-bold" style="color: #4facfe;"><?php echo e($totalCandidates); ?></h2>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(79, 172, 254, 0.1); color: #4facfe;">
                            <i class="bi bi-people me-1"></i>Ứng viên
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Nhân viên Active -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 position-relative">
                    <div class="stat-icon-bg position-absolute" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"></div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon rounded-3 p-3 me-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <i class="bi bi-person-check-fill text-white fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 fw-semibold">Nhân viên Active</h6>
                            <h2 class="mb-0 fw-bold" style="color: #11998e;"><?php echo e($totalActiveEmployees); ?></h2>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(17, 153, 142, 0.1); color: #11998e;">
                            <i class="bi bi-check-circle me-1"></i>Đang làm việc
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Đối tác -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 position-relative">
                    <div class="stat-icon-bg position-absolute" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);"></div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon rounded-3 p-3 me-3" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="bi bi-building-fill text-white fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 fw-semibold">Đối tác</h6>
                            <h2 class="mb-0 fw-bold" style="color: #fa709a;"><?php echo e($totalRecruiters); ?></h2>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(250, 112, 154, 0.1); color: #fa709a;">
                            <i class="bi bi-handshake me-1"></i>Nhà tuyển dụng
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4">
        
        <!-- Chart 1: CV theo Trạng thái -->
        <div class="col-12 col-lg-4">
            <div class="chart-card card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="chart-icon bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                            <i class="bi bi-pie-chart-fill text-primary fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">CV theo Trạng thái</h5>
                            <small class="text-muted">
                                <i class="bi bi-people-fill me-1"></i>Tuyển dụng
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <canvas id="cvStatusChart" style="max-height: 300px;"></canvas>
                </div>
                <div class="card-footer bg-light border-0 py-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Phân bổ ứng viên theo trạng thái xử lý
                    </small>
                </div>
            </div>
        </div>

        <!-- Chart 2: Nhân viên theo Phòng ban -->
        <div class="col-12 col-lg-4">
            <div class="chart-card card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="chart-icon bg-success bg-opacity-10 rounded-3 p-2 me-3">
                            <i class="bi bi-diagram-3-fill text-success fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Nhân viên theo Phòng ban</h5>
                            <small class="text-muted">
                                <i class="bi bi-building me-1"></i>Nhân sự
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <canvas id="employeeDeptChart" style="max-height: 300px;"></canvas>
                </div>
                <div class="card-footer bg-light border-0 py-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Phân bổ nhân viên theo từng bộ phận
                    </small>
                </div>
            </div>
        </div>

        <!-- Chart 3: Vị trí theo Lĩnh vực -->
        <div class="col-12 col-lg-4">
            <div class="chart-card card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="chart-icon bg-warning bg-opacity-10 rounded-3 p-2 me-3">
                            <i class="bi bi-bar-chart-fill text-warning fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Vị trí theo Lĩnh vực</h5>
                            <small class="text-muted">
                                <i class="bi bi-tags-fill me-1"></i>Tuyển dụng
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <canvas id="positionFieldChart" style="max-height: 300px;"></canvas>
                </div>
                <div class="card-footer bg-light border-0 py-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Số lượng tin tuyển dụng theo ngành nghề
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Footer -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="row text-center g-3">
                        <div class="col-6 col-md-3">
                            <div class="quick-stat">
                                <i class="bi bi-calendar-check text-primary fs-3 mb-2"></i>
                                <h6 class="text-muted mb-1">Hôm nay</h6>
                                <p class="mb-0 fw-bold"><?php echo date('d/m/Y'); ?></p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="quick-stat">
                                <i class="bi bi-clock-history text-success fs-3 mb-2"></i>
                                <h6 class="text-muted mb-1">Thời gian</h6>
                                <p class="mb-0 fw-bold" id="current-time"><?php echo date('H:i:s'); ?></p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="quick-stat">
                                <i class="bi bi-person-circle text-info fs-3 mb-2"></i>
                                <h6 class="text-muted mb-1">Người dùng</h6>
                                <p class="mb-0 fw-bold"><?php echo $_SESSION['user']['full_name'] ?? 'Admin'; ?></p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="quick-stat">
                                <i class="bi bi-shield-check text-warning fs-3 mb-2"></i>
                                <h6 class="text-muted mb-1">Vai trò</h6>
                                <p class="mb-0 fw-bold text-capitalize"><?php echo $_SESSION['user']['role'] ?? 'N/A'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== DASHBOARD STYLES ===== */
    
    /* Dashboard Icon Animation */
    .dashboard-icon {
        transition: transform 0.3s ease;
    }
    
    .dashboard-icon:hover {
        transform: scale(1.1) rotate(5deg);
    }

    /* Stat Cards */
    .stat-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    
    .stat-icon {
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(-5deg);
    }
    
    .stat-icon-bg {
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        opacity: 0.1;
        transition: all 0.5s ease;
    }
    
    .stat-card:hover .stat-icon-bg {
        transform: scale(1.2) rotate(45deg);
        opacity: 0.15;
    }

    /* Chart Cards */
    .chart-card {
        transition: all 0.3s ease;
    }
    
    .chart-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    
    .chart-icon {
        transition: transform 0.3s ease;
    }
    
    .chart-card:hover .chart-icon {
        transform: rotate(360deg);
    }

    /* Quick Stats */
    .quick-stat {
        transition: all 0.3s ease;
        padding: 1rem;
        border-radius: 10px;
    }
    
    .quick-stat:hover {
        background: rgba(0,0,0,0.02);
        transform: scale(1.05);
    }
    
    .quick-stat i {
        transition: transform 0.3s ease;
    }
    
    .quick-stat:hover i {
        transform: scale(1.2);
    }

    /* Badge Animations */
    .badge {
        transition: all 0.3s ease;
    }
    
    .badge:hover {
        transform: scale(1.05);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stat-card .card-body {
            padding: 1.5rem !important;
        }
        
        .stat-icon {
            padding: 0.75rem !important;
        }
        
        .stat-icon i {
            font-size: 1.5rem !important;
        }
        
        h2 {
            font-size: 1.75rem;
        }
    }

    /* Card Header Gradient Bottom Border */
    .chart-card .card-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2, #11998e, #38ef7d);
        opacity: 0.3;
    }

    /* Smooth Fade In Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-card,
    .chart-card {
        animation: fadeInUp 0.6s ease;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }

    .chart-card:nth-child(1) { animation-delay: 0.5s; }
    .chart-card:nth-child(2) { animation-delay: 0.6s; }
    .chart-card:nth-child(3) { animation-delay: 0.7s; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- DỮ LIỆU TỪ PHP ---
        const cvLabels = <?php echo json_encode($cvStatusLabels); ?>;
        const cvData = <?php echo json_encode($cvStatusData); ?>;
        const posLabels = <?php echo json_encode($posFieldLabels); ?>;
        const posData = <?php echo json_encode($posFieldData); ?>;
        const empDeptLabels = <?php echo json_encode($empDeptLabels); ?>;
        const empDeptData = <?php echo json_encode($empDeptData); ?>;

        // Common Chart Options
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12,
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            }
        };

        // --- 1. VẼ BIỂU ĐỒ TRÒN (PIE) - CV ---
        if (cvData.length > 0) {
            const ctxPie = document.getElementById('cvStatusChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'doughnut', // Changed to doughnut for modern look
                data: {
                    labels: cvLabels,
                    datasets: [{
                        label: 'Số lượng CV',
                        data: cvData,
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.8)',   // Primary
                            'rgba(79, 172, 254, 0.8)',    // Info
                            'rgba(17, 153, 142, 0.8)',    // Success
                            'rgba(245, 87, 108, 0.8)',    // Danger
                            'rgba(108, 117, 125, 0.8)'    // Secondary
                        ],
                        borderColor: '#fff',
                        borderWidth: 3,
                        hoverOffset: 10
                    }]
                },
                options: {
                    ...commonOptions,
                    cutout: '60%', // For doughnut effect
                    plugins: {
                        ...commonOptions.plugins,
                        legend: {
                            ...commonOptions.plugins.legend,
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // --- 2. VẼ BIỂU ĐỒ CỘT (BAR) - VỊ TRÍ ---
        if (posData.length > 0) {
            const ctxBar = document.getElementById('positionFieldChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: posLabels,
                    datasets: [{
                        label: 'Số tin tuyển dụng',
                        data: posData,
                        backgroundColor: 'rgba(250, 112, 154, 0.7)',
                        borderColor: 'rgba(250, 112, 154, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { 
                                precision: 0,
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // --- 3. VẼ BIỂU ĐỒ TRÒN (PIE) - NHÂN VIÊN ---
        if (empDeptData.length > 0) {
            const ctxDeptPie = document.getElementById('employeeDeptChart').getContext('2d');
            new Chart(ctxDeptPie, {
                type: 'pie',
                data: {
                    labels: empDeptLabels,
                    datasets: [{
                        label: 'Số lượng Nhân viên',
                        data: empDeptData,
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.8)',
                            'rgba(79, 172, 254, 0.8)',
                            'rgba(17, 153, 142, 0.8)',
                            'rgba(250, 112, 154, 0.8)',
                            'rgba(254, 225, 64, 0.8)',
                            'rgba(13, 202, 240, 0.8)',
                            'rgba(253, 126, 20, 0.8)',
                            'rgba(102, 16, 242, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 3,
                        hoverOffset: 10
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        legend: {
                            ...commonOptions.plugins.legend,
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        // --- LIVE CLOCK ---
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
    });
</script>
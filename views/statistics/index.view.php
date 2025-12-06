<?php // $title và các biến thống kê được truyền từ Controller ?>

<div class="container-fluid py-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
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

                <div class="btn-group shadow-sm">
                    <a href="<?php echo BASE_URL; ?>/statistics/export-excel" class="btn btn-success text-white">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel
                    </a>
                    <button type="button" id="btn-export-pdf" class="btn btn-danger text-white">
                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="export-pdf-form" action="<?php echo BASE_URL; ?>/statistics/export-pdf" method="POST" target="_blank" class="d-none">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
        <input type="hidden" name="chart_cv" id="input_chart_cv">
        <input type="hidden" name="chart_emp" id="input_chart_emp">
        <input type="hidden" name="chart_pos" id="input_chart_pos">
    </form>

    <div class="row g-4 mb-4">
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

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="chart-card card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">CV theo Trạng thái</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="cvStatusChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="chart-card card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Nhân viên theo Phòng ban</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="employeeDeptChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="chart-card card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Vị trí theo Lĩnh vực</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="positionFieldChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

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
    /* Giữ nguyên style cũ của bạn */
    .dashboard-icon { transition: transform 0.3s ease; }
    .dashboard-icon:hover { transform: scale(1.1) rotate(5deg); }
    .stat-card { transition: all 0.3s ease; position: relative; overflow: hidden; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important; }
    .stat-icon { transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .stat-card:hover .stat-icon { transform: scale(1.1) rotate(-5deg); }
    .stat-icon-bg { position: absolute; top: -50%; right: -20%; width: 200px; height: 200px; border-radius: 50%; opacity: 0.1; transition: all 0.5s ease; }
    .stat-card:hover .stat-icon-bg { transform: scale(1.2) rotate(45deg); opacity: 0.15; }
    .chart-card { transition: all 0.3s ease; }
    .chart-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important; }
    .chart-icon { transition: transform 0.3s ease; }
    .chart-card:hover .chart-icon { transform: rotate(360deg); }
    .quick-stat { transition: all 0.3s ease; padding: 1rem; border-radius: 10px; }
    .quick-stat:hover { background: rgba(0,0,0,0.02); transform: scale(1.05); }
    .badge { transition: all 0.3s ease; }
    .badge:hover { transform: scale(1.05); }
    @media (max-width: 768px) {
        .stat-card .card-body { padding: 1.5rem !important; }
        .stat-icon { padding: 0.75rem !important; }
        .stat-icon i { font-size: 1.5rem !important; }
        h2 { font-size: 1.75rem; }
    }
    .chart-card .card-header::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: linear-gradient(90deg, #667eea, #764ba2, #11998e, #38ef7d); opacity: 0.3; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .stat-card, .chart-card { animation: fadeInUp 0.6s ease; }
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }
    .chart-card:nth-child(1) { animation-delay: 0.5s; }
    .chart-card:nth-child(2) { animation-delay: 0.6s; }
    .chart-card:nth-child(3) { animation-delay: 0.7s; }
    
    /* DARK MODE OVERRIDES */
    [data-theme="dark"] .bg-light { background-color: #1e1e1e !important; color: #e0e0e0; }
    [data-theme="dark"] .bg-white { background-color: #1e1e1e !important; }
    [data-theme="dark"] .card { background-color: #1e1e1e; border: 1px solid #333; }
    [data-theme="dark"] .text-dark { color: #fff !important; }
    [data-theme="dark"] .text-muted { color: #a0a0a5 !important; }
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

        // --- KHAI BÁO BIẾN CHART ĐỂ DÙNG SAU NÀY ---
        let chart1, chart2, chart3;

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } },
                tooltip: { backgroundColor: 'rgba(0, 0, 0, 0.8)', padding: 12, cornerRadius: 8 }
            }
        };

        // 1. Chart CV
        if (cvData.length > 0) {
            const ctxPie = document.getElementById('cvStatusChart').getContext('2d');
            chart1 = new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: cvLabels,
                    datasets: [{
                        label: 'Số lượng CV',
                        data: cvData,
                        backgroundColor: ['#667eea', '#4facfe', '#11998e', '#fa709a', '#6c757d'],
                        borderWidth: 2
                    }]
                },
                options: { ...commonOptions, cutout: '60%' }
            });
        }

        // 2. Chart Nhân viên
        if (empDeptData.length > 0) {
            const ctxDeptPie = document.getElementById('employeeDeptChart').getContext('2d');
            chart2 = new Chart(ctxDeptPie, {
                type: 'pie',
                data: {
                    labels: empDeptLabels,
                    datasets: [{
                        label: 'Nhân viên',
                        data: empDeptData,
                        backgroundColor: ['#667eea', '#4facfe', '#11998e', '#fa709a', '#fce38a', '#38ef7d'],
                        borderWidth: 2
                    }]
                },
                options: commonOptions
            });
        }

        // 3. Chart Vị trí
        if (posData.length > 0) {
            const ctxBar = document.getElementById('positionFieldChart').getContext('2d');
            chart3 = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: posLabels,
                    datasets: [{
                        label: 'Tin tuyển dụng',
                        data: posData,
                        backgroundColor: 'rgba(250, 112, 154, 0.7)',
                        borderColor: 'rgba(250, 112, 154, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { display: false } } }
                }
            });
        }

        // --- XỬ LÝ XUẤT PDF KÈM BIỂU ĐỒ ---
        const btnExport = document.getElementById('btn-export-pdf');
        if(btnExport) {
            btnExport.addEventListener('click', function() {
                // Lấy ảnh Base64 từ các biểu đồ
                const img1 = chart1 ? chart1.toBase64Image() : '';
                const img2 = chart2 ? chart2.toBase64Image() : '';
                const img3 = chart3 ? chart3.toBase64Image() : '';

                // Gán vào input hidden
                document.getElementById('input_chart_cv').value = img1;
                document.getElementById('input_chart_emp').value = img2;
                document.getElementById('input_chart_pos').value = img3;

                // Submit form
                document.getElementById('export-pdf-form').submit();
            });
        }

        // --- LIVE CLOCK ---
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const clock = document.getElementById('current-time');
            if (clock) clock.textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();
    });
</script>
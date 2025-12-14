<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cơ hội nghề nghiệp - Tuyển dụng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ========================================================= */
        /* GLOBAL STYLES */
        /* ========================================================= */
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1e40af;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --gradient-start: #3b82f6;
            --gradient-end: #8b5cf6;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-bottom: 60px;
        }
        
        /* ========================================================= */
        /* HEADER SECTION */
        /* ========================================================= */
        .hero-header {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.95) 0%, rgba(139, 92, 246, 0.95) 100%);
            padding: 60px 0 80px 0;

            margin-bottom: 30px;          
            position: relative;
            overflow: hidden;
        }
        
        .hero-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,197.3C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            color: white;
            text-align: center;
        }
        
        .hero-content h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .hero-content p {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 30px;
        }
        
        .hero-stat {
            text-align: center;
        }
        
        .hero-stat .number {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
        }
        
        .hero-stat .label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* ========================================================= */
        /* FILTER SECTION */
        /* ========================================================= */
        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        
        .filter-section .form-select,
        .filter-section .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.3s;
        }
        
        .filter-section .form-select:focus,
        .filter-section .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        
        /* ========================================================= */
        /* JOB CARDS */
        /* ========================================================= */
        .job-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            overflow: hidden;
            height: 100%;
            position: relative;
        }
        
        .job-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }
        
        .job-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        
        .job-card:hover::before {
            transform: scaleX(1);
        }
        
        .job-card-body {
            padding: 30px;
        }
        
        .job-badge {
            display: inline-block;
            padding: 6px 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
        
        .job-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        
        .job-company {
            color: #64748b;
            font-size: 1rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .job-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            font-size: 0.9rem;
        }
        
        .job-meta-item i {
            color: var(--primary-color);
            width: 20px;
            text-align: center;
        }
        
        .salary-highlight {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 16px;
            border-radius: 10px;
            margin: 15px 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .salary-highlight i {
            font-size: 1.2rem;
        }
        
        .deadline-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-apply {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
            color: white;
        }
        
        /* ========================================================= */
        /* EMPTY STATE */
        /* ========================================================= */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .empty-state i {
            font-size: 5rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: #475569;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: #94a3b8;
        }
        
        /* ========================================================= */
        /* RESPONSIVE */
        /* ========================================================= */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
            
            .hero-stats {
                flex-direction: column;
                gap: 20px;
            }
            
            .job-meta {
                flex-direction: column;
                gap: 10px;
            }
        }
        
        /* ========================================================= */
        /* ANIMATIONS */
        /* ========================================================= */
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
        
        .job-card {
            animation: fadeInUp 0.6s ease-out backwards;
        }
        
        .job-card:nth-child(1) { animation-delay: 0.1s; }
        .job-card:nth-child(2) { animation-delay: 0.2s; }
        .job-card:nth-child(3) { animation-delay: 0.3s; }
        .job-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    
    <!-- Hero Header -->
    <div class="hero-header">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-briefcase"></i> Cơ hội nghề nghiệp</h1>
                <p>Khám phá những vị trí tuyển dụng hấp dẫn và phát triển sự nghiệp của bạn cùng chúng tôi</p>
                
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="number"><?php echo $totalRecords ?? 0; ?></span>
                        <span class="label">Vị trí tuyển dụng</span>
                    </div>
                    <div class="hero-stat">
                        <span class="number">50+</span>
                        <span class="label">Công ty đối tác</span>
                    </div>
                    <div class="hero-stat">
                        <span class="number">1000+</span>
                        <span class="label">Ứng viên thành công</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="container">
        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="<?php echo BASE_URL; ?>/careers" class="row g-3">
                <a href="<?php echo BASE_URL; ?>/careers" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                </a>
                <div class="col-md-4">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                        <input type="text" name="keyword" class="form-control ps-5" 
                               placeholder="Tìm kiếm theo tên vị trí, mô tả..." 
                               value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="field_id" class="form-select">
                        <option value="">📂 Lĩnh vực</option>
                        <?php if(isset($fields) && !empty($fields)): ?>
                            <?php foreach($fields as $field): ?>
                                <option value="<?php echo $field['id']; ?>" 
                                        <?php echo (isset($_GET['field_id']) && $_GET['field_id'] == $field['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($field['field_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="location" class="form-select">
                        <option value="">📍 Địa điểm</option>
                        <option value="Hà Nội" <?php echo (isset($_GET['location']) && $_GET['location'] == 'Hà Nội') ? 'selected' : ''; ?>>Hà Nội</option>
                        <option value="Hồ Chí Minh" <?php echo (isset($_GET['location']) && $_GET['location'] == 'Hồ Chí Minh') ? 'selected' : ''; ?>>Hồ Chí Minh</option>
                        <option value="Đà Nẵng" <?php echo (isset($_GET['location']) && $_GET['location'] == 'Đà Nẵng') ? 'selected' : ''; ?>>Đà Nẵng</option>
                        <option value="Hải Phòng" <?php echo (isset($_GET['location']) && $_GET['location'] == 'Hải Phòng') ? 'selected' : ''; ?>>Hải Phòng</option>
                        <option value="Cần Thơ" <?php echo (isset($_GET['location']) && $_GET['location'] == 'Cần Thơ') ? 'selected' : ''; ?>>Cần Thơ</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="job_type" class="form-select">
                        <option value="">💼 Loại hình</option>
                        <option value="Full-time" <?php echo (isset($_GET['job_type']) && $_GET['job_type'] == 'Full-time') ? 'selected' : ''; ?>>Full-time</option>
                        <option value="Part-time" <?php echo (isset($_GET['job_type']) && $_GET['job_type'] == 'Part-time') ? 'selected' : ''; ?>>Part-time</option>
                        <option value="Internship" <?php echo (isset($_GET['job_type']) && $_GET['job_type'] == 'Internship') ? 'selected' : ''; ?>>Internship</option>
                        <option value="Freelance" <?php echo (isset($_GET['job_type']) && $_GET['job_type'] == 'Freelance') ? 'selected' : ''; ?>>Freelance</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search"></i> Tìm
                        </button>
                    </div>
                </div>
            </form>
            
            <?php if(!empty($_GET['keyword']) || !empty($_GET['field_id']) || !empty($_GET['location']) || !empty($_GET['job_type'])): ?>
            <div class="mt-3">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="text-muted" style="font-size: 0.9rem;">
                        <i class="fas fa-filter"></i> Đang lọc:
                    </span>
                    <?php if(!empty($_GET['keyword'])): ?>
                        <span class="badge bg-primary">
                            Từ khóa: "<?php echo htmlspecialchars($_GET['keyword']); ?>"
                        </span>
                    <?php endif; ?>
                    <?php if(!empty($_GET['location'])): ?>
                        <span class="badge bg-info">
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($_GET['location']); ?>
                        </span>
                    <?php endif; ?>
                    <?php if(!empty($_GET['job_type'])): ?>
                        <span class="badge bg-success">
                            <i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($_GET['job_type']); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Results Info -->
        <?php if(isset($totalJobs)): ?>
        <div class="mb-3">
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i>
                Tìm thấy <strong><?php echo $totalJobs; ?></strong> vị trí tuyển dụng
                <?php if(isset($currentPage) && isset($totalPages)): ?>
                    (Trang <?php echo $currentPage; ?>/<?php echo $totalPages; ?>)
                <?php endif; ?>
            </p>
        </div>
        <?php endif; ?>
        
        <!-- Job Cards Grid -->
        <div class="row">
            <?php if(!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                <div class="col-lg-6 mb-4">
                    <div class="job-card">
                        <div class="job-card-body">
                            <span class="job-badge">
                                <i class="fas fa-fire"></i> 
                                <?php echo htmlspecialchars($job['field_name'] ?? 'General'); ?>
                            </span>
                            
                            <h3 class="job-title">
                                <?php echo htmlspecialchars($job['title']); ?>
                            </h3>
                            
                            <div class="job-company">
                                <i class="fas fa-building"></i>
                                <span><?php echo htmlspecialchars($job['company_name']); ?></span>
                            </div>
                            
                            <div class="job-meta">
                                <div class="job-meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo htmlspecialchars($job['location'] ?? 'Hà Nội'); ?></span>
                                </div>
                                <div class="job-meta-item">
                                    <i class="fas fa-briefcase"></i>
                                    <span><?php echo htmlspecialchars($job['job_type'] ?? 'Full-time'); ?></span>
                                </div>
                                <div class="job-meta-item">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span><?php echo htmlspecialchars($job['experience'] ?? 'Yêu cầu kinh nghiệm'); ?></span>
                                </div>
                            </div>
                            
                            <div class="salary-highlight">
                                <span>
                                    <i class="fas fa-dollar-sign"></i>
                                    Mức lương
                                </span>
                                <strong>
                                    <?php 
                                        $salary = $job['salary_range'] ?? null;
                                        echo !empty($salary) ? htmlspecialchars($salary) : 'Thỏa thuận'; 
                                    ?>
                                </strong>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="deadline-badge">
                                    <i class="fas fa-clock"></i>
                                    Hạn: 
                                    <?php 
                                        $deadline = $job['end_date'] ?? null;
                                        echo $deadline ? date('d/m/Y', strtotime($deadline)) : 'Không giới hạn'; 
                                    ?>
                                </span>
                                <span class="text-muted" style="font-size: 0.85rem;">
                                    <i class="fas fa-eye"></i> 
                                    <?php echo rand(50, 500); ?> lượt xem
                                </span>
                            </div>
                            
                            <a href="<?php echo BASE_URL; ?>/careers/detail?id=<?php echo $job['id']; ?>" 
                               class="btn-apply">
                                <span>Xem chi tiết & Ứng tuyển</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>Không tìm thấy vị trí nào</h3>
                        <p>Hiện tại chúng tôi chưa có vị trí phù hợp. Vui lòng thử lại sau!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="row mt-5">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page-1; ?>&keyword=<?php echo htmlspecialchars($keyword ?? ''); ?>&field_id=<?php echo htmlspecialchars($field_id ?? ''); ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php 
                        // Logic hiển thị: Chỉ hiện trang đầu, trang cuối và các trang xung quanh trang hiện tại
                        $range = 2;
                        for ($i = 1; $i <= $totalPages; $i++): 
                            if ($i == 1 || $i == $totalPages || ($i >= $page - $range && $i <= $page + $range)):
                        ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&keyword=<?php echo htmlspecialchars($keyword ?? ''); ?>&field_id=<?php echo htmlspecialchars($field_id ?? ''); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php elseif (($i == $page - $range - 1) || ($i == $page + $range + 1)): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; endfor; ?>

                        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page+1; ?>&keyword=<?php echo htmlspecialchars($keyword ?? ''); ?>&field_id=<?php echo htmlspecialchars($field_id ?? ''); ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
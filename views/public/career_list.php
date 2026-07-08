<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cơ hội nghề nghiệp - Tuyển dụng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --bg-primary: #0a0a0f;
            --bg-card: #13131a;
            --border: #2a2a3a;
            --accent: #c9a84c;
            --text-primary: #f0f0f5;
            --text-muted: #6b6b80;
            --salary-green: #22c55e;
        }

        /* ===== BASE STYLES ===== */
        body {
            background: linear-gradient(135deg, #0a0a0f 0%, #0d0510 50%, #0a0a0f 100%);
            font-family: 'DM Sans', sans-serif;
            color: var(--text-primary);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Animated mesh gradient overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(201, 168, 76, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
            animation: meshGradient 15s ease infinite;
        }

        @keyframes meshGradient {
            0%, 100% {
                transform: translate(0, 0);
            }
            50% {
                transform: translate(20px, 10px);
            }
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 50%, #16213e 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            text-align: center;
            border-bottom: 1px solid rgba(201, 168, 76, 0.2);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 30% 50%, rgba(102, 126, 234, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 70% 30%, rgba(201, 168, 76, 0.08) 0%, transparent 40%);
            pointer-events: none;
        }

        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--text-primary);
            position: relative;
            z-index: 2;
            letter-spacing: -1px;
        }

        .page-header h1 i {
            color: var(--accent);
            margin-right: 10px;
        }

        .page-header p {
            font-size: 1rem;
            margin-bottom: 30px;
            color: var(--text-muted);
            font-weight: 300;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 2;
        }

        /* ===== STATS SECTION ===== */
        .stats {
            display: flex;
            justify-content: center;
            gap: 0;
            margin-top: 30px;
            position: relative;
            z-index: 2;
        }

        .stat-item {
            text-align: center;
            padding: 0 30px;
            border-right: 1px solid rgba(201, 168, 76, 0.3);
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--accent);
            text-shadow: 0 0 20px rgba(201, 168, 76, 0.4);
            margin-bottom: 5px;
            font-family: 'Playfair Display', serif;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 500;
        }

        /* ===== FILTER BOX ===== */
        .filter-box {
            background: rgba(19, 19, 26, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 25px;
            margin-bottom: 40px;
            border-radius: 8px;
            border: 1px solid rgba(201, 168, 76, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .filter-box .form-control,
        .filter-box .form-select {
            margin-bottom: 0;
            background: rgba(45, 45, 60, 0.5);
            border: 1px solid rgba(201, 168, 76, 0.15);
            color: var(--text-primary);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            padding: 10px 14px;
        }

        .filter-box .form-control::placeholder {
            color: var(--text-muted);
            opacity: 0.7;
        }

        .filter-box .form-control:focus,
        .filter-box .form-select:focus {
            background: rgba(45, 45, 60, 0.7);
            border-color: var(--accent);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.1);
        }

        .filter-box .form-select option {
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        .filter-box .btn-primary {
            background: var(--accent);
            border: none;
            color: var(--bg-primary);
            font-weight: 600;
            border-radius: 20px;
            padding: 10px 24px;
            transition: all 0.3s ease;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filter-box .btn-primary:hover {
            background: #e0bb6c;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(201, 168, 76, 0.3);
            color: var(--bg-primary);
        }

        /* ===== JOB GRID ===== */
        .jobs-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        /* ===== JOB CARDS ===== */
        .job-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
            border-left: 3px solid transparent;
            animation: cardFadeIn 0.6s ease backwards;
            display: flex;
            flex-direction: column;
        }

        @keyframes cardFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .jobs-grid .job-card:nth-child(1) { animation-delay: 0.05s; }
        .jobs-grid .job-card:nth-child(2) { animation-delay: 0.1s; }
        .jobs-grid .job-card:nth-child(3) { animation-delay: 0.15s; }
        .jobs-grid .job-card:nth-child(4) { animation-delay: 0.2s; }
        .jobs-grid .job-card:nth-child(5) { animation-delay: 0.25s; }
        .jobs-grid .job-card:nth-child(6) { animation-delay: 0.3s; }
        .jobs-grid .job-card:nth-child(7) { animation-delay: 0.35s; }
        .jobs-grid .job-card:nth-child(8) { animation-delay: 0.4s; }
        .jobs-grid .job-card:nth-child(9) { animation-delay: 0.45s; }
        .jobs-grid .job-card:nth-child(10) { animation-delay: 0.5s; }
        .jobs-grid .job-card:nth-child(11) { animation-delay: 0.55s; }
        .jobs-grid .job-card:nth-child(12) { animation-delay: 0.6s; }
        .jobs-grid .job-card:nth-child(13) { animation-delay: 0.65s; }
        .jobs-grid .job-card:nth-child(14) { animation-delay: 0.7s; }
        .jobs-grid .job-card:nth-child(15) { animation-delay: 0.75s; }
        .jobs-grid .job-card:nth-child(16) { animation-delay: 0.8s; }

        .job-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(201, 168, 76, 0.1) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }

        .job-card:hover {
            transform: translateY(-4px);
            border-color: rgba(201, 168, 76, 0.5);
            border-left-color: var(--accent);
            box-shadow: 
                0 16px 40px rgba(0, 0, 0, 0.5),
                inset 0 0 30px rgba(201, 168, 76, 0.05);
        }

        .job-card:hover::before {
            opacity: 1;
        }

        /* ===== JOB BADGE ===== */
        .job-badge {
            background: rgba(107, 107, 128, 0.3);
            color: var(--accent);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-block;
            margin-bottom: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid rgba(201, 168, 76, 0.2);
            transition: all 0.3s ease;
        }

        .job-card:hover .job-badge {
            background: rgba(201, 168, 76, 0.15);
            border-color: rgba(201, 168, 76, 0.4);
        }

        /* ===== JOB TITLE ===== */
        .job-title {
            font-size: clamp(0.95rem, 2.5vw, 1.5rem);
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-family: 'Playfair Display', serif;
            line-height: 1.3;
        }

        /* ===== JOB COMPANY ===== */
        .job-company {
            color: var(--text-muted);
            margin-bottom: 12px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .job-company i {
            color: var(--accent);
            margin-right: 6px;
        }

        /* ===== JOB INFO ===== */
        .job-info {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .job-info-item {
            color: var(--text-muted);
            font-size: 0.75rem;
            background: rgba(45, 45, 60, 0.4);
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid rgba(107, 107, 128, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .job-info-item i {
            color: var(--accent);
            font-size: 0.7rem;
        }

        /* ===== SALARY BOX ===== */
        .salary-box {
            background: transparent;
            color: transparent;
            padding: 8px 0;
            margin-bottom: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            border-left: 3px solid var(--salary-green);
            padding-left: 10px;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .salary-box i {
            -webkit-text-fill-color: var(--salary-green);
            color: var(--salary-green);
            font-size: 0.85rem;
        }

        /* ===== DEADLINE INFO ===== */
        .deadline-info {
            background-color: rgba(251, 146, 60, 0.15);
            color: #fb923c;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            border: 1px solid rgba(251, 146, 60, 0.2);
            margin-bottom: 0;
        }

        .deadline-info i {
            font-size: 0.65rem;
        }

        /* ===== BUTTON ===== */
        .btn-apply {
            background: transparent;
            color: var(--accent);
            width: 100%;
            padding: 10px;
            border: 1.5px solid var(--accent);
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: auto;
        }

        .btn-apply:hover {
            background: var(--accent);
            color: var(--bg-primary);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(201, 168, 76, 0.3);
        }

        /* ===== EMPTY STATE ===== */
        .empty-box {
            background: var(--bg-card);
            padding: 60px 20px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .jobs-grid .job-card[style*="grid-column"] .empty-box {
            grid-column: 1 / -1;
        }

        .empty-box i {
            font-size: 4rem;
            color: rgba(201, 168, 76, 0.3);
            margin-bottom: 20px;
            display: block;
        }

        .empty-box h3 {
            color: var(--text-primary);
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .empty-box p {
            color: var(--text-muted);
        }

        /* ===== TEXT UTILITIES ===== */
        .text-muted {
            color: var(--text-muted) !important;
            font-size: 0.85rem;
        }

        .badge {
            border: 1px solid rgba(201, 168, 76, 0.2);
            font-size: 0.8rem;
            padding: 6px 10px;
            font-weight: 500;
        }

        .badge.bg-primary {
            background: rgba(201, 168, 76, 0.15) !important;
            color: var(--accent) !important;
        }

        .badge.bg-info {
            background: rgba(79, 172, 254, 0.15) !important;
            color: #4facfe !important;
        }

        .badge.bg-success {
            background: rgba(34, 197, 94, 0.15) !important;
            color: #22c55e !important;
        }

        .badge.bg-secondary {
            background: rgba(107, 107, 128, 0.2) !important;
            color: var(--text-muted) !important;
        }

        /* ===== PAGINATION ===== */
        nav[style*="margin-top"] {
            display: flex;
            justify-content: center;
        }

        .pagination {
            gap: 4px;
            justify-content: center;
            align-items: center;
        }

        .pagination .page-link {
            color: var(--accent);
            border: 1px solid rgba(201, 168, 76, 0.2);
            background: transparent;
            border-radius: 4px;
            transition: all 0.3s ease;
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .pagination .page-link:hover {
            background: rgba(201, 168, 76, 0.1);
            border-color: var(--accent);
        }

        .pagination .page-item.active .page-link {
            background: var(--accent);
            border-color: var(--accent);
            color: var(--bg-primary);
        }

        .pagination .page-item.disabled .page-link {
            color: var(--text-muted);
            border-color: var(--border);
            cursor: not-allowed;
        }

        .pagination .page-item.disabled .page-link:hover {
            background: transparent;
        }

        /* ===== RESPONSIVE ===== */
        /* 3 columns: Below 1280px (xl) */
        @media (max-width: 1279px) {
            .jobs-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* 2 columns: Below 1024px (lg) */
        @media (max-width: 1023px) {
            .jobs-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* 1 column: Below 640px (sm) */
        @media (max-width: 639px) {
            .jobs-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .page-header {
                padding: 40px 0;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .page-header p {
                font-size: 0.9rem;
            }

            .stats {
                gap: 15px;
                padding: 0 20px;
            }

            .stat-item {
                padding: 0 20px;
                border-right: none;
            }

            .stat-item:not(:last-child)::after {
                content: '';
                position: absolute;
                right: -5px;
                width: 1px;
                height: 30px;
                background: rgba(201, 168, 76, 0.3);
            }

            .stat-number {
                font-size: 1.8rem;
            }

            .filter-box {
                padding: 15px;
            }

            .job-card {
                padding: 16px;
            }

            .job-title {
                font-size: 1.2rem;
            }
        }

        /* Tablet: 640px - 1024px */
        @media (min-width: 640px) and (max-width: 1023px) {
            .stats {
                gap: 20px;
            }
        }

        /* Desktop: 1024px+ */
        @media (min-width: 1024px) {
            .job-info-item {
                font-size: 0.8rem;
            }
        }

        /* ===== DARK MODE ADJUSTMENTS ===== */
        [data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-card: #13131a;
            --border: #2a2a3a;
            --accent: #c9a84c;
            --text-primary: #f0f0f5;
            --text-muted: #6b6b80;
        }
    </style>
</head>
<body>
    
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-briefcase"></i> Cơ hội nghề nghiệp</h1>
            <p>Khám phá những vị trí tuyển dụng hấp dẫn và phát triển sự nghiệp của bạn cùng chúng tôi</p>
            
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $totalRecords ?? 0; ?></div>
                    <div class="stat-label">Vị trí tuyển dụng</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Công ty đối tác</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Ứng viên thành công</div>
                </div>
            </div>
        </div>
    </div>
        
        <div class="container">
        <div class="filter-box">
            <form method="GET" action="<?php echo BASE_URL; ?>/careers" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control" 
                           placeholder="🔍 Tìm kiếm theo tên vị trí, mô tả..." 
                           value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>">
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
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                </div>
            </form>
            
            <?php if(!empty($_GET['keyword']) || !empty($_GET['field_id']) || !empty($_GET['location']) || !empty($_GET['job_type'])): ?>
            <div class="mt-3">
                <span class="text-muted"><i class="fas fa-filter"></i> Đang lọc: </span>
                <?php if(!empty($_GET['keyword'])): ?>
                    <span class="badge bg-primary">Từ khóa: "<?php echo htmlspecialchars($_GET['keyword']); ?>"</span>
                <?php endif; ?>
                <?php if(!empty($_GET['location'])): ?>
                    <span class="badge bg-info">📍 <?php echo htmlspecialchars($_GET['location']); ?></span>
                <?php endif; ?>
                <?php if(!empty($_GET['job_type'])): ?>
                    <span class="badge bg-success">💼 <?php echo htmlspecialchars($_GET['job_type']); ?></span>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>/careers" class="btn btn-sm btn-secondary ms-2">
                    <i class="fas fa-redo"></i> Xóa bộ lọc
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if(isset($totalJobs)): ?>
        <div class="mb-3">
            <p class="text-muted">
                <i class="fas fa-info-circle"></i>
                Tìm thấy <strong><?php echo $totalJobs; ?></strong> vị trí tuyển dụng
                <?php if(isset($currentPage) && isset($totalPages)): ?>
                    (Trang <?php echo $currentPage; ?>/<?php echo $totalPages; ?>)
                <?php endif; ?>
            </p>
        </div>
        <?php endif; ?>
        
        <div class="jobs-grid">
            <?php if(!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="job-card">
                        <span class="job-badge">
                            <i class="fas fa-fire"></i> 
                            <?php echo htmlspecialchars($job['field_name'] ?? 'General'); ?>
                        </span>
                        
                        <h3 class="job-title">
                            <?php echo htmlspecialchars($job['title']); ?>
                        </h3>
                        
                        <div class="job-company">
                            <i class="fas fa-building"></i>
                            <?php echo htmlspecialchars($job['company_name']); ?>
                        </div>
                        
                        <div class="job-info">
                            <div class="job-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($job['location'] ?? 'Hà Nội'); ?>
                            </div>
                            <div class="job-info-item">
                                <i class="fas fa-briefcase"></i>
                                <?php echo htmlspecialchars($job['job_type'] ?? 'Full-time'); ?>
                            </div>
                            <div class="job-info-item">
                                <i class="fas fa-graduation-cap"></i>
                                <?php echo htmlspecialchars($job['experience'] ?? 'Yêu cầu kinh nghiệm'); ?>
                            </div>
                        </div>
                        
                        <div class="salary-box">
                            <i class="fas fa-dollar-sign"></i>
                            Mức lương: 
                            <?php 
                                $salary = $job['salary_range'] ?? null;
                                echo !empty($salary) ? htmlspecialchars($salary) : 'Thỏa thuận'; 
                            ?>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 8px;">
                            <span class="deadline-info">
                                <i class="fas fa-clock"></i>
                                Hạn: 
                                <?php 
                                    $deadline = $job['end_date'] ?? null;
                                    echo $deadline ? date('d/m/Y', strtotime($deadline)) : 'Không giới hạn'; 
                                ?>
                            </span>
                        </div>
                        
                        <a href="<?php echo BASE_URL; ?>/careers/detail?id=<?php echo $job['id']; ?>" 
                           class="btn btn-apply">
                            Xem chi tiết & Ứng tuyển <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="job-card" style="grid-column: 1 / -1;">
                    <div class="empty-box">
                        <i class="fas fa-search"></i>
                        <h3>Không tìm thấy vị trí nào</h3>
                        <p>Hiện tại chúng tôi chưa có vị trí phù hợp. Vui lòng thử lại sau!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <nav style="margin-top: 40px; padding-top: 30px; border-top: 1px solid rgba(201, 168, 76, 0.1);">
            <ul class="pagination">
                        
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page-1; ?>&keyword=<?php echo htmlspecialchars($keyword ?? ''); ?>&field_id=<?php echo htmlspecialchars($field_id ?? ''); ?>&location=<?php echo htmlspecialchars($location ?? ''); ?>&job_type=<?php echo htmlspecialchars($job_type ?? ''); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>

                        <?php 
                        $range = 2;
                        for ($i = 1; $i <= $totalPages; $i++): 
                            if ($i == 1 || $i == $totalPages || ($i >= $page - $range && $i <= $page + $range)):
                        ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&keyword=<?php echo htmlspecialchars($keyword ?? ''); ?>&field_id=<?php echo htmlspecialchars($field_id ?? ''); ?>&location=<?php echo htmlspecialchars($location ?? ''); ?>&job_type=<?php echo htmlspecialchars($job_type ?? ''); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php elseif (($i == $page - $range - 1) || ($i == $page + $range + 1)): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; endfor; ?>

                        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page+1; ?>&keyword=<?php echo htmlspecialchars($keyword ?? ''); ?>&field_id=<?php echo htmlspecialchars($field_id ?? ''); ?>&location=<?php echo htmlspecialchars($location ?? ''); ?>&job_type=<?php echo htmlspecialchars($job_type ?? ''); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                        
                    </ul>
                </nav>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
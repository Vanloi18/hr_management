<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cơ hội nghề nghiệp - Tuyển dụng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f5f5f5;font-family: Arial, sans-serif;
        }
        .page-header {background-color: #2563eb;color: white; padding: 40px 0;margin-bottom: 30px;text-align: center;}
        .page-header h1 {font-size: 2.5rem;margin-bottom: 15px;}
        .page-header p {font-size: 1.1rem;margin-bottom: 20px;}
        .stats {display: flex; justify-content: center;gap: 50px;margin-top: 20px;}
        .stat-item {text-align: center;}
        .stat-number {font-size: 2rem;font-weight: bold;}
        .stat-label {font-size: 0.9rem;}
        .filter-box {background: white;padding: 20px;margin-bottom: 30px;border-radius: 5px;border: 1px solid #ddd;}
        .filter-box .form-control,
        .filter-box .form-select {margin-bottom: 10px;}
        .job-card {background: white;border: 1px solid #ddd; border-radius: 5px;padding: 20px;margin-bottom: 20px;transition: box-shadow 0.3s;}
        .job-card:hover {box-shadow: 0 4px 12px rgba(0,0,0,0.1);}
        .job-badge {background-color: #2563eb;color: white;padding: 4px 12px;border-radius: 3px;font-size: 0.8rem;display: inline-block;margin-bottom: 10px;
        }
        
        .job-title {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .job-company {
            color: #666;
            margin-bottom: 15px;
        }
        
        .job-info {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .job-info-item {
            color: #666;
            font-size: 0.9rem;
        }
        
        .job-info-item i {
            color: #2563eb;
            margin-right: 5px;
        }
        
        .salary-box {
            background-color: #10b981;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        
        .deadline-info {
            background-color: #fef3c7;
            color: #92400e;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.85rem;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .btn-apply {
            background-color: #2563eb;
            color: white;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .btn-apply:hover {
            background-color: #1d4ed8;
            color: white;
        }
        
        .empty-box {
            background: white;
            padding: 60px 20px;
            text-align: center;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .empty-box i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .pagination .page-link {
            color: #2563eb;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        
        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
                gap: 20px;
            }
            
            .job-info {
                flex-direction: column;
                gap: 8px;
            }
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
        
        <div class="row">
            <?php if(!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                <div class="col-lg-6">
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
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="deadline-info">
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
                           class="btn btn-apply">
                            Xem chi tiết & Ứng tuyển <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-box">
                        <i class="fas fa-search"></i>
                        <h3>Không tìm thấy vị trí nào</h3>
                        <p>Hiện tại chúng tôi chưa có vị trí phù hợp. Vui lòng thử lại sau!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="row mt-4">
            <div class="col-12">
                <nav>
                    <ul class="pagination justify-content-center">
                        
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page-1; ?>&keyword=<?php echo htmlspecialchars($keyword ?? ''); ?>&field_id=<?php echo htmlspecialchars($field_id ?? ''); ?>">
                                &laquo;
                            </a>
                        </li>

                        <?php 
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
                            <a class="page-link" href="?page=<?php echo $page+1; ?>&keyword=<?php echo htmlspecialchars($keyword ?? ''); ?>&field_id=<?php echo htmlspecialchars($field_id ?? ''); ?>">
                                &raquo;
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
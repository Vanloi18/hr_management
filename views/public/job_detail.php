<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - Chi tiết tuyển dụng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --gradient-start: #3b82f6;
            --gradient-end: #8b5cf6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        /* HERO HEADER */
        .job-header {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            padding: 60px 0 80px;
            color: white;
            position: relative;
            margin-bottom: 30px;
          
        }

        .back-link {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .back-link:hover { color: white; transform: translateX(-5px); }

        .job-title { font-size: 2.5rem; font-weight: 800; margin-bottom: 15px; }
        
        .job-meta-header {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .job-meta-header i { margin-right: 8px; }

        /* MAIN CONTENT CARDS */
        .content-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: var(--primary-color);
            border-radius: 4px;
            display: block;
        }

        .job-content-text {
            font-size: 1rem;
            line-height: 1.8;
            color: #475569;
            text-align: justify;
        }

        /* SIDEBAR & FORM */
        .sidebar-sticky {
            position: sticky;
            top: 20px; /* Dính khi cuộn */
        }

        .apply-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); /* Bóng đậm hơn để nổi bật */
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .apply-header {
            background: #f1f5f9;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .apply-header h4 { margin: 0; font-size: 1.1rem; font-weight: 700; color: #334155; }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-submit-apply {
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            color: white;
            font-weight: 600;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-submit-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .info-list li {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            color: #64748b;
        }
        .info-list li strong { color: #334155; }
        .info-list li:last-child { border-bottom: none; }

    </style>
</head>
<body>

    <div class="job-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <a href="<?php echo BASE_URL; ?>/careers" class="back-link">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                    
                    <h1 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h1>
                    
                    <div class="job-meta-header">
                        <span>
                            <i class="fas fa-building"></i> 
                            <?php echo htmlspecialchars($job['company_name'] ?? 'Công ty Tuyển dụng'); ?>
                        </span>
                        <span>|</span>
                        <span>
                            <i class="fas fa-map-marker-alt"></i> 
                            <?php echo htmlspecialchars($job['location'] ?? 'Thỏa thuận'); ?>
                        </span>
                        <span>|</span>
                        <span>
                            <i class="fas fa-dollar-sign"></i> 
                            <?php echo htmlspecialchars($job['salary_range'] ?? 'Thỏa thuận'); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row justify-content-center">
            
            <div class="col-lg-7">
                <div class="content-card">
                    <h3 class="section-title">Mô tả công việc</h3>
                    <div class="job-content-text">
                        <?php echo nl2br(htmlspecialchars($job['description'])); ?>
                    </div>
                </div>

                <div class="content-card">
                    <h3 class="section-title">Yêu cầu ứng viên</h3>
                    <div class="job-content-text">
                        <?php echo nl2br(htmlspecialchars($job['requirements'])); ?>
                    </div>
                </div>

                <div class="content-card">
                    <h3 class="section-title">Quyền lợi được hưởng</h3>
                    <div class="job-content-text">
                        <ul style="padding-left: 20px;">
                            <li>Mức lương cạnh tranh, thưởng theo hiệu quả công việc.</li>
                            <li>Được đóng BHXH, BHYT theo quy định nhà nước.</li>
                            <li>Môi trường làm việc trẻ trung, năng động.</li>
                            <li>Cơ hội thăng tiến rõ ràng.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4"> <div class="sidebar-sticky">
                    
                    <div class="content-card p-4 mb-4">
                        <h5 class="fw-bold mb-3">Thông tin chung</h5>
                        <ul class="info-list">
                            <li>
                                <span><i class="fas fa-briefcase me-2"></i> Hình thức:</span>
                                <strong><?php echo htmlspecialchars($job['job_type'] ?? 'Full-time'); ?></strong>
                            </li>
                            <li>
                                <span><i class="fas fa-calendar-alt me-2"></i> Ngày đăng:</span>
                                <strong><?php echo date('d/m/Y', strtotime($job['created_at'])); ?></strong>
                            </li>
                            <li>
                                <span><i class="fas fa-clock me-2"></i> Hạn nộp:</span>
                                <strong class="text-danger">
                                    <?php 
                                        $deadline = $job['end_date'] ?? null;
                                        echo $deadline ? date('d/m/Y', strtotime($deadline)) : 'Không giới hạn'; 
                                    ?>
                                </strong>
                            </li>
                            <li>
                                <span><i class="fas fa-eye me-2"></i> Lượt xem:</span>
                                <strong><?php echo rand(100, 2000); ?></strong>
                            </li>
                        </ul>
                    </div>

                    <div class="apply-card">
                        <div class="apply-header">
                            <h4><i class="fas fa-paper-plane text-primary"></i> Ứng tuyển ngay</h4>
                            <small class="text-muted">Điền thông tin để nộp hồ sơ</small>
                        </div>
                        <div class="p-4">
                            <form action="<?php echo BASE_URL; ?>/careers/apply" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="position_id" value="<?php echo $job['id']; ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Họ và tên</label>
                                    <input type="text" name="full_name" class="form-control" placeholder="Nguyễn Văn A" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control" placeholder="09xxxxxxxx" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold small">CV đính kèm (PDF, Word)</label>
                                    <input type="file" name="resume" class="form-control" required accept=".pdf,.doc,.docx">
                                    <div class="form-text small"><i class="fas fa-info-circle"></i> Tối đa 5MB.</div>
                                </div>
                                
                                <button type="submit" class="btn-submit-apply">
                                    GỬI HỒ SƠ ỨNG TUYỂN
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
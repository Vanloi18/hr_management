<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - Chi tiết tuyển dụng</title>
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

        body {
            background: linear-gradient(135deg, #0a0a0f 0%, #0d0510 50%, #0a0a0f 100%);
            font-family: 'DM Sans', sans-serif;
            color: var(--text-primary);
            min-height: 100vh;
        }

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
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(20px, 10px); }
        }

        .page-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 50%, #16213e 100%);
            color: white;
            padding: 50px 0;
            margin-bottom: 40px;
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

        .back-link {
            color: var(--accent);
            text-decoration: none;
            margin-bottom: 15px;
            display: inline-block;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .back-link:hover {
            color: #e0bb6c;
            transform: translateX(-4px);
        }

        .job-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--text-primary);
            font-weight: 700;
            position: relative;
            z-index: 2;
            letter-spacing: -1px;
        }

        .job-meta {
            font-size: 0.95rem;
            color: var(--text-muted);
            position: relative;
            z-index: 2;
        }

        .job-meta i {
            color: var(--accent);
            margin-right: 6px;
        }

        .content-section {
            background: var(--bg-card);
            padding: 28px;
            margin-bottom: 24px;
            border-radius: 8px;
            border: 1px solid var(--border);
            animation: fadeInUp 0.6s ease 0.2s backwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--text-primary);
            border-bottom: 2px solid rgba(201, 168, 76, 0.3);
            padding-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .content-section div,
        .content-section ul li {
            color: var(--text-muted);
            line-height: 1.7;
            font-size: 0.95rem;
        }

        .content-section ul {
            margin: 0;
            padding-left: 20px;
        }

        .content-section ul li {
            margin-bottom: 8px;
            color: var(--text-muted);
        }

        .info-table {
            width: 100%;
        }

        .info-table tr {
            border-bottom: 1px solid var(--border);
        }

        .info-table td {
            padding: 12px 0;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .info-table td:first-child {
            font-weight: 600;
            width: 40%;
            color: var(--accent);
        }

        .info-table i {
            color: var(--accent);
            margin-right: 6px;
        }

        .apply-box {
            background: var(--bg-card);
            padding: 28px;
            border-radius: 8px;
            border: 2px solid rgba(201, 168, 76, 0.3);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.6s ease 0.3s backwards;
        }

        .apply-box h4 {
            text-align: center;
            margin-bottom: 24px;
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--accent);
            font-weight: 700;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .form-control {
            background: rgba(45, 45, 60, 0.5);
            border: 1px solid rgba(201, 168, 76, 0.15);
            color: var(--text-primary);
            margin-bottom: 16px;
            border-radius: 6px;
            padding: 10px 14px;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: var(--text-muted);
            opacity: 0.7;
        }

        .form-control:focus {
            background: rgba(45, 45, 60, 0.7);
            border-color: var(--accent);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.1);
        }

        .btn-submit {
            background: var(--accent);
            color: var(--bg-primary);
            width: 100%;
            padding: 12px;
            font-weight: 700;
            border: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-family: 'DM Sans', sans-serif;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .btn-submit:hover {
            background: #e0bb6c;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(201, 168, 76, 0.3);
            color: var(--bg-primary);
        }

        small.text-muted {
            color: var(--text-muted) !important;
            font-size: 0.8rem;
            display: block;
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 30px 0;
            }

            .job-title {
                font-size: 1.5rem;
            }

            .job-meta {
                font-size: 0.85rem;
            }

            .content-section {
                padding: 16px;
            }

            .section-title {
                font-size: 1.1rem;
            }

            .apply-box {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="page-header">
        <div class="container">
            <a href="<?php echo BASE_URL; ?>/careers" class="back-link">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
            
            <h1 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h1>
            
            <div class="job-meta">
                <i class="fas fa-building"></i> 
                <?php echo htmlspecialchars($job['company_name'] ?? 'Công ty Tuyển dụng'); ?>
                <span class="mx-2">|</span>
                <i class="fas fa-map-marker-alt"></i> 
                <?php echo htmlspecialchars($job['location'] ?? 'Thỏa thuận'); ?>
                <span class="mx-2">|</span>
                <i class="fas fa-dollar-sign"></i> 
                <?php echo htmlspecialchars($job['salary_range'] ?? 'Thỏa thuận'); ?>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            
            <div class="col-lg-8">
                <div class="content-section">
                    <h3 class="section-title">Mô tả công việc</h3>
                    <div>
                        <?php echo nl2br(htmlspecialchars($job['description'])); ?>
                    </div>
                </div>

                <div class="content-section">
                    <h3 class="section-title">Yêu cầu ứng viên</h3>
                    <div>
                        <?php echo nl2br(htmlspecialchars($job['requirements'])); ?>
                    </div>
                </div>

                <div class="content-section">
                    <h3 class="section-title">Quyền lợi được hưởng</h3>
                    <ul>
                        <li>Mức lương cạnh tranh, thưởng theo hiệu quả công việc.</li>
                        <li>Được đóng BHXH, BHYT theo quy định nhà nước.</li>
                        <li>Môi trường làm việc trẻ trung, năng động.</li>
                        <li>Cơ hội thăng tiến rõ ràng.</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="content-section mb-4">
                    <h5 class="section-title">Thông tin chung</h5>
                    <table class="info-table">
                        <tr>
                            <td><i class="fas fa-briefcase"></i> Hình thức:</td>
                            <td><?php echo htmlspecialchars($job['job_type'] ?? 'Full-time'); ?></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-calendar-alt"></i> Ngày đăng:</td>
                            <td><?php echo date('d/m/Y', strtotime($job['created_at'])); ?></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-clock"></i> Hạn nộp:</td>
                            <td style="color: red;">
                                <?php 
                                    $deadline = $job['end_date'] ?? null;
                                    echo $deadline ? date('d/m/Y', strtotime($deadline)) : 'Không giới hạn'; 
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-eye"></i> Lượt xem:</td>
                            <td><?php echo rand(100, 2000); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="apply-box">
                    <h4><i class="fas fa-paper-plane"></i> Ứng tuyển ngay</h4>
                    <form action="<?php echo BASE_URL; ?>/careers/apply" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="position_id" value="<?php echo $job['id']; ?>">
                        
                        <div>
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control" placeholder="Nguyễn Văn A" required>
                        </div>
                        
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                        </div>
                        
                        <div>
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" placeholder="09xxxxxxxx" required>
                        </div>
                        
                        <div>
                            <label class="form-label">CV đính kèm (PDF, Word)</label>
                            <input type="file" name="resume" class="form-control" required accept=".pdf,.doc,.docx">
                            <small class="text-muted">Tối đa 5MB</small>
                        </div>
                        
                        <button type="submit" class="btn btn-submit">
                            GỬI HỒ SƠ ỨNG TUYỂN
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
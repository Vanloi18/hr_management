<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - Chi tiết tuyển dụng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .page-header {
            background-color: #2563eb;
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }

        .back-link {
            color: white;
            text-decoration: none;
            margin-bottom: 15px;
            display: inline-block;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .job-title {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .job-meta {
            font-size: 1rem;
        }

        .content-section {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }

        .info-table {
            width: 100%;
        }

        .info-table tr {
            border-bottom: 1px solid #eee;
        }

        .info-table td {
            padding: 12px 0;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
        }

        .apply-box {
            background: white;
            padding: 25px;
            border-radius: 5px;
            border: 2px solid #2563eb;
        }

        .apply-box h4 {
            text-align: center;
            margin-bottom: 20px;
            color: #2563eb;
        }

        .form-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .btn-submit {
            background-color: #2563eb;
            color: white;
            width: 100%;
            padding: 12px;
            font-weight: bold;
            border: none;
        }

        .btn-submit:hover {
            background-color: #1d4ed8;
            color: white;
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
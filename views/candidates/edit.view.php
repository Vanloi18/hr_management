<?php 
// $candidate và $positions được truyền từ Controller
$errors = $_SESSION['_flash']['errors'] ?? [];
unset($_SESSION['_flash']['errors']);
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                
                <!-- Card Header với gradient -->
                <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-50 rounded-3 p-3 me-3">
                            <i class="bi bi-pencil-square text-dark" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 text-dark fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-dark text-opacity-75 small">
                                <i class="bi bi-person-badge me-1"></i>
                                Đang chỉnh sửa: <strong><?php echo e($candidate['full_name']); ?></strong>
                            </p>
                        </div>
                        <div class="badge bg-white text-dark rounded-pill px-3 py-2">
                            ID: #<?php echo e($candidate['id']); ?>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                
                    <!-- Hiển thị lỗi -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger border-0 rounded-3 shadow-sm" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                                <div class="flex-grow-1">
                                    <h6 class="alert-heading mb-2 fw-bold">Có lỗi xảy ra!</h6>
                                    <ul class="mb-0 ps-3">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo BASE_URL; ?>/candidates/update" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($candidate['id']); ?>">
                        
                        <!-- Thông tin cơ bản -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-person-circle text-info me-2"></i>Thông tin Ứng viên
                            </h5>
                        </div>

                        <!-- Vị trí ứng tuyển -->
                        <div class="mb-4">
                            <label for="position_id" class="form-label fw-semibold">
                                <i class="bi bi-briefcase text-info me-2"></i>Vị trí ứng tuyển
                                <span class="text-danger">*</span>
                            </label>
                            <select id="position_id" 
                                    name="position_id" 
                                    class="form-select form-select-lg rounded-3 shadow-sm" 
                                    required>
                                <option value="">-- Chọn Vị trí --</option>
                                <?php foreach ($positions as $position): ?>
                                    <option value="<?php echo e($position['id']); ?>" <?php echo $candidate['position_id'] == $position['id'] ? 'selected' : ''; ?>>
                                        <?php echo e($position['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Họ tên -->
                        <div class="mb-4">
                            <label for="full_name" class="form-label fw-semibold">
                                <i class="bi bi-person text-info me-2"></i>Họ tên
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="full_name" 
                                   name="full_name" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   value="<?php echo e($candidate['full_name']); ?>" 
                                   placeholder="Nhập họ và tên đầy đủ"
                                   required>
                        </div>

                        <!-- Email & Phone -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope text-info me-2"></i>Email
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control form-control-lg rounded-3 shadow-sm" 
                                       value="<?php echo e($candidate['email']); ?>" 
                                       placeholder="example@email.com"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="bi bi-telephone text-info me-2"></i>Số điện thoại
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       id="phone" 
                                       name="phone" 
                                       class="form-control form-control-lg rounded-3 shadow-sm" 
                                       value="<?php echo e($candidate['phone']); ?>" 
                                       placeholder="0912345678"
                                       required>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- CV File Section -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i>Quản lý CV
                            </h5>
                        </div>

                        <!-- CV hiện tại -->
                        <div class="alert alert-info border-0 rounded-3 shadow-sm mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-check-fill me-3 fs-4"></i>
                                <div class="flex-grow-1">
                                    <strong>CV hiện tại:</strong>
                                    <div class="mt-2">
                                        <a href="<?php echo BASE_URL; ?>/<?php echo e($candidate['cv_file_path']); ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-info rounded-pill">
                                            <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> 
                                            Xem CV: <?php echo e(basename($candidate['cv_file_path'])); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload CV mới -->
                        <div class="mb-4">
                            <label for="cv_file" class="form-label fw-semibold">
                                <i class="bi bi-cloud-upload text-danger me-2"></i>Thay thế file CV
                            </label>
                            <input type="file" 
                                   id="cv_file" 
                                   name="cv_file" 
                                   class="form-control form-control-lg rounded-3 shadow-sm"
                                   accept=".pdf,.doc,.docx">
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Bỏ trống</strong> nếu không muốn thay đổi CV | Chấp nhận: <strong>.pdf, .doc, .docx</strong> | Tối đa: <strong>5MB</strong>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Trạng thái & Ghi chú -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-sliders text-warning me-2"></i>Trạng thái & Đánh giá
                            </h5>
                        </div>

                        <!-- Trạng thái -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">
                                <i class="bi bi-circle-fill text-warning me-2"></i>Trạng thái
                            </label>
                            <select id="status" 
                                    name="status" 
                                    class="form-select form-select-lg rounded-3 shadow-sm">
                                <option value="pending" <?php echo $candidate['status'] === 'pending' ? 'selected' : ''; ?>>
                                    ⏳ Chờ xử lý (Pending)
                                </option>
                                <option value="interviewing" <?php echo $candidate['status'] === 'interviewing' ? 'selected' : ''; ?>>
                                    💬 Đang phỏng vấn (Interviewing)
                                </option>
                                <option value="hired" <?php echo $candidate['status'] === 'hired' ? 'selected' : ''; ?>>
                                    ✅ Đã tuyển (Hired)
                                </option>
                                <option value="rejected" <?php echo $candidate['status'] === 'rejected' ? 'selected' : ''; ?>>
                                    ❌ Bị từ chối (Rejected)
                                </option>
                            </select>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Cập nhật trạng thái xử lý hồ sơ
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold">
                                <i class="bi bi-pencil-square text-warning me-2"></i>Ghi chú & Đánh giá
                            </label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="5" 
                                      class="form-control rounded-3 shadow-sm"
                                      placeholder="Nhập ghi chú, đánh giá nội bộ về ứng viên..."><?php echo e($candidate['notes']); ?></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Ghi chú nội bộ giúp theo dõi quá trình tuyển dụng
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/candidates" 
                               class="btn btn-lg btn-light border rounded-pill px-4 shadow-sm">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm" 
                                    style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; color: #333;">
                                <i class="bi bi-save-fill me-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center text-muted small flex-wrap gap-2">
                        <span>
                            <i class="bi bi-calendar3 me-1"></i>
                            Nộp hồ sơ: <strong><?php echo date('d/m/Y H:i', strtotime($candidate['applied_at'])); ?></strong>
                        </span>
                        <span>
                            <i class="bi bi-person-badge me-1"></i>
                            ID: <strong>#<?php echo e($candidate['id']); ?></strong>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow border-0 rounded-4 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-lightning-fill text-warning me-2"></i>Hành động nhanh:
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" 
                                class="btn btn-outline-success btn-sm rounded-pill px-3"
                                onclick="document.getElementById('status').value='interviewing'; document.querySelector('form').submit();">
                            <i class="bi bi-chat-dots me-1"></i>Chuyển sang Phỏng vấn
                        </button>
                        <button type="button" 
                                class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                onclick="document.getElementById('status').value='hired'; document.querySelector('form').submit();">
                            <i class="bi bi-check-circle me-1"></i>Đánh dấu Đã tuyển
                        </button>
                        <button type="button" 
                                class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                onclick="document.getElementById('status').value='rejected'; document.querySelector('form').submit();">
                            <i class="bi bi-x-circle me-1"></i>Từ chối ứng viên
                        </button>
                        <a href="mailto:<?php echo e($candidate['email']); ?>" 
                           class="btn btn-outline-info btn-sm rounded-pill px-3">
                            <i class="bi bi-envelope me-1"></i>Gửi Email
                        </a>
                        <a href="tel:<?php echo e($candidate['phone']); ?>" 
                           class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="bi bi-telephone me-1"></i>Gọi điện
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #4facfe;
        box-shadow: 0 0 0 0.25rem rgba(79, 172, 254, 0.25);
    }
    
    .icon-box {
        transition: transform 0.3s ease;
    }
    
    .card:hover .icon-box {
        transform: scale(1.1) rotate(-5deg);
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
    }
    
    .section-title h5 {
        position: relative;
        padding-bottom: 10px;
    }
    
    .section-title h5::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border-radius: 2px;
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .card-header .badge {
            display: none;
        }
    }
</style>

<script>
    // File preview enhancement
    document.getElementById('cv_file').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const fileName = this.files[0].name;
            const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2); // MB
            
            // Show preview
            const preview = document.createElement('div');
            preview.className = 'alert alert-success border-0 rounded-3 mt-2';
            preview.innerHTML = `
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>File mới:</strong> ${fileName} (${fileSize} MB)
            `;
            
            // Remove old preview if exists
            const oldPreview = this.parentElement.querySelector('.alert-success');
            if (oldPreview) oldPreview.remove();
            
            this.parentElement.appendChild(preview);
        }
    });
</script>
<?php 
// $recruiters và $fields được truyền từ Controller
$errors = $_SESSION['_flash']['errors'] ?? [];
$old = $_SESSION['_flash']['old'] ?? [];
unset($_SESSION['_flash']['errors'], $_SESSION['_flash']['old']);
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                
                <!-- Card Header với gradient -->
                <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-50 rounded-3 p-3 me-3">
                            <i class="bi bi-megaphone-fill text-dark" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-dark fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-dark text-opacity-75 small">Tạo tin tuyển dụng mới cho vị trí công việc</p>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/positions" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        
                        <!-- Thông tin cơ bản -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-briefcase-fill text-primary me-2"></i>Thông tin Vị trí
                            </h5>
                        </div>

                        <!-- Tiêu đề vị trí -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                <i class="bi bi-briefcase text-primary me-2"></i>Tiêu đề Vị trí
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   value="<?php echo e($old['title'] ?? ''); ?>" 
                                   placeholder="Ví dụ: Nhân viên Kinh doanh, Lập trình viên PHP..."
                                   required>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Nhập tên vị trí công việc cần tuyển
                            </div>
                        </div>

                        <!-- Nhà tuyển dụng & Lĩnh vực -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="recruiter_id" class="form-label fw-semibold">
                                    <i class="bi bi-building text-primary me-2"></i>Nhà tuyển dụng
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="recruiter_id" 
                                        name="recruiter_id" 
                                        class="form-select form-select-lg rounded-3 shadow-sm" 
                                        required>
                                    <option value="">-- Chọn Công ty --</option>
                                    <?php foreach ($recruiters as $recruiter): ?>
                                        <option value="<?php echo e($recruiter['id']); ?>" <?php echo ($old['recruiter_id'] ?? '') == $recruiter['id'] ? 'selected' : ''; ?>>
                                            <?php echo e($recruiter['company_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    <i class="bi bi-building-fill me-1"></i>Chọn công ty đăng tin
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="field_id" class="form-label fw-semibold">
                                    <i class="bi bi-tag text-primary me-2"></i>Lĩnh vực
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="field_id" 
                                        name="field_id" 
                                        class="form-select form-select-lg rounded-3 shadow-sm" 
                                        required>
                                    <option value="">-- Chọn Lĩnh vực --</option>
                                    <?php foreach ($fields as $field): ?>
                                        <option value="<?php echo e($field['id']); ?>" <?php echo ($old['field_id'] ?? '') == $field['id'] ? 'selected' : ''; ?>>
                                            <?php echo e($field['field_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    <i class="bi bi-tag-fill me-1"></i>Ngành nghề phù hợp
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Chi tiết công việc -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-file-text-fill text-success me-2"></i>Chi tiết Công việc
                            </h5>
                        </div>

                        <!-- Mô tả công việc -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="bi bi-file-text text-success me-2"></i>Mô tả Công việc
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="5" 
                                      class="form-control rounded-3 shadow-sm"
                                      placeholder="Nhập mô tả chi tiết về công việc, trách nhiệm, quyền lợi..."><?php echo e($old['description'] ?? ''); ?></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Mô tả càng chi tiết càng thu hút ứng viên
                            </div>
                        </div>

                        <!-- Yêu cầu công việc -->
                        <div class="mb-4">
                            <label for="requirements" class="form-label fw-semibold">
                                <i class="bi bi-list-check text-success me-2"></i>Yêu cầu Công việc
                            </label>
                            <textarea id="requirements" 
                                      name="requirements" 
                                      rows="5" 
                                      class="form-control rounded-3 shadow-sm"
                                      placeholder="Nhập yêu cầu về kinh nghiệm, kỹ năng, trình độ học vấn..."><?php echo e($old['requirements'] ?? ''); ?></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Liệt kê các yêu cầu cần thiết cho vị trí này
                            </div>
                        </div>

                        <!-- Trạng thái -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">
                                <i class="bi bi-circle-fill text-success me-2"></i>Trạng thái
                            </label>
                            <select id="status" 
                                    name="status" 
                                    class="form-select form-select-lg rounded-3 shadow-sm">
                                <option value="open" <?php echo ($old['status'] ?? 'open') === 'open' ? 'selected' : ''; ?>>
                                    ✅ Đang mở (Open) - Đang tuyển dụng
                                </option>
                                <option value="closed" <?php echo ($old['status'] ?? '') === 'closed' ? 'selected' : ''; ?>>
                                    ❌ Đã đóng (Closed) - Ngừng nhận CV
                                </option>
                            </select>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Chọn trạng thái hiển thị tin tuyển dụng
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/positions" 
                               class="btn btn-lg btn-light border rounded-pill px-4 shadow-sm">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm" 
                                    style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border: none; color: #333;">
                                <i class="bi bi-megaphone-fill me-2"></i>Đăng tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles */
    .form-control:focus,
    .form-select:focus {
        border-color: #a8edea;
        box-shadow: 0 0 0 0.25rem rgba(168, 237, 234, 0.25);
    }
    
    .icon-box {
        transition: transform 0.3s ease;
    }
    
    .card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
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
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        border-radius: 2px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
    }
</style>
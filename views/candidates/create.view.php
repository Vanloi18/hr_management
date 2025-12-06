<?php 
// $positions và $selected_position_id được truyền từ Controller
$errors = $_SESSION['_flash']['errors'] ?? [];
$old = $_SESSION['_flash']['old'] ?? [];
unset($_SESSION['_flash']['errors'], $_SESSION['_flash']['old']);
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                
                <!-- Card Header với gradient -->
                <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="bi bi-person-plus-fill text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-white fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-white-50 small">Nhập thông tin ứng viên và upload CV</p>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/candidates" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        
                        <!-- Thông tin cơ bản -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-person-circle text-success me-2"></i>Thông tin Ứng viên
                            </h5>
                        </div>

                        <!-- Vị trí ứng tuyển -->
                        <div class="mb-4">
                            <label for="position_id" class="form-label fw-semibold">
                                <i class="bi bi-briefcase text-success me-2"></i>Vị trí ứng tuyển
                                <span class="text-danger">*</span>
                            </label>
                            <select id="position_id" 
                                    name="position_id" 
                                    class="form-select form-select-lg rounded-3 shadow-sm" 
                                    required>
                                <option value="">-- Chọn Vị trí --</option>
                                <?php foreach ($positions as $position): ?>
                                    <option value="<?php echo e($position['id']); ?>" 
                                        <?php 
                                        $current_selection = $old['position_id'] ?? $selected_position_id;
                                        echo $current_selection == $position['id'] ? 'selected' : ''; 
                                        ?>>
                                        <?php echo e($position['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Chọn vị trí mà ứng viên nộp hồ sơ
                            </div>
                        </div>

                        <!-- Họ tên -->
                        <div class="mb-4">
                            <label for="full_name" class="form-label fw-semibold">
                                <i class="bi bi-person text-success me-2"></i>Họ tên
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="full_name" 
                                   name="full_name" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   value="<?php echo e($old['full_name'] ?? ''); ?>" 
                                   placeholder="Nhập họ và tên đầy đủ"
                                   required>
                        </div>

                        <!-- Email & Phone -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope text-success me-2"></i>Email
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control form-control-lg rounded-3 shadow-sm" 
                                       value="<?php echo e($old['email'] ?? ''); ?>" 
                                       placeholder="example@email.com"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="bi bi-telephone text-success me-2"></i>Số điện thoại
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       id="phone" 
                                       name="phone" 
                                       class="form-control form-control-lg rounded-3 shadow-sm" 
                                       value="<?php echo e($old['phone'] ?? ''); ?>" 
                                       placeholder="0912345678"
                                       required>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Upload CV -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-file-earmark-arrow-up text-primary me-2"></i>Tải lên CV
                            </h5>
                        </div>

                        <!-- File CV -->
                        <div class="mb-4">
                            <label for="cv_file" class="form-label fw-semibold">
                                <i class="bi bi-file-earmark-pdf text-primary me-2"></i>File CV
                                <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   id="cv_file" 
                                   name="cv_file" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   accept=".pdf,.doc,.docx"
                                   required>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Chấp nhận file: <strong>.pdf, .doc, .docx</strong> | Kích thước tối đa: <strong>5MB</strong>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Trạng thái & Ghi chú -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-sliders text-warning me-2"></i>Trạng thái & Ghi chú
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
                                <option value="pending" <?php echo ($old['status'] ?? 'pending') === 'pending' ? 'selected' : ''; ?>>
                                    ⏳ Chờ xử lý (Pending)
                                </option>
                                <option value="interviewing" <?php echo ($old['status'] ?? '') === 'interviewing' ? 'selected' : ''; ?>>
                                    💬 Đang phỏng vấn (Interviewing)
                                </option>
                                <option value="hired" <?php echo ($old['status'] ?? '') === 'hired' ? 'selected' : ''; ?>>
                                    ✅ Đã tuyển (Hired)
                                </option>
                                <option value="rejected" <?php echo ($old['status'] ?? '') === 'rejected' ? 'selected' : ''; ?>>
                                    ❌ Bị từ chối (Rejected)
                                </option>
                            </select>
                        </div>

                        <!-- Ghi chú -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold">
                                <i class="bi bi-pencil-square text-warning me-2"></i>Ghi chú (của HR)
                            </label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="4" 
                                      class="form-control rounded-3 shadow-sm"
                                      placeholder="Nhập ghi chú, đánh giá về ứng viên..."><?php echo e($old['notes'] ?? ''); ?></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Ghi chú nội bộ, không hiển thị cho ứng viên
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
                                    class="btn btn-lg btn-success rounded-pill px-5 shadow-sm" 
                                    style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none;">
                                <i class="bi bi-save-fill me-2"></i>Lưu ứng viên
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #11998e;
        box-shadow: 0 0 0 0.25rem rgba(17, 153, 142, 0.25);
    }
    
    .icon-box {
        transition: transform 0.3s ease;
    }
    
    .card:hover .icon-box {
        transform: scale(1.1) rotate(10deg);
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
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border-radius: 2px;
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;}
    }
</style>
<?php 
// $recruiter được truyền từ Controller
$errors = $_SESSION['_flash']['errors'] ?? [];
unset($_SESSION['_flash']['errors']); 
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                
                <!-- Card Header với gradient -->
                <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="bi bi-pencil-square text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 text-white fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-white-50 small">
                                <i class="bi bi-building me-1"></i>
                                Đang chỉnh sửa: <strong><?php echo e($recruiter['company_name']); ?></strong>
                            </p>
                        </div>
                        <div class="badge bg-white text-dark rounded-pill px-3 py-2">
                            ID: #<?php echo e($recruiter['id']); ?>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/recruiters/update" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($recruiter['id']); ?>">

                        <!-- Thông tin công ty -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-building-fill text-warning me-2"></i>Thông tin Công ty
                            </h5>
                        </div>

                        <!-- Tên công ty -->
                        <div class="mb-4">
                            <label for="company_name" class="form-label fw-semibold">
                                <i class="bi bi-building text-warning me-2"></i>Tên công ty
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="company_name" 
                                   name="company_name" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   value="<?php echo e($recruiter['company_name']); ?>" 
                                   placeholder="Nhập tên công ty đầy đủ"
                                   required>
                        </div>

                        <!-- Địa chỉ -->
                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold">
                                <i class="bi bi-geo-alt text-warning me-2"></i>Địa chỉ
                            </label>
                            <textarea id="address" 
                                      name="address" 
                                      rows="3" 
                                      class="form-control rounded-3 shadow-sm"
                                      placeholder="Nhập địa chỉ công ty"><?php echo e($recruiter['address']); ?></textarea>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Thông tin liên hệ -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-person-lines-fill text-primary me-2"></i>Thông tin Liên hệ
                            </h5>
                        </div>

                        <!-- Người liên hệ -->
                        <div class="mb-4">
                            <label for="contact_person" class="form-label fw-semibold">
                                <i class="bi bi-person text-primary me-2"></i>Người liên hệ
                            </label>
                            <input type="text" 
                                   id="contact_person" 
                                   name="contact_person" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   value="<?php echo e($recruiter['contact_person']); ?>"
                                   placeholder="Nhập tên người đại diện">
                        </div>

                        <!-- Email & Phone -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope text-primary me-2"></i>Email
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control form-control-lg rounded-3 shadow-sm" 
                                       value="<?php echo e($recruiter['email']); ?>"
                                       placeholder="contact@company.com">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="bi bi-telephone text-primary me-2"></i>Số điện thoại
                                </label>
                                <input type="text" 
                                       id="phone" 
                                       name="phone" 
                                       class="form-control form-control-lg rounded-3 shadow-sm" 
                                       value="<?php echo e($recruiter['phone']); ?>"
                                       placeholder="0912345678">
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/recruiters" 
                               class="btn btn-lg btn-light border rounded-pill px-4 shadow-sm">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm" 
                                    style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border: none;">
                                <i class="bi bi-save-fill me-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span>
                            <i class="bi bi-calendar3 me-1"></i>
                            Tạo lúc: <strong><?php echo date('d/m/Y H:i', strtotime($recruiter['created_at'])); ?></strong>
                        </span>
                        <span>
                            <i class="bi bi-building me-1"></i>
                            ID: <strong>#<?php echo e($recruiter['id']); ?></strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
unset($_SESSION['_flash']['errors']); 
?>

<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden recruiter-card">
                
                <div class="card-header border-0 py-4 bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-building-gear fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 opacity-75 small">
                                Đang chỉnh sửa: <strong><?php echo e($recruiter['company_name']); ?></strong>
                            </p>
                        </div>
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold shadow-sm d-none d-md-block">
                            ID: #<?php echo e($recruiter['id']); ?>
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Đã xảy ra lỗi!</h6>
                                    <ul class="mb-0 ps-3 small">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo BASE_URL; ?>/recruiters/update" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        <input type="hidden" name="id" value="<?php echo e($recruiter['id']); ?>">

                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase text-secondary text-xs opacity-75 mb-3 ls-1">
                                <i class="bi bi-info-circle me-1"></i> Thông tin công ty
                            </h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-medium">Tên công ty <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($recruiter['company_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Email liên hệ <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($recruiter['email']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($recruiter['phone']); ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium">Địa chỉ</label>
                                    <input type="text" name="address" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($recruiter['address']); ?>">
                                </div>
                            </div>
                        </div>

                        <hr class="border-secondary border-opacity-10 my-4">

                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase text-secondary text-xs opacity-75 mb-3 ls-1">
                                <i class="bi bi-person-badge me-1"></i> Người đại diện
                            </h6>
                            <div class="col-12">
                                <label class="form-label fw-medium">Tên người liên hệ <span class="text-danger">*</span></label>
                                <input type="text" name="contact_person" class="form-control form-control-lg bg-light border-0" 
                                       value="<?php echo e($recruiter['contact_person']); ?>" required>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-5">
                            <a href="<?php echo BASE_URL; ?>/recruiters" class="btn btn-light btn-lg px-4 rounded-pill border-0 text-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm fw-medium">
                                <i class="bi bi-check2-circle me-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span>
                            <i class="bi bi-calendar3 me-1"></i>
                            Tạo lúc: <strong><?php echo date('d/m/Y H:i', strtotime($recruiter['created_at'])); ?></strong>
                        </span>
                        <span class="d-md-none">
                            <i class="bi bi-hash"></i>
                            ID: <strong><?php echo e($recruiter['id']); ?></strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    
    /* Input Focus Styles */
    .form-control:focus {
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15); /* Shadow xanh dương */
        border: 1px solid #86b7fe !important;
    }

    /* Dark Mode Overrides */
    [data-theme="dark"] .recruiter-card {
        background-color: #1e1e1e !important;
        border: 1px solid #333 !important;
    }
    
    [data-theme="dark"] .form-control {
        background-color: #2b2b2b !important;
        color: #fff !important;
        border-color: #444 !important;
    }
    
    [data-theme="dark"] .form-control:focus {
        background-color: #333 !important;
        border-color: #0d6efd !important;
    }
    
    [data-theme="dark"] .text-secondary {
        color: #a0a0a0 !important;
    }
    
    [data-theme="dark"] .card-footer {
        background-color: #1e1e1e !important;
        border-top: 1px solid #333 !important;
    }
</style>
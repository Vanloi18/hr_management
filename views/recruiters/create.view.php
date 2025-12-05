<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
$old = $_SESSION['_flash']['old'] ?? [];
unset($_SESSION['_flash']['errors'], $_SESSION['_flash']['old']);
?>

<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden recruiter-card">
                
                <div class="card-header border-0 py-4 bg-success text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-building-add fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 opacity-75 small">Nhập thông tin công ty và người liên hệ</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Vui lòng kiểm tra lại!</h6>
                                    <ul class="mb-0 ps-3 small">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo BASE_URL; ?>/recruiters" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase text-secondary text-xs opacity-75 mb-3 ls-1">
                                <i class="bi bi-info-circle me-1"></i> Thông tin công ty
                            </h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-medium">Tên công ty <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($old['company_name'] ?? ''); ?>" placeholder="Nhập tên công ty" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Email liên hệ <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($old['email'] ?? ''); ?>" placeholder="contact@company.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($old['phone'] ?? ''); ?>" placeholder="09xxxxxxx" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium">Địa chỉ</label>
                                    <input type="text" name="address" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($old['address'] ?? ''); ?>" placeholder="Số nhà, đường, quận/huyện...">
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
                                <input type="text" name="contact_name" class="form-control form-control-lg bg-light border-0" 
                                       value="<?php echo e($old['contact_name'] ?? ''); ?>" placeholder="VD: Nguyễn Văn A" required>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-5">
                            <a href="<?php echo BASE_URL; ?>/recruiters" class="btn btn-light btn-lg px-4 rounded-pill border-0 text-secondary">Quay lại</a>
                            <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow-sm fw-medium">
                                <i class="bi bi-save me-2"></i>Lưu thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .form-control:focus {
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.15); /* Shadow xanh lá */
        border: 1px solid #20c997 !important;
    }
    
    /* Dark Mode */
    [data-theme="dark"] .recruiter-card { background-color: #1e1e1e !important; border: 1px solid #333 !important; }
    [data-theme="dark"] .form-control { background-color: #2b2b2b !important; color: #fff !important; border-color: #444 !important; }
    [data-theme="dark"] .form-control:focus { background-color: #333 !important; border-color: #198754 !important; }
    [data-theme="dark"] .text-secondary { color: #a0a0a0 !important; }
</style>
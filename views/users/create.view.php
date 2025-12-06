<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
$old = $_SESSION['_flash']['old'] ?? [];
unset($_SESSION['_flash']['errors'], $_SESSION['_flash']['old']);
?>

<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden user-card">
                
                <div class="card-header border-0 py-4 bg-success text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-person-plus-fill fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 opacity-75 small">Tạo tài khoản mới cho nhân viên</p>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/users" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase text-secondary text-xs opacity-75 mb-3 ls-1">
                                <i class="bi bi-info-circle me-1"></i> Thông tin tài khoản
                            </h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-medium">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($old['full_name'] ?? ''); ?>" placeholder="VD: Nguyễn Văn A" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($old['email'] ?? ''); ?>" placeholder="email@company.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Vai trò <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select form-select-lg bg-light border-0 cursor-pointer">
                                        <option value="hr" <?php echo ($old['role'] ?? '') === 'hr' ? 'selected' : ''; ?>>Nhân viên HR</option>
                                        <option value="admin" <?php echo ($old['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Quản trị viên</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="border-secondary border-opacity-10 my-4">

                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase text-secondary text-xs opacity-75 mb-3 ls-1">
                                <i class="bi bi-key me-1"></i> Thiết lập mật khẩu
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" 
                                               class="form-control form-control-lg bg-light border-0 border-end-0" required>
                                        <button class="btn btn-light border-0 text-secondary bg-light" type="button" onclick="togglePassword('password')">
                                            <i class="bi bi-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text small mt-1">Tối thiểu 6 ký tự</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="confirm_password" name="confirm_password" 
                                               class="form-control form-control-lg bg-light border-0 border-end-0" required>
                                        <button class="btn btn-light border-0 text-secondary bg-light" type="button" onclick="togglePassword('confirm_password')">
                                            <i class="bi bi-eye" id="confirm_password-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-5">
                            <a href="<?php echo BASE_URL; ?>/users" class="btn btn-light btn-lg px-4 rounded-pill border-0 text-secondary">Quay lại</a>
                            <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow-sm fw-medium">
                                <i class="bi bi-plus-lg me-2"></i>Tạo mới
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
    .form-control:focus, .form-select:focus { background-color: #fff; box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.15); border: 1px solid #20c997 !important; }
    
    /* Dark Mode */
    [data-theme="dark"] .user-card { background-color: #1e1e1e !important; border: 1px solid #333 !important; }
    [data-theme="dark"] .form-control, [data-theme="dark"] .form-select, [data-theme="dark"] .input-group .btn-light { background-color: #2b2b2b !important; color: #fff !important; border-color: #444 !important; }
    [data-theme="dark"] .form-control:focus { background-color: #333 !important; border-color: #198754 !important; }
    [data-theme="dark"] .text-secondary { color: #a0a0a0 !important; }
</style>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = document.getElementById(id + '-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
unset($_SESSION['_flash']['errors']); 
?>

<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden edit-user-card">

                <div class="card-header border-0 py-4 bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-person-gear fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 opacity-75 small">
                                Đang chỉnh sửa hồ sơ: <strong><?php echo e($user['full_name']); ?></strong>
                            </p>
                        </div>
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold shadow-sm">
                            ID: #<?php echo e($user['id']); ?>
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading fw-bold mb-1">Đã xảy ra lỗi!</h6>
                                    <ul class="mb-0 ps-3 small">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo BASE_URL; ?>/users/update" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($user['id']); ?>">

                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase text-secondary text-xs opacity-75 mb-3 ls-1">
                                <i class="bi bi-info-circle me-1"></i> Thông tin cơ bản
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="full_name" class="form-label fw-medium">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" id="full_name" name="full_name" 
                                           class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($user['full_name']); ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" 
                                           class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($user['email']); ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="role" class="form-label fw-medium">Vai trò <span class="text-danger">*</span></label>
                                    <select id="role" name="role" class="form-select form-select-lg bg-light border-0 cursor-pointer">
                                        <option value="hr" <?php echo $user['role'] === 'hr' ? 'selected' : ''; ?>>Nhân viên HR</option>
                                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Quản trị viên (Admin)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="border-secondary border-opacity-10 my-4">

                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase text-secondary text-xs opacity-75 mb-3 ls-1">
                                <i class="bi bi-shield-lock me-1"></i> Bảo mật
                            </h6>
                            
                            <div class="alert alert-light border-0 rounded-3 d-flex align-items-center mb-3">
                                <i class="bi bi-info-circle text-info me-3 fs-5"></i>
                                <span class="small text-muted">Chỉ nhập mật khẩu bên dưới nếu bạn muốn thay đổi. Nếu không, hãy để trống.</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-medium">Mật khẩu mới</label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" 
                                               class="form-control form-control-lg bg-light border-0 border-end-0" 
                                               placeholder="••••••">
                                        <button class="btn btn-light border-0 text-secondary bg-light" type="button" onclick="togglePassword('password')">
                                            <i class="bi bi-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label fw-medium">Xác nhận mật khẩu</label>
                                    <div class="input-group">
                                        <input type="password" id="confirm_password" name="confirm_password" 
                                               class="form-control form-control-lg bg-light border-0 border-end-0" 
                                               placeholder="••••••">
                                        <button class="btn btn-light border-0 text-secondary bg-light" type="button" onclick="togglePassword('confirm_password')">
                                            <i class="bi bi-eye" id="confirm_password-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-5">
                            <a href="<?php echo BASE_URL; ?>/users" class="btn btn-light btn-lg px-4 rounded-pill border-0 text-secondary">
                                Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm fw-medium">
                                <i class="bi bi-check2-circle me-2"></i>Cập nhật
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Letter Spacing cho tiêu đề nhỏ */
    .ls-1 { letter-spacing: 1px; }
    
    /* Input Focus Styles */
    .form-control:focus, .form-select:focus {
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
        border: 1px solid #86b7fe !important;
    }

    /* Dark Mode Overrides cho trang Edit User */
    [data-theme="dark"] .edit-user-card {
        background-color: #1e1e1e !important;
        border: 1px solid #333 !important;
    }
    
    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-select,
    [data-theme="dark"] .input-group .btn-light {
        background-color: #2b2b2b !important; /* Input nền tối */
        color: #fff !important;
        border-color: #444 !important;
    }
    
    [data-theme="dark"] .form-control:focus {
        border-color: #0d6efd !important;
        background-color: #333 !important;
    }
    
    [data-theme="dark"] .text-secondary {
        color: #a0a0a0 !important;
    }
    
    [data-theme="dark"] .alert-light {
        background-color: #2c2c2c;
        color: #ddd;
    }
</style>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
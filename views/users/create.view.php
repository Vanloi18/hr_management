<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
$old = $_SESSION['_flash']['old'] ?? [];
unset($_SESSION['_flash']['errors'], $_SESSION['_flash']['old']);
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                
                <!-- Card Header với gradient -->
                <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="bi bi-person-plus-fill text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-white fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-white-50 small">Điền thông tin để tạo tài khoản mới</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                
                    <!-- Hiển thị lỗi với style đẹp -->
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/users" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        
                        <!-- Họ tên -->
                        <div class="mb-4">
                            <label for="full_name" class="form-label fw-semibold">
                                <i class="bi bi-person text-primary me-2"></i>Họ tên
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   id="full_name" 
                                   name="full_name" 
                                   value="<?php echo e($old['full_name'] ?? ''); ?>" 
                                   placeholder="Nhập họ và tên đầy đủ"
                                   required>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Ví dụ: Nguyễn Văn A
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bi bi-envelope text-primary me-2"></i>Email
                                <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo e($old['email'] ?? ''); ?>" 
                                   placeholder="example@company.com"
                                   required>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Email sẽ dùng để đăng nhập
                            </div>
                        </div>

                        <!-- Vai trò -->
                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold">
                                <i class="bi bi-shield-check text-primary me-2"></i>Vai trò
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg rounded-3 shadow-sm" id="role" name="role">
                                <option value="hr" <?php echo ($old['role'] ?? 'hr') === 'hr' ? 'selected' : ''; ?>>
                                    <i class="bi bi-person-badge"></i> Nhân viên HR (HR)
                                </option>
                                <option value="admin" <?php echo ($old['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>
                                    <i class="bi bi-star"></i> Quản trị viên (Admin)
                                </option>
                            </select>
                        </div>

                        <!-- Mật khẩu -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock text-primary me-2"></i>Mật khẩu
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group shadow-sm">
                                    <input type="password" 
                                           class="form-control form-control-lg rounded-start-3 border-end-0" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Nhập mật khẩu"
                                           required>
                                    <button class="btn btn-outline-secondary rounded-end-3" 
                                            type="button" 
                                            onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill text-primary me-2"></i>Xác nhận Mật khẩu
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group shadow-sm">
                                    <input type="password" 
                                           class="form-control form-control-lg rounded-start-3 border-end-0" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           placeholder="Nhập lại mật khẩu"
                                           required>
                                    <button class="btn btn-outline-secondary rounded-end-3" 
                                            type="button" 
                                            onclick="togglePassword('confirm_password')">
                                        <i class="bi bi-eye" id="confirm_password-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/users" class="btn btn-lg btn-light border rounded-pill px-4 shadow-sm">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                <i class="bi bi-save-fill me-2"></i>Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles cho form */
    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    
    .icon-box {
        transition: transform 0.3s ease;
    }
    
    .card:hover .icon-box {
        transform: scale(1.1);
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
    }
    
    .input-group-text {
        background: white;
    }
    
    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
    }
</style>

<script>
    // Toggle hiển thị mật khẩu
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
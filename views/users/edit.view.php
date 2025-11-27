<?php 
// $title và $user được set từ Controller
$errors = $_SESSION['_flash']['errors'] ?? [];
unset($_SESSION['_flash']['errors']); 
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                
                <!-- Card Header với gradient -->
                <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="bi bi-pencil-square text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 text-white fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-white-50 small">
                                <i class="bi bi-person-circle me-1"></i>
                                Đang chỉnh sửa: <strong><?php echo e($user['full_name']); ?></strong>
                            </p>
                        </div>
                        <div class="badge bg-white text-dark rounded-pill px-3 py-2">
                            ID: #<?php echo e($user['id']); ?>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/users/update" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($user['id']); ?>">

                        <!-- Thông tin cơ bản -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-info-circle-fill me-2"></i>Thông tin cơ bản
                            </h5>
                        </div>

                        <!-- Họ tên -->
                        <div class="mb-4">
                            <label for="full_name" class="form-label fw-semibold">
                                <i class="bi bi-person text-danger me-2"></i>Họ tên
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="full_name" 
                                   name="full_name" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   value="<?php echo e($user['full_name']); ?>" 
                                   placeholder="Nhập họ và tên đầy đủ"
                                   required>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bi bi-envelope text-danger me-2"></i>Email
                                <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   value="<?php echo e($user['email']); ?>" 
                                   placeholder="example@company.com"
                                   required>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Email sẽ dùng để đăng nhập
                            </div>
                        </div>

                        <!-- Vai trò -->
                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold">
                                <i class="bi bi-shield-check text-danger me-2"></i>Vai trò
                                <span class="text-danger">*</span>
                            </label>
                            <select id="role" name="role" class="form-select form-select-lg rounded-3 shadow-sm">
                                <option value="hr" <?php echo $user['role'] === 'hr' ? 'selected' : ''; ?>>
                                    Nhân viên HR (HR)
                                </option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>
                                    Quản trị viên (Admin)
                                </option>
                            </select>
                        </div>

                        <!-- Divider cho phần mật khẩu -->
                        <hr class="my-4">
                        
                        <!-- Thay đổi mật khẩu -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-2">
                                <i class="bi bi-key-fill me-2"></i>Thay đổi mật khẩu
                            </h5>
                            <div class="alert alert-info border-0 rounded-3 shadow-sm d-flex align-items-center">
                                <i class="bi bi-info-circle-fill me-3 fs-5"></i>
                                <span>Bỏ trống nếu không muốn thay đổi mật khẩu</span>
                            </div>
                        </div>

                        <!-- Mật khẩu mới -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock text-warning me-2"></i>Mật khẩu mới
                                </label>
                                <div class="input-group shadow-sm">
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="form-control form-control-lg rounded-start-3 border-end-0" 
                                           placeholder="Nhập mật khẩu mới">
                                    <button class="btn btn-outline-secondary rounded-end-3" 
                                            type="button" 
                                            onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-shield-lock me-1"></i>Tối thiểu 6 ký tự
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill text-warning me-2"></i>Xác nhận Mật khẩu
                                </label>
                                <div class="input-group shadow-sm">
                                    <input type="password" 
                                           class="form-control form-control-lg rounded-start-3 border-end-0" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           placeholder="Nhập lại mật khẩu mới">
                                    <button class="btn btn-outline-secondary rounded-end-3" 
                                            type="button" 
                                            onclick="togglePassword('confirm_password')">
                                        <i class="bi bi-eye" id="confirm_password-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end mt-4">
                            <a href="<?php echo BASE_URL; ?>/users" 
                               class="btn btn-lg btn-light border rounded-pill px-4 shadow-sm">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm" 
                                    style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none;">
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
                            Tạo lúc: <strong><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></strong>
                        </span>
                        <span>
                            <i class="bi bi-clock-history me-1"></i>
                            ID: <strong>#<?php echo e($user['id']); ?></strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles cho form */
    .form-control:focus,
    .form-select:focus {
        border-color: #f5576c;
        box-shadow: 0 0 0 0.25rem rgba(245, 87, 108, 0.25);
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
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 2px;
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
        
        .card-header .badge {
            display: none;
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
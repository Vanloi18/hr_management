<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
$old = $_SESSION['_flash']['old'] ?? [];
unset($_SESSION['_flash']['errors'], $_SESSION['_flash']['old']);
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                
                <!-- Card Header với gradient -->
                <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="bi bi-building-add text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-white fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-white-50 small">Nhập thông tin công ty và người liên hệ</p>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/recruiters" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        
                        <!-- Thông tin công ty -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-building-fill text-info me-2"></i>Thông tin Công ty
                            </h5>
                        </div>

                        <!-- Tên công ty -->
                        <div class="mb-4">
                            <label for="company_name" class="form-label fw-semibold">
                                <i class="bi bi-building text-info me-2"></i>Tên công ty
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="company_name" 
                                   name="company_name" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   value="<?php echo e($old['company_name'] ?? ''); ?>" 
                                   placeholder="Nhập tên công ty đầy đủ"
                                   required>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Ví dụ: Công ty TNHH ABC
                            </div>
                        </div>

                        <!-- Địa chỉ -->
                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold">
                                <i class="bi bi-geo-alt text-info me-2"></i>Địa chỉ
                            </label>
                            <textarea id="address" 
                                      name="address" 
                                      rows="3" 
                                      class="form-control rounded-3 shadow-sm"
                                      placeholder="Nhập địa chỉ công ty (số nhà, đường, quận/huyện, thành phố)"><?php echo e($old['address'] ?? ''); ?></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Địa chỉ chi tiết giúp liên hệ dễ dàng hơn
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Thông tin liên hệ -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-muted mb-3">
                                <i class="bi bi-person-lines-fill text-success me-2"></i>Thông tin Liên hệ
                            </h5>
                        </div>

                        <!-- Người liên hệ -->
                        <div class="mb-4">
                            <label for="contact_person" class="form-label fw-semibold">
                                <i class="bi bi-person text-success me-2"></i>Người liên hệ
                            </label>
                            <input type="text" 
                                   id="contact_person" 
                                   name="contact_person" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   value="<?php echo e($old['contact_person'] ?? ''); ?>"
                                   placeholder="Nhập tên người đại diện">
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Tên người phụ trách tuyển dụng
                            </div>
                        </div>

                        <!-- Email & Phone -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope text-success me-2"></i>Email
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control form-control-lg rounded-3 shadow-sm" 
                                       value="<?php echo e($old['email'] ?? ''); ?>"
                                       placeholder="contact@company.com">
                                <div class="form-text">
                                    <i class="bi bi-shield-lock me-1"></i>Email liên hệ
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="bi bi-telephone text-success me-2"></i>Số điện thoại
                                </label>
                                <input type="text" 
                                       id="phone" 
                                       name="phone" 
                                       class="form-control form-control-lg rounded-3 shadow-sm" 
                                       value="<?php echo e($old['phone'] ?? ''); ?>"
                                       placeholder="0912345678">
                                <div class="form-text">
                                    <i class="bi bi-telephone-fill me-1"></i>SĐT liên hệ
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/recruiters" class="btn btn-lg btn-light border rounded-pill px-4 shadow-sm">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none;">
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
    /* Custom styles */
    .form-control:focus,
    .form-select:focus {
        border-color: #4facfe;
        box-shadow: 0 0 0 0.25rem rgba(79, 172, 254, 0.25);
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
    }
</style>
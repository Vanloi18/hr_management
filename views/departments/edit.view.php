<?php // $department được truyền từ Controller ?>
<?php $errors = $_SESSION['_flash']['errors'] ?? []; ?>
<?php unset($_SESSION['_flash']['errors']); ?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <!-- Card Header -->
                <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-50 rounded-3 p-3 me-3">
                            <i class="bi bi-pencil-square text-dark" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 text-dark fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-dark text-opacity-75 small">
                                <i class="bi bi-building me-1"></i>
                                Đang chỉnh sửa: <strong><?php echo e($department['name']); ?></strong>
                            </p>
                        </div>
                        <div class="badge bg-white text-dark rounded-pill px-3 py-2">
                            ID: #<?php echo e($department['id']); ?>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">

                    <!-- Alert lỗi -->
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

                    <!-- Warning Box -->
                    <div class="alert alert-warning border-0 rounded-3 shadow-sm mb-4">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                            <div>
                                <strong>Lưu ý:</strong> Nếu phòng ban này đang có nhân viên, việc thay đổi tên sẽ ảnh hưởng đến tất cả nhân viên thuộc phòng ban.
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="<?php echo BASE_URL; ?>/departments/update" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($department['id']); ?>">

                        <!-- Tên Phòng ban -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bi bi-building text-warning me-2"></i>Tên Phòng ban
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   id="name" 
                                   name="name" 
                                   value="<?php echo e($department['name']); ?>" 
                                   placeholder="Nhập tên phòng ban"
                                   required>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Tên phòng ban nên rõ ràng và chính xác
                            </div>
                        </div>
                        
                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/departments" 
                               class="btn btn-lg btn-light border rounded-pill px-4 shadow-sm">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm" 
                                    style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border: none; color: #333;">
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
                            Tạo lúc: <strong><?php echo date('d/m/Y H:i', strtotime($department['created_at'])); ?></strong>
                        </span>
                        <span>
                            <i class="bi bi-building me-1"></i>
                            ID: <strong>#<?php echo e($department['id']); ?></strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #fa709a;
        box-shadow: 0 0 0 0.25rem rgba(250, 112, 154, 0.25);
    }
    
    .icon-box {
        transition: transform 0.3s ease;
    }
    
    .card:hover .icon-box {
        transform: scale(1.1) rotate(-5deg);
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .card-header .badge {
            display: none;
        }
    }
</style>
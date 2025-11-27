<?php
$errors = $_SESSION['_flash']['errors'] ?? [];
$old = $_SESSION['_flash']['old'] ?? [];
unset($_SESSION['_flash']['errors'], $_SESSION['_flash']['old']);
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <!-- Card Header với gradient -->
                <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="bi bi-plus-circle-fill text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-white fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-white-50 small">Tạo phòng ban mới cho tổ chức</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                
                    <!-- AJAX Error Alert -->
                    <div id="ajax-general-error" class="alert alert-danger border-0 rounded-3 shadow-sm mb-4" style="display: none;">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                            <div class="flex-grow-1" id="ajax-error-content"></div>
                        </div>
                    </div>

                    <form method="POST" action="<?php echo BASE_URL; ?>/departments" id="form-add-department" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        
                        <!-- Info Box -->
                        <div class="alert alert-info border-0 rounded-3 shadow-sm mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-lightbulb-fill me-3 fs-4"></i>
                                <div>
                                    <strong>Gợi ý:</strong> Tên phòng ban nên rõ ràng và dễ hiểu như: 
                                    <span class="badge bg-white text-primary me-1">Kinh doanh</span>
                                    <span class="badge bg-white text-primary me-1">Kế toán</span>
                                    <span class="badge bg-white text-primary me-1">Nhân sự</span>
                                    <span class="badge bg-white text-primary">IT</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tên Phòng ban -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bi bi-building text-primary me-2"></i>Tên Phòng ban
                                <span class="text-danger">*</span>
                            </label>
                            
                            <input type="text" 
                                   class="form-control form-control-lg rounded-3 shadow-sm <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                                   id="name" 
                                   name="name" 
                                   value="<?php echo e($old['name'] ?? ''); ?>" 
                                   placeholder="Ví dụ: Phòng Kinh doanh, Phòng Kỹ thuật..."
                                   aria-describedby="error-name"
                                   required>
                            
                            <div id="error-name" class="invalid-feedback">
                                <?php echo e($errors['name'] ?? ''); ?>
                            </div>
                            
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Tên phòng ban phải rõ ràng và chính xác
                            </div>
                        </div>
                        
                        <!-- Divider -->
                        <hr class="my-4">
                        
                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/departments" class="btn btn-lg btn-light border rounded-pill px-4 shadow-sm">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm" 
                                    id="btn-submit-ajax"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                <i class="bi bi-save-fill me-2"></i>Lưu
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light border-0 py-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Sau khi tạo, phòng ban có thể được gán cho nhân viên
                    </small>
                </div>
            </div>

            <!-- Examples Card -->
            <div class="card shadow border-0 rounded-4 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-stars text-warning me-2"></i>Các phòng ban phổ biến:
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill example-badge" style="font-size: 0.9rem; cursor: pointer;">
                            <i class="bi bi-cash-stack me-1"></i>Phòng Kế toán
                        </span>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill example-badge" style="font-size: 0.9rem; cursor: pointer;">
                            <i class="bi bi-graph-up me-1"></i>Phòng Kinh doanh
                        </span>
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill example-badge" style="font-size: 0.9rem; cursor: pointer;">
                            <i class="bi bi-megaphone me-1"></i>Phòng Marketing
                        </span>
                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill example-badge" style="font-size: 0.9rem; cursor: pointer;">
                            <i class="bi bi-laptop me-1"></i>Phòng IT
                        </span>
                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill example-badge" style="font-size: 0.9rem; cursor: pointer;">
                            <i class="bi bi-people me-1"></i>Phòng Nhân sự
                        </span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill example-badge" style="font-size: 0.9rem; cursor: pointer;">
                            <i class="bi bi-building me-1"></i>Phòng Hành chính
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
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
    
    .example-badge {
        transition: all 0.3s ease;
    }
    
    .example-badge:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
    }
</style>

<script>
    // Auto-fill example on click
    document.querySelectorAll('.example-badge').forEach(badge => {
        badge.addEventListener('click', function() {
            const text = this.textContent.trim();
            const nameInput = document.getElementById('name');
            if (nameInput) {
                nameInput.value = text;
                nameInput.focus();
                
                // Visual feedback
                this.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            }
        });
    });
</script>
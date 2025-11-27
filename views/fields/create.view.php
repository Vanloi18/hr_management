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
                            <i class="bi bi-tag-fill text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-white fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-white-50 small">Tạo lĩnh vực mới để phân loại công việc</p>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/fields" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        
                        <!-- Info Box -->
                        <div class="alert alert-info border-0 rounded-3 shadow-sm mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-lightbulb-fill me-3 fs-4"></i>
                                <div>
                                    <strong>Lưu ý:</strong> Lĩnh vực giúp phân loại các vị trí tuyển dụng theo ngành nghề như: 
                                    <span class="badge bg-white text-primary me-1">IT</span>
                                    <span class="badge bg-white text-primary me-1">Marketing</span>
                                    <span class="badge bg-white text-primary me-1">Kế toán</span>
                                    <span class="badge bg-white text-primary">Nhân sự</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tên lĩnh vực -->
                        <div class="mb-4">
                            <label for="field_name" class="form-label fw-semibold">
                                <i class="bi bi-tag text-primary me-2"></i>Tên lĩnh vực
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="field_name" 
                                   name="field_name" 
                                   class="form-control form-control-lg rounded-3 shadow-sm" 
                                   value="<?php echo e($old['field_name'] ?? ''); ?>" 
                                   placeholder="Ví dụ: Công nghệ thông tin, Kinh doanh, Marketing..."
                                   required>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Nhập tên lĩnh vực/ngành nghề rõ ràng, dễ hiểu
                            </div>
                        </div>
                        
                        <!-- Mô tả -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="bi bi-file-text text-primary me-2"></i>Mô tả
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      class="form-control rounded-3 shadow-sm"
                                      placeholder="Nhập mô tả chi tiết về lĩnh vực này (không bắt buộc)..."><?php echo e($old['description'] ?? ''); ?></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Mô tả giúp phân biệt các lĩnh vực tương tự
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/fields" 
                               class="btn btn-lg btn-light border rounded-pill px-4 shadow-sm">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm" 
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                <i class="bi bi-save-fill me-2"></i>Lưu
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Preview Card (Optional) -->
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex align-items-center text-muted small">
                        <i class="bi bi-info-circle me-2"></i>
                        <span>Sau khi tạo, lĩnh vực này sẽ được sử dụng khi đăng tin tuyển dụng</span>
                    </div>
                </div>
            </div>

            <!-- Examples Card -->
            <div class="card shadow border-0 rounded-4 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-stars text-warning me-2"></i>Gợi ý các lĩnh vực phổ biến:
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill" style="font-size: 0.9rem;">
                            <i class="bi bi-laptop me-1"></i>Công nghệ thông tin
                        </span>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill" style="font-size: 0.9rem;">
                            <i class="bi bi-graph-up me-1"></i>Kinh doanh
                        </span>
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill" style="font-size: 0.9rem;">
                            <i class="bi bi-megaphone me-1"></i>Marketing
                        </span>
                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill" style="font-size: 0.9rem;">
                            <i class="bi bi-calculator me-1"></i>Kế toán - Tài chính
                        </span>
                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill" style="font-size: 0.9rem;">
                            <i class="bi bi-people me-1"></i>Nhân sự
                        </span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill" style="font-size: 0.9rem;">
                            <i class="bi bi-truck me-1"></i>Logistics
                        </span>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill" style="font-size: 0.9rem;">
                            <i class="bi bi-building me-1"></i>Xây dựng
                        </span>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill" style="font-size: 0.9rem;">
                            <i class="bi bi-heart-pulse me-1"></i>Y tế - Sức khỏe
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles */
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
    
    .badge {
        transition: all 0.3s ease;
        cursor: default;
    }
    
    .badge:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
    }
</style>

<script>
    // Auto-fill example on click (Optional enhancement)
    document.querySelectorAll('.badge').forEach(badge => {
        badge.style.cursor = 'pointer';
        badge.addEventListener('click', function() {
            const text = this.textContent.trim();
            const fieldNameInput = document.getElementById('field_name');
            if (fieldNameInput && !fieldNameInput.value) {
                fieldNameInput.value = text;
                fieldNameInput.focus();
                
                // Visual feedback
                this.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            }
        });
    });
</script>
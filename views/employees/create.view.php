<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
$old = $_SESSION['_flash']['old'] ?? [];
unset($_SESSION['_flash']['errors'], $_SESSION['_flash']['old']);
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <!-- Card Header với gradient -->
                <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="bi bi-person-badge-fill text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-white fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-white-50 small">Nhập thông tin hồ sơ nhân viên mới</p>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/employees" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>

                        <div class="row g-4">

                            <!-- LEFT COLUMN -->
                            <div class="col-lg-8">

                                <!-- Thông tin cơ bản -->
                                <div class="section-title mb-4">
                                    <h5 class="fw-bold text-muted mb-3">
                                        <i class="bi bi-person-circle text-primary me-2"></i>Thông tin Cơ bản
                                    </h5>
                                </div>

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
                                </div>

                                <!-- Email & Phone -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
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
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label fw-semibold">
                                            <i class="bi bi-telephone text-primary me-2"></i>Số điện thoại
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg rounded-3 shadow-sm"
                                               id="phone" 
                                               name="phone"
                                               value="<?php echo e($old['phone'] ?? ''); ?>"
                                               placeholder="0912345678">
                                    </div>
                                </div>

                                <!-- Divider -->
                                <hr class="my-4">

                                <!-- Thông tin công việc -->
                                <div class="section-title mb-4">
                                    <h5 class="fw-bold text-muted mb-3">
                                        <i class="bi bi-briefcase-fill text-success me-2"></i>Thông tin Công việc
                                    </h5>
                                </div>

                                <!-- Phòng ban & Chức vụ -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="department_id" class="form-label fw-semibold">
                                            <i class="bi bi-building text-success me-2"></i>Phòng ban
                                        </label>
                                        <select class="form-select form-select-lg rounded-3 shadow-sm" 
                                                id="department_id" 
                                                name="department_id">
                                            <option value="">-- Không thuộc phòng ban --</option>
                                            <?php foreach ($departments as $department): ?>
                                                <option value="<?php echo e($department['id']); ?>"
                                                    <?php echo ($old['department_id'] ?? '') == $department['id'] ? 'selected' : ''; ?>>
                                                    <?php echo e($department['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="job_title" class="form-label fw-semibold">
                                            <i class="bi bi-briefcase text-success me-2"></i>Chức vụ
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg rounded-3 shadow-sm"
                                               id="job_title" 
                                               name="job_title"
                                               value="<?php echo e($old['job_title'] ?? ''); ?>" 
                                               placeholder="Ví dụ: Nhân viên, Trưởng phòng..."
                                               required>
                                    </div>
                                </div>

                                <!-- Ngày vào & Trạng thái -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="start_date" class="form-label fw-semibold">
                                            <i class="bi bi-calendar-check text-success me-2"></i>Ngày vào công ty
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" 
                                               class="form-control form-control-lg rounded-3 shadow-sm"
                                               id="start_date" 
                                               name="start_date"
                                               value="<?php echo e($old['start_date'] ?? ''); ?>" 
                                               required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="status" class="form-label fw-semibold">
                                            <i class="bi bi-circle-fill text-success me-2"></i>Trạng thái
                                        </label>
                                        <select class="form-select form-select-lg rounded-3 shadow-sm" 
                                                id="status" 
                                                name="status">
                                            <option value="probation" <?php echo ($old['status'] ?? 'probation') === 'probation' ? 'selected' : ''; ?>>
                                                ⏳ Thử việc
                                            </option>
                                            <option value="active" <?php echo ($old['status'] ?? '') === 'active' ? 'selected' : ''; ?>>
                                                ✅ Chính thức
                                            </option>
                                            <option value="terminated" <?php echo ($old['status'] ?? '') === 'terminated' ? 'selected' : ''; ?>>
                                                ❌ Đã nghỉ
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT COLUMN -->
                            <div class="col-lg-4">
                                <div class="p-4 border rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    
                                    <h5 class="fw-bold mb-4 text-center">
                                        <i class="bi bi-paperclip me-2"></i>Tệp đính kèm
                                    </h5>

                                    <!-- Ảnh đại diện -->
                                    <div class="mb-4">
                                        <label for="photo" class="form-label fw-semibold">
                                            <i class="bi bi-image text-primary me-2"></i>Ảnh đại diện
                                        </label>
                                        <input type="file" 
                                               class="form-control form-control-lg rounded-3 shadow-sm"
                                               id="photo" 
                                               name="photo" 
                                               accept="image/*">
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Định dạng: JPG, PNG | Tối đa: <strong>2MB</strong>
                                        </div>
                                        
                                        <!-- Preview placeholder -->
                                        <div id="photo-preview" class="mt-3 text-center" style="display: none;">
                                            <img src="" 
                                                 alt="Preview" 
                                                 class="img-thumbnail rounded-3 shadow-sm"
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        </div>
                                    </div>

                                    <!-- Hợp đồng -->
                                    <div class="mb-2">
                                        <label for="contract" class="form-label fw-semibold">
                                            <i class="bi bi-file-earmark-text text-danger me-2"></i>Hợp đồng
                                        </label>
                                        <input type="file" 
                                               class="form-control form-control-lg rounded-3 shadow-sm"
                                               id="contract" 
                                               name="contract"
                                               accept=".pdf,.doc,.docx">
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Định dạng: PDF, DOC, DOCX | Tối đa: <strong>5MB</strong>
                                        </div>
                                    </div>

                                    <!-- Info Box -->
                                    <div class="alert alert-info border-0 rounded-3 mt-4">
                                        <small>
                                            <i class="bi bi-lightbulb me-1"></i>
                                            <strong>Lưu ý:</strong> Các tệp này sẽ được lưu trữ an toàn trong hệ thống
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/employees" 
                               class="btn btn-lg btn-light border rounded-pill px-4 shadow-sm">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm" 
                                    style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none;">
                                <i class="bi bi-save-fill me-2"></i>Lưu Hồ sơ
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #f093fb;
        box-shadow: 0 0 0 0.25rem rgba(240, 147, 251, 0.25);
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
        box-shadow: 0 6px20px rgba(0,0,0,0.15) !important;
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
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
    }
</style>

<script>
    // Photo preview
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photo-preview');
                const img = preview.querySelector('img');
                img.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
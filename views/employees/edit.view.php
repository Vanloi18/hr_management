<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
unset($_SESSION['_flash']['errors']);
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <!-- Card Header -->
                <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-50 rounded-3 p-3 me-3">
                            <i class="bi bi-pencil-square text-dark" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 text-dark fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 text-dark text-opacity-75 small">
                                <i class="bi bi-person-badge me-1"></i>
                                Đang chỉnh sửa: <strong><?php echo e($employee['full_name']); ?></strong>
                            </p>
                        </div>
                        <div class="badge bg-white text-dark rounded-pill px-3 py-2">
                            ID: #<?php echo e($employee['id']); ?>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/employees/update" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <?php csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($employee['id']); ?>">

                        <div class="row g-4">

                            <!-- LEFT COLUMN -->
                            <div class="col-lg-8">

                                <!-- Thông tin cơ bản -->
                                <div class="section-title mb-4">
                                    <h5 class="fw-bold text-muted mb-3">
                                        <i class="bi bi-person-circle text-info me-2"></i>Thông tin Cơ bản
                                    </h5>
                                </div>

                                <!-- Họ tên -->
                                <div class="mb-4">
                                    <label for="full_name" class="form-label fw-semibold">
                                        <i class="bi bi-person text-info me-2"></i>Họ tên
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg rounded-3 shadow-sm"
                                           id="full_name" 
                                           name="full_name"
                                           value="<?php echo e($employee['full_name']); ?>" 
                                           placeholder="Nhập họ và tên đầy đủ"
                                           required>
                                </div>

                                <!-- Email & Phone -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class="bi bi-envelope text-info me-2"></i>Email
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" 
                                               class="form-control form-control-lg rounded-3 shadow-sm"
                                               id="email" 
                                               name="email"
                                               value="<?php echo e($employee['email']); ?>" 
                                               placeholder="example@company.com"
                                               required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label fw-semibold">
                                            <i class="bi bi-telephone text-info me-2"></i>Số điện thoại
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg rounded-3 shadow-sm"
                                               id="phone" 
                                               name="phone"
                                               value="<?php echo e($employee['phone']); ?>"
                                               placeholder="0912345678">
                                    </div>
                                </div>

                                <!-- Divider -->
                                <hr class="my-4">

                                <!-- Thông tin công việc -->
                                <div class="section-title mb-4">
                                    <h5 class="fw-bold text-muted mb-3">
                                        <i class="bi bi-briefcase-fill text-warning me-2"></i>Thông tin Công việc
                                    </h5>
                                </div>

                                <!-- Phòng ban & Chức vụ -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="department_id" class="form-label fw-semibold">
                                            <i class="bi bi-building text-warning me-2"></i>Phòng ban
                                        </label>
                                        <select class="form-select form-select-lg rounded-3 shadow-sm" 
                                                id="department_id" 
                                                name="department_id">
                                            <option value="">-- Không thuộc phòng ban --</option>
                                            <?php foreach ($departments as $department): ?>
                                                <option value="<?php echo e($department['id']); ?>"
                                                    <?php echo $employee['department_id'] == $department['id'] ? 'selected' : ''; ?>>
                                                    <?php echo e($department['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="job_title" class="form-label fw-semibold">
                                            <i class="bi bi-briefcase text-warning me-2"></i>Chức vụ
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg rounded-3 shadow-sm"
                                               id="job_title" 
                                               name="job_title"
                                               value="<?php echo e($employee['job_title']); ?>" 
                                               placeholder="Ví dụ: Nhân viên, Trưởng phòng..."
                                               required>
                                    </div>
                                </div>

                                <!-- Ngày vào & Trạng thái -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="start_date" class="form-label fw-semibold">
                                            <i class="bi bi-calendar-check text-warning me-2"></i>Ngày vào công ty
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" 
                                               class="form-control form-control-lg rounded-3 shadow-sm"
                                               id="start_date" 
                                               name="start_date"
                                               value="<?php echo e($employee['start_date']); ?>" 
                                               required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="status" class="form-label fw-semibold">
                                            <i class="bi bi-circle-fill text-warning me-2"></i>Trạng thái
                                        </label>
                                        <select class="form-select form-select-lg rounded-3 shadow-sm" 
                                                id="status" 
                                                name="status">
                                            <option value="probation" <?php echo $employee['status'] === 'probation' ? 'selected' : ''; ?>>
                                                ⏳ Thử việc
                                            </option>
                                            <option value="active" <?php echo $employee['status'] === 'active' ? 'selected' : ''; ?>>
                                                ✅ Chính thức
                                            </option>
                                            <option value="terminated" <?php echo $employee['status'] === 'terminated' ? 'selected' : ''; ?>>
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
                                        <i class="bi bi-paperclip me-2"></i>Quản lý Tệp
                                    </h5>

                                    <!-- Ảnh đại diện -->
                                    <div class="mb-4">
                                        <label for="photo" class="form-label fw-semibold">
                                            <i class="bi bi-image text-primary me-2"></i>Thay thế ảnh đại diện
                                        </label>
                                        <input type="file" 
                                               class="form-control form-control-lg rounded-3 shadow-sm"
                                               id="photo" 
                                               name="photo" 
                                               accept="image/*">

                                        <?php if (!empty($employee['photo_path'])): ?>
                                            <div class="mt-3 text-center">
                                                <img src="<?php echo BASE_URL . '/' . e($employee['photo_path']); ?>"
                                                     alt="<?php echo e($employee['full_name']); ?>"
                                                     class="img-thumbnail rounded-3 shadow-sm"
                                                     style="width: 150px; height: 150px; object-fit: cover;">
                                                <small class="d-block text-muted mt-2">
                                                    <i class="bi bi-check-circle me-1"></i>Ảnh hiện tại
                                                </small>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-secondary border-0 rounded-3 mt-3">
                                                <small>
                                                    <i class="bi bi-info-circle me-1"></i>Chưa có ảnh đại diện
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="form-text mt-2">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Bỏ trống nếu không muốn thay đổi
                                        </div>
                                    </div>

                                    <!-- Hợp đồng -->
                                    <div>
                                        <label for="contract" class="form-label fw-semibold">
                                            <i class="bi bi-file-earmark-text text-danger me-2"></i>Thay thế hợp đồng
                                        </label>
                                        <input type="file" 
                                               class="form-control form-control-lg rounded-3 shadow-sm"
                                               id="contract" 
                                               name="contract"
                                               accept=".pdf,.doc,.docx">

                                        <?php if (!empty($employee['contract_path'])): ?>
                                            <div class="mt-3">
                                                <a href="<?php echo BASE_URL . '/' . e($employee['contract_path']); ?>"
                                                   target="_blank"
                                                   class="btn btn-outline-primary btn-sm rounded-pill w-100 shadow-sm">
                                                    <i class="bi bi-file-earmark-arrow-down me-2"></i>
                                                    Xem hợp đồng hiện tại
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-secondary border-0 rounded-3 mt-3">
                                                <small>
                                                    <i class="bi bi-info-circle me-1"></i>Chưa có hợp đồng
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="form-text mt-2">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Bỏ trống nếu không muốn thay đổi
                                        </div>
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
                                    style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; color: #333;">
                                <i class="bi bi-save-fill me-2"></i>Cập nhật hồ sơ
                            </button>
                        </div>

                    </form>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center text-muted small flex-wrap gap-2">
                        <span>
                            <i class="bi bi-calendar3 me-1"></i>
                            Vào làm: <strong><?php echo date('d/m/Y', strtotime($employee['start_date'])); ?></strong>
                        </span>
                        <span>
                            <i class="bi bi-clock-history me-1"></i>
                            Cập nhật: <strong><?php echo date('d/m/Y H:i', strtotime($employee['updated_at'] ?? $employee['created_at'])); ?></strong>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow border-0 rounded-4 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-lightning-fill text-warning me-2"></i>Hành động nhanh:
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" 
                                class="btn btn-outline-success btn-sm rounded-pill px-3"
                                onclick="document.getElementById('status').value='active'; document.querySelector('form').submit();">
                            <i class="bi bi-check-circle me-1"></i>Chuyển sang Chính thức
                        </button>
                        <button type="button" 
                                class="btn btn-outline-warning btn-sm rounded-pill px-3"
                                onclick="document.getElementById('status').value='probation'; document.querySelector('form').submit();">
                            <i class="bi bi-clock me-1"></i>Chuyển sang Thử việc
                        </button>
                        <button type="button" 
                                class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                onclick="if(confirm('Xác nhận chuyển trạng thái Đã nghỉ?')) { document.getElementById('status').value='terminated'; document.querySelector('form').submit(); }">
                            <i class="bi bi-x-circle me-1"></i>Đánh dấu Đã nghỉ
                        </button>
                        <a href="mailto:<?php echo e($employee['email']); ?>" 
                           class="btn btn-outline-info btn-sm rounded-pill px-3">
                            <i class="bi bi-envelope me-1"></i>Gửi Email
                        </a>
                        <?php if ($employee['phone']): ?>
                            <a href="tel:<?php echo e($employee['phone']); ?>" 
                               class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                <i class="bi bi-telephone me-1"></i>Gọi điện
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #4facfe;
        box-shadow: 0 0 0 0.25rem rgba(79, 172, 254, 0.25);
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
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .card-header .badge {
            display: none;
        }
    }
</style>
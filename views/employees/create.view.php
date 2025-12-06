<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
$old = $_SESSION['_flash']['old'] ?? [];
unset($_SESSION['_flash']['errors'], $_SESSION['_flash']['old']);
?>

<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden employee-card">
                
                <div class="card-header border-0 py-4 bg-success text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-person-plus-fill fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 opacity-75 small">Nhập thông tin hồ sơ nhân viên mới</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
                            <ul class="mb-0 ps-3 small">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo BASE_URL; ?>/employees" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-uppercase text-secondary text-xs opacity-75 mb-3 ls-1">
                                    <i class="bi bi-person-vcard me-1"></i> Thông tin cá nhân
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($old['full_name'] ?? ''); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($old['email'] ?? ''); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($old['phone'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Ảnh chân dung</label>
                                    <input type="file" name="photo" class="form-control bg-light border-0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold text-uppercase text-secondary text-xs opacity-75 mb-3 ls-1">
                                    <i class="bi bi-briefcase me-1"></i> Thông tin công việc
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Vị trí công việc <span class="text-danger">*</span></label>
                                    <input type="text" name="job_title" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($old['job_title'] ?? ''); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Phòng ban</label>
                                    <select name="department_id" class="form-select form-select-lg bg-light border-0 cursor-pointer">
                                        <option value="">-- Chọn phòng ban --</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo $dept['id']; ?>" <?php echo ($old['department_id'] ?? '') == $dept['id'] ? 'selected' : ''; ?>>
                                                <?php echo e($dept['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($old['start_date'] ?? date('Y-m-d')); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Trạng thái</label>
                                    <select name="status" class="form-select form-select-lg bg-light border-0 cursor-pointer">
                                        <option value="probation">Thử việc (Probation)</option>
                                        <option value="active">Chính thức (Active)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Hợp đồng (PDF/DOC)</label>
                                    <input type="file" name="contract" class="form-control bg-light border-0">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-5">
                            <a href="<?php echo BASE_URL; ?>/employees" class="btn btn-light btn-lg px-4 rounded-pill border-0 text-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow-sm fw-medium">
                                <i class="bi bi-save me-2"></i>Lưu hồ sơ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus { background-color: #fff; box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.15); border: 1px solid #20c997 !important; }
    /* Dark Mode */
    [data-theme="dark"] .employee-card { background-color: #1e1e1e !important; border: 1px solid #333 !important; }
    [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background-color: #2b2b2b !important; color: #fff !important; border-color: #444 !important; }
    [data-theme="dark"] .form-control:focus { background-color: #333 !important; border-color: #198754 !important; }
    [data-theme="dark"] .text-secondary { color: #a0a0a0 !important; }
</style>
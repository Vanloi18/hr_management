<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
$old = $_SESSION['_flash']['old'] ?? [];
unset($_SESSION['_flash']['errors'], $_SESSION['_flash']['old']);
?>

<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-card">
                
                <div class="card-header border-0 py-4 bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-megaphone-fill fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 opacity-75 small">Tạo tin tuyển dụng mới cho vị trí công việc</p>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/positions" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-medium">Tiêu đề vị trí <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-lg bg-light border-0" 
                                       value="<?php echo e($old['title'] ?? ''); ?>" placeholder="VD: Senior React Developer" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Công ty <span class="text-danger">*</span></label>
                                <select name="recruiter_id" class="form-select form-select-lg bg-light border-0 cursor-pointer" required>
                                    <option value="">-- Chọn công ty --</option>
                                    <?php foreach ($recruiters as $rec): ?>
                                        <option value="<?php echo $rec['id']; ?>" <?php echo ($old['recruiter_id'] ?? '') == $rec['id'] ? 'selected' : ''; ?>>
                                            <?php echo e($rec['company_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Lĩnh vực <span class="text-danger">*</span></label>
                                <select name="field_id" class="form-select form-select-lg bg-light border-0 cursor-pointer" required>
                                    <option value="">-- Chọn lĩnh vực --</option>
                                    <?php foreach ($fields as $field): ?>
                                        <option value="<?php echo $field['id']; ?>" <?php echo ($old['field_id'] ?? '') == $field['id'] ? 'selected' : ''; ?>>
                                            <?php echo e($field['field_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Mức lương</label>
                                <input type="text" name="salary_range" class="form-control form-control-lg bg-light border-0" 
                                       value="<?php echo e($old['salary_range'] ?? ''); ?>" placeholder="VD: 15 - 20 Triệu">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Địa điểm làm việc</label>
                                <input type="text" name="location" class="form-control form-control-lg bg-light border-0" 
                                       value="<?php echo e($old['location'] ?? ''); ?>" placeholder="VD: Hà Nội">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Mô tả công việc</label>
                                <textarea name="description" rows="4" class="form-control bg-light border-0 rounded-3"><?php echo e($old['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Yêu cầu</label>
                                <textarea name="requirements" rows="4" class="form-control bg-light border-0 rounded-3"><?php echo e($old['requirements'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-5">
                            <a href="<?php echo BASE_URL; ?>/positions" class="btn btn-light btn-lg px-4 rounded-pill border-0 text-secondary">Hủy</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm fw-medium">
                                <i class="bi bi-save me-2"></i>Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus { background-color: #fff; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15); border: 1px solid #86b7fe !important; }
    
    /* Dark Mode */
    [data-theme="dark"] .position-card { background-color: #1e1e1e !important; border: 1px solid #333 !important; }
    [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background-color: #2b2b2b !important; color: #fff !important; border-color: #444 !important; }
    [data-theme="dark"] .form-control:focus { background-color: #333 !important; border-color: #0d6efd !important; }
    [data-theme="dark"] .text-secondary { color: #a0a0a0 !important; }
</style>
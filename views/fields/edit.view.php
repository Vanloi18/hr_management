<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
unset($_SESSION['_flash']['errors']); 
?>

<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden field-card">
                
                <div class="card-header border-0 py-4 bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-pencil-square fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 opacity-75 small">
                                Đang chỉnh sửa: <strong><?php echo e($field['field_name']); ?></strong>
                            </p>
                        </div>
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold shadow-sm d-none d-md-block">
                            ID: #<?php echo e($field['id']); ?>
                        </span>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/fields/update" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        <input type="hidden" name="id" value="<?php echo e($field['id']); ?>">

                        <div class="mb-4">
                            <label class="form-label fw-medium">Tên lĩnh vực <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-card-heading text-secondary"></i></span>
                                <input type="text" name="field_name" class="form-control form-control-lg bg-light border-0" 
                                       value="<?php echo e($field['field_name']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Mô tả chi tiết</label>
                            <textarea name="description" rows="4" class="form-control bg-light border-0 rounded-3"><?php echo e($field['description']); ?></textarea>
                        </div>

                        <hr class="border-secondary border-opacity-10 my-4">

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/fields" class="btn btn-light btn-lg px-4 rounded-pill border-0 text-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm fw-medium">
                                <i class="bi bi-check2-circle me-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span>
                            <i class="bi bi-calendar3 me-1"></i>
                            Tạo lúc: <strong><?php echo date('d/m/Y H:i', strtotime($field['created_at'])); ?></strong>
                        </span>
                        <span class="d-md-none">ID: #<?php echo e($field['id']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus { background-color: #fff; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15); border: 1px solid #86b7fe !important; }
    
    /* Dark Mode */
    [data-theme="dark"] .field-card { background-color: #1e1e1e !important; border: 1px solid #333 !important; }
    [data-theme="dark"] .form-control { background-color: #2b2b2b !important; color: #fff !important; border-color: #444 !important; }
    [data-theme="dark"] .form-control:focus { background-color: #333 !important; border-color: #0d6efd !important; }
    [data-theme="dark"] .input-group-text { background-color: #2b2b2b !important; border-color: #444 !important; color: #a0a0a0; }
    [data-theme="dark"] .text-secondary { color: #a0a0a0 !important; }
    [data-theme="dark"] .card-footer { background-color: #1e1e1e !important; border-top: 1px solid #333 !important; }
</style>
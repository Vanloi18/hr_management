<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
$old = $_SESSION['_flash']['old'] ?? [];
unset($_SESSION['_flash']['errors'], $_SESSION['_flash']['old']);
?>

<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden field-card">
                
                <div class="card-header border-0 py-4 bg-success text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-tag-fill fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 opacity-75 small">Tạo lĩnh vực mới để phân loại công việc</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Vui lòng kiểm tra lại!</h6>
                                    <ul class="mb-0 ps-3 small">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo BASE_URL; ?>/fields" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

                        <div class="mb-4">
                            <label class="form-label fw-medium">Tên lĩnh vực <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-card-heading text-secondary"></i></span>
                                <input type="text" name="field_name" class="form-control form-control-lg bg-light border-0" 
                                       value="<?php echo e($old['field_name'] ?? ''); ?>" 
                                       placeholder="Ví dụ: Công nghệ thông tin, Marketing..." required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Mô tả chi tiết</label>
                            <textarea name="description" rows="4" class="form-control bg-light border-0 rounded-3" 
                                      placeholder="Nhập mô tả ngắn về lĩnh vực này..."><?php echo e($old['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="mb-4">
                            <p class="text-secondary small mb-2"><i class="bi bi-lightbulb me-1"></i>Gợi ý các lĩnh vực phổ biến:</p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-light text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill cursor-pointer example-badge">IT - Phần mềm</span>
                                <span class="badge bg-light text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill cursor-pointer example-badge">Marketing</span>
                                <span class="badge bg-light text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill cursor-pointer example-badge">Tài chính</span>
                                <span class="badge bg-light text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill cursor-pointer example-badge">Nhân sự</span>
                            </div>
                        </div>

                        <hr class="border-secondary border-opacity-10 my-4">

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/fields" class="btn btn-light btn-lg px-4 rounded-pill border-0 text-secondary">Quay lại</a>
                            <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow-sm fw-medium">
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
    .form-control:focus { background-color: #fff; box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.15); border: 1px solid #20c997 !important; }
    .input-group-text { color: #6c757d; }
    
    .example-badge { transition: all 0.2s; cursor: pointer; }
    .example-badge:hover { background-color: #e9ecef !important; color: #198754 !important; border-color: #198754 !important; }

    /* Dark Mode */
    [data-theme="dark"] .field-card { background-color: #1e1e1e !important; border: 1px solid #333 !important; }
    [data-theme="dark"] .form-control { background-color: #2b2b2b !important; color: #fff !important; border-color: #444 !important; }
    [data-theme="dark"] .form-control:focus { background-color: #333 !important; border-color: #198754 !important; }
    [data-theme="dark"] .input-group-text { background-color: #2b2b2b !important; border-color: #444 !important; color: #a0a0a0; }
    [data-theme="dark"] .text-secondary { color: #a0a0a0 !important; }
    [data-theme="dark"] .example-badge { background-color: #2b2b2b !important; color: #a0a0a0 !important; border-color: #444 !important; }
</style>

<script>
    // Click vào badge gợi ý để điền tự động
    document.querySelectorAll('.example-badge').forEach(badge => {
        badge.addEventListener('click', function() {
            document.querySelector('input[name="field_name"]').value = this.innerText;
        });
    });
</script>
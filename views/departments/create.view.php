<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
unset($_SESSION['_flash']['errors']); 
?>

<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden dept-card">
                <div class="card-header border-0 py-4 bg-success text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-plus-circle-fill fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 opacity-75 small">Tạo phòng ban mới cho tổ chức</p>
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

                    <form method="POST" action="<?php echo BASE_URL; ?>/departments" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

                        <div class="mb-4">
                            <label class="form-label fw-medium">Tên phòng ban <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" 
                                   placeholder="Nhập tên phòng ban (VD: Phòng Kế Toán)" required>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-5">
                            <a href="<?php echo BASE_URL; ?>/departments" class="btn btn-light btn-lg px-4 rounded-pill border-0 text-secondary">Quay lại</a>
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
    /* Dark Mode */
    [data-theme="dark"] .dept-card { background-color: #1e1e1e !important; border: 1px solid #333 !important; }
    [data-theme="dark"] .form-control { background-color: #2b2b2b !important; color: #fff !important; border-color: #444 !important; }
    [data-theme="dark"] .form-control:focus { background-color: #333 !important; border-color: #198754 !important; }
</style>
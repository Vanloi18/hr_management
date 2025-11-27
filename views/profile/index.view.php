<div class="container-fluid py-4 bg-light">
    
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Hồ sơ cá nhân</h4>
            <p class="text-secondary small mb-0">Quản lý thông tin tài khoản và bảo mật</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center p-5">
                    <div class="position-relative d-inline-block mb-3">
                        <?php if (!empty($user['avatar']) && file_exists('uploads/avatars/' . $user['avatar'])): ?>
                            <img src="<?php echo BASE_URL . '/uploads/avatars/' . $user['avatar']; ?>" 
                                 class="rounded-circle shadow-sm object-fit-cover border border-3 border-white" 
                                 width="120" height="120" alt="Avatar">
                        <?php else: ?>
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold border border-3 border-white shadow-sm" 
                                 style="width: 120px; height: 120px; font-size: 3rem;">
                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        
                        <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-white shadow-sm rounded-circle p-2 cursor-pointer border text-primary" 
                               style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"
                               data-bs-toggle="tooltip" title="Đổi ảnh đại diện">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                    </div>

                    <h5 class="fw-bold mb-1"><?php echo e($user['full_name']); ?></h5>
                    <p class="text-secondary mb-3"><?php echo e($user['email']); ?></p>
                    
                    <?php if ($user['role'] === 'admin'): ?>
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Quản trị viên</span>
                    <?php else: ?>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Nhân sự</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h6 class="fw-bold"><i class="bi bi-person-gear me-2 text-primary"></i>Thông tin chi tiết</h6>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo BASE_URL; ?>/profile/update" method="POST" enctype="multipart/form-data">
                        <?php csrf_field(); ?>
                        <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" onchange="previewImage(this)">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-bold">Họ và tên</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person"></i></span>
                                    <input type="text" name="full_name" class="form-control bg-light border-0" 
                                           value="<?php echo e($user['full_name']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-bold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-0" 
                                           value="<?php echo e($user['email']); ?>" required>
                                </div>
                            </div>

                            <div class="col-12 my-4">
                                <hr class="text-secondary opacity-25">
                                <div class="form-check form-switch">
                                    <input class="form-check-input cursor-pointer" type="checkbox" id="togglePassword">
                                    <label class="form-check-label fw-medium cursor-pointer" for="togglePassword">Đổi mật khẩu</label>
                                </div>
                            </div>

                            <div id="passwordSection" class="row g-3 d-none">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted fw-bold">Mật khẩu mới</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control bg-light border-0" 
                                               placeholder="Chỉ nhập nếu muốn đổi">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted fw-bold">Xác nhận mật khẩu</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-check2-circle"></i></span>
                                        <input type="password" name="confirm_password" class="form-control bg-light border-0" 
                                               placeholder="Nhập lại mật khẩu mới">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium shadow-sm">
                                <i class="bi bi-floppy me-1"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Toggle hiển thị đổi mật khẩu
    document.getElementById('togglePassword').addEventListener('change', function() {
        const section = document.getElementById('passwordSection');
        if(this.checked) {
            section.classList.remove('d-none');
            section.classList.add('animate__animated', 'animate__fadeIn');
        } else {
            section.classList.add('d-none');
        }
    });

    // 2. Preview ảnh khi chọn file
    function previewImage(input) {
        if (input.files && input.files[0]) {
            // Logic preview nếu muốn thêm (hiện tại submit luôn để đơn giản)
            // alert('Đã chọn ảnh: ' + input.files[0].name + '. Hãy bấm Lưu để cập nhật.');
        }
    }

    // Kích hoạt tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

<style>
    .cursor-pointer { cursor: pointer; }
    .input-group-text { color: #6c757d; }
    .form-control:focus { box-shadow: none; background-color: #fff; }
    .form-control:focus + .input-group-text { background-color: #fff; color: var(--bs-primary); }
</style>
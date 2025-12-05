<?php 
// Xác định tab đang active từ URL (mặc định là general)
$currentTab = $_GET['tab'] ?? 'general'; 
?>

<div class="content-wrapper">
    <div class="container-fluid py-4 bg-light">
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h4 class="fw-bold text-dark mb-1">Cài đặt & Hồ sơ</h4>
                <p class="text-secondary small mb-0">Quản lý hệ thống và thông tin tài khoản</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="list-group list-group-flush">
                        <?php if($_SESSION['user']['role'] === 'admin'): ?>
                        <a href="?tab=general" class="list-group-item list-group-item-action py-3 px-4 <?php echo $currentTab == 'general' ? 'active-tab' : ''; ?>">
                            <i class="bi bi-building-gear me-2"></i> Cài đặt chung
                        </a>
                        <?php endif; ?>

                        <a href="?tab=personal" class="list-group-item list-group-item-action py-3 px-4 <?php echo $currentTab == 'personal' ? 'active-tab' : ''; ?>">
                            <i class="bi bi-person-vcard me-2"></i> Thông tin cá nhân
                        </a>

                        <a href="?tab=security" class="list-group-item list-group-item-action py-3 px-4 <?php echo $currentTab == 'security' ? 'active-tab' : ''; ?>">
                            <i class="bi bi-shield-lock me-2"></i> Bảo mật
                        </a>

                        <a href="?tab=system" class="list-group-item list-group-item-action py-3 px-4 <?php echo $currentTab == 'system' ? 'active-tab' : ''; ?>">
                            <i class="bi bi-info-circle me-2"></i> Thông tin hệ thống
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        
                        <?php if ($currentTab == 'general' && $_SESSION['user']['role'] === 'admin'): ?>
                            <h5 class="fw-bold text-primary mb-4">Cài đặt chung</h5>
                            <form action="<?php echo BASE_URL; ?>/settings/update" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                <div class="mb-3">
                                    <label class="form-label">Tên công ty</label>
                                    <input type="text" name="settings[company_name]" class="form-control" value="<?php echo e($settings['company_name'] ?? ''); ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email hệ thống</label>
                                        <input type="email" name="settings[company_email]" class="form-control" value="<?php echo e($settings['company_email'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Hotline</label>
                                        <input type="text" name="settings[company_phone]" class="form-control" value="<?php echo e($settings['company_phone'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Địa chỉ</label>
                                    <input type="text" name="settings[company_address]" class="form-control" value="<?php echo e($settings['company_address'] ?? ''); ?>">
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save"></i> Lưu thay đổi</button>
                                </div>
                            </form>
                        
                        <?php elseif ($currentTab == 'personal'): ?>
                            <h5 class="fw-bold text-primary mb-4">Thông tin cá nhân</h5>
                            <form action="<?php echo BASE_URL; ?>/profile/update-info" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                <div class="row align-items-center mb-4">
                                    <div class="col-auto">
                                        <?php if (!empty($user['avatar'])): ?>
                                            <img src="<?php echo BASE_URL . '/uploads/avatars/' . $user['avatar']; ?>" class="rounded-circle object-fit-cover border" width="80" height="80">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-secondary fw-bold border" style="width: 80px; height: 80px; font-size: 2rem;">
                                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col">
                                        <label class="form-label fw-bold">Ảnh đại diện</label>
                                        <input type="file" name="avatar" class="form-control form-control-sm">
                                        <div class="form-text">Chấp nhận JPG, PNG. Dung lượng tối đa 2MB.</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" name="full_name" class="form-control" value="<?php echo e($user['full_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email (Không thể thay đổi)</label>
                                    <input type="text" class="form-control bg-light" value="<?php echo e($user['email']); ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Vai trò</label>
                                    <input type="text" class="form-control bg-light text-capitalize" value="<?php echo e($user['role']); ?>" readonly>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-person-check"></i> Cập nhật hồ sơ</button>
                                </div>
                            </form>

                        <?php elseif ($currentTab == 'security'): ?>
                            <h5 class="fw-bold text-primary mb-4">Đổi mật khẩu</h5>
                            <form action="<?php echo BASE_URL; ?>/profile/change-password" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu hiện tại</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <input type="password" name="new_password" class="form-control" required minlength="6">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" name="confirm_password" class="form-control" required minlength="6">
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-danger rounded-pill px-4"><i class="bi bi-key"></i> Đổi mật khẩu</button>
                                </div>
                            </form>

                        <?php elseif ($currentTab == 'system'): ?>
                            <h5 class="fw-bold text-primary mb-4">Thông tin hệ thống</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Phiên bản Ứng dụng</th>
                                            <td><span class="badge bg-success">v<?php echo $systemInfo['app_version']; ?></span></td>
                                        </tr>
                                        <tr>
                                            <th>Phiên bản PHP</th>
                                            <td><?php echo $systemInfo['php_version']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Máy chủ (Server Software)</th>
                                            <td><?php echo $systemInfo['server_software']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Database</th>
                                            <td><?php echo $systemInfo['db_connection']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Upload Max Size</th>
                                            <td><?php echo $systemInfo['upload_max_filesize']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Post Max Size</th>
                                            <td><?php echo $systemInfo['post_max_size']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .active-tab {
        background-color: #eef2ff !important;
        color: #2563eb !important;
        border-left: 4px solid #2563eb !important;
        font-weight: 600;
    }
    .list-group-item {
        border: none;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s;
    }
    .list-group-item:hover {
        background-color: #f9fafb;
        color: #2563eb;
    }
</style>
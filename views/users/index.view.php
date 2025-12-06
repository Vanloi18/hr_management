<div class="container-fluid py-4 bg-light">
    
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Quản lý Người dùng</h4>
            <p class="text-secondary small mb-0">Quản lý tài khoản đăng nhập và phân quyền hệ thống</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center justify-content-between">
                <div class="col-md-6 col-lg-5">
                    <form action="<?php echo BASE_URL; ?>/users" method="GET" class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <input type="text" 
                               name="keyword" 
                               class="form-control form-control-lg ps-5 rounded-pill bg-light border-0 fs-6" 
                               placeholder="Tìm kiếm theo tên, email..." 
                               value="<?php echo isset($keyword) ? e($keyword) : '' ?>">
                         <?php if(isset($role) && !empty($role)): ?>
                            <input type="hidden" name="role" value="<?php echo e($role); ?>">
                         <?php endif; ?>
                    </form>
                </div>

                <div class="col-md-6 col-lg-7 text-md-end">
                    <div class="d-flex gap-2 justify-content-md-end">
                        <div class="dropdown">
                            <button class="btn btn-light border-0 rounded-pill px-3 py-2 dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-funnel me-1"></i> 
                                <?php 
                                    if(isset($role) && $role === 'admin') echo 'Quản trị viên';
                                    elseif(isset($role) && $role === 'hr') echo 'Nhân sự';
                                    else echo 'Lọc Vai trò';
                                ?>
                            </button>
                            <ul class="dropdown-menu shadow border-0 rounded-3 mt-2">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/users">Tất cả</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/users?role=admin<?php echo isset($keyword) ? '&keyword='.e($keyword) : '' ?>">Quản trị viên</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/users?role=hr<?php echo isset($keyword) ? '&keyword='.e($keyword) : '' ?>">Nhân sự</a></li>
                            </ul>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-success rounded-pill px-3 py-2 shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-1"></i> Xuất file
                            </button>
                            <ul class="dropdown-menu shadow border-0 rounded-3 mt-2">
                                <?php 
                                    $query = http_build_query([
                                        'keyword' => $keyword ?? '',
                                        'role'    => $role ?? ''
                                    ]);
                                ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/users/export-excel?<?php echo $query; ?>">
                                        <i class="bi bi-file-earmark-excel text-success me-2"></i> Xuất Excel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/users/export-pdf?<?php echo $query; ?>">
                                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Xuất PDF
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <a href="<?php echo BASE_URL; ?>/users/create" class="btn btn-primary rounded-pill px-4 py-2 fw-medium shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Thêm User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-uppercase fw-bold small opacity-75" style="width: 50px;">ID</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Người dùng</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Vai trò</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Trạng thái</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Ngày tạo</th>
                            <th class="pe-4 py-3 text-end text-secondary text-uppercase fw-bold small opacity-75">Hành động</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr id="row-user-<?php echo e($user['id']); ?>">
                                    <td class="ps-4">
                                        <span class="text-muted small fw-medium font-monospace">#<?php echo e($user['id']); ?></span>
                                    </td>
                                    
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($user['avatar']) && file_exists(BASE_PATH . 'public/uploads/avatars/' . $user['avatar'])): ?>
                                                <img src="<?php echo BASE_URL . '/uploads/avatars/' . $user['avatar']; ?>" 
                                                     class="rounded-circle me-3 object-fit-cover shadow-sm border" 
                                                     width="45" height="45" alt="Avatar">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3 fw-bold border border-primary border-opacity-10" 
                                                     style="width: 45px; height: 45px; font-size: 1.1rem;">
                                                    <?php echo strtoupper(substr($user['full_name'] ?? 'U', 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div>
                                                <h6 class="mb-0 text-dark fw-bold"><?php echo e($user['full_name']); ?></h6>
                                                <span class="text-secondary small d-block mt-1"><?php echo e($user['email']); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-medium border border-danger border-opacity-10">
                                                Quản trị viên
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill fw-medium border border-info border-opacity-10">
                                                Nhân sự (HR)
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if (isset($user['status']) && $user['status'] == 0): ?>
                                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-2 fw-medium border border-secondary border-opacity-10">
                                                <i class="bi bi-lock-fill me-1"></i>Đã khóa
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-2 fw-medium border border-success border-opacity-10">
                                                <i class="bi bi-check-circle-fill me-1"></i>Hoạt động
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="text-secondary small">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo e(date('d/m/Y', strtotime($user['created_at']))); ?>
                                    </td>
                                    
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?php echo BASE_URL; ?>/users/edit?id=<?php echo e($user['id']); ?>" 
                                               class="btn btn-icon btn-light text-primary rounded-circle" 
                                               data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            
                                           <?php if($user['id'] != $_SESSION['user']['id']): ?>
                                                <button type="button" 
                                                        class="btn btn-icon btn-light text-danger rounded-circle btn-delete-user"
                                                        data-id="<?php echo e($user['id']); ?>"
                                                        data-url="<?php echo BASE_URL; ?>/users/delete" 
                                                        data-bs-toggle="tooltip" title="Xóa tài khoản">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-search display-6 text-secondary"></i>
                                        <p class="mt-3 mb-0 text-muted">Không tìm thấy tài khoản phù hợp</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="px-4 py-3 border-top bg-white rounded-bottom-4">
                <?php require BASE_PATH . 'views/partials/pagination.view.php'; ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Table Styling */
    .custom-table td { vertical-align: middle; padding: 1rem 1.5rem; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    
    /* Button Icons */
    .btn-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .btn-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    
    /* Avatar */
    .object-fit-cover { object-fit: cover; }
    
    /* Badges Subtle (Bootstrap 5.3 style polyfill) */
    .bg-danger-subtle { background-color: #fff5f5 !important; }
    .bg-info-subtle { background-color: #eff6ff !important; }
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-secondary-subtle { background-color: #f3f4f6 !important; }

    /* Dark Mode Overrides */
    [data-theme="dark"] .bg-light { background-color: #1e1e1e !important; color: #e0e0e0; }
    [data-theme="dark"] .text-dark { color: #fff !important; }
    [data-theme="dark"] .text-secondary, [data-theme="dark"] .text-muted { color: #a0a0a5 !important; }
    [data-theme="dark"] .table { color: #e0e0e0; border-color: #333; }
    [data-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.05); }
    [data-theme="dark"] .form-control { background-color: #2b2b2b; border-color: #444; color: #fff; }
    [data-theme="dark"] .btn-light { background-color: rgba(255,255,255,0.1); color: #fff; border: none; }
    [data-theme="dark"] .bg-white { background-color: #1e1e1e !important; }
    
    [data-theme="dark"] .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.2) !important; color: #ff6b6b; border-color: #dc3545; }
    [data-theme="dark"] .bg-info-subtle { background-color: rgba(13, 202, 240, 0.2) !important; color: #6edff6; border-color: #0dcaf0; }
    [data-theme="dark"] .bg-success-subtle { background-color: rgba(25, 135, 84, 0.2) !important; color: #75b798; border-color: #198754; }
    [data-theme="dark"] .bg-secondary-subtle { background-color: rgba(108, 117, 125, 0.2) !important; color: #a7acb1; border-color: #6c757d; }
    [data-theme="dark"] .pagination .page-link { background-color: #2b2b2b; border-color: #444; color: #fff; }
    [data-theme="dark"] .pagination .page-item.active .page-link { background-color: #0d6efd; border-color: #0d6efd; }
</style>
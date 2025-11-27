<div class="container-fluid py-4 bg-light">
    
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Quản lý Nhân viên</h4>
            <p class="text-secondary small mb-0">Quản lý tài khoản và phân quyền hệ thống</p>
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
                               class="form-control form-control-lg ps-5 rounded-pill bg-light border-0" 
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
                            <button class="btn btn-light border-0 rounded-pill px-3 py-2 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-funnel me-1"></i> 
                                <?php 
                                    if(isset($role) && $role === 'admin') echo 'Quản trị viên';
                                    elseif(isset($role) && $role === 'hr') echo 'Nhân sự';
                                    else echo 'Tất cả vai trò';
                                ?>
                            </button>
                            <ul class="dropdown-menu shadow-sm border-0 rounded-3">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/users">Tất cả</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/users?role=admin<?php echo isset($keyword) ? '&keyword='.e($keyword) : '' ?>">Quản trị viên</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/users?role=hr<?php echo isset($keyword) ? '&keyword='.e($keyword) : '' ?>">Nhân sự</a></li>
                            </ul>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/users/create" class="btn btn-primary rounded-pill px-4 py-2 fw-medium shadow-sm">
                            <i class="bi bi-person-plus-fill me-1"></i> Thêm User
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
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Trạng thái</th> <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Ngày tạo</th>
                            <th class="pe-4 py-3 text-end text-secondary text-uppercase fw-bold small opacity-75">Hành động</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr id="row-user-<?php echo e($user['id']); ?>">
                                <td class="ps-4">
                                    <span class="text-muted small fw-medium">#<?php echo e($user['id']); ?></span>
                                </td>
                                
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($user['avatar']) && file_exists('uploads/avatars/' . $user['avatar'])): ?>
                                            <img src="<?php echo BASE_URL . '/uploads/avatars/' . $user['avatar']; ?>" 
                                                 class="rounded-circle me-3 object-fit-cover shadow-sm" 
                                                 width="45" height="45" alt="Avatar">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3 fw-bold border border-primary border-opacity-10" 
                                                 style="width: 45px; height: 45px; font-size: 1.1rem;">
                                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
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
                                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-3 fw-medium">
                                            Quản trị viên
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-3 fw-medium">
                                            Nhân sự
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (isset($user['status']) && $user['status'] == 0): ?>
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-3 fw-medium">
                                            <i class="bi bi-lock-fill me-1"></i>Đã khóa
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-3 fw-medium">
                                            <i class="bi bi-check-circle-fill me-1"></i>Hoạt động
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-secondary small">
                                    <?php echo e(date('d/m/Y', strtotime($user['created_at']))); ?>
                                </td>
                                
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo BASE_URL; ?>/users/edit?id=<?php echo e($user['id']); ?>" 
                                           class="btn btn-icon btn-light text-primary rounded-circle" 
                                           data-bs-toggle="tooltip" 
                                           data-bs-placement="top"
                                           title="Chỉnh sửa thông tin">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-icon btn-light text-danger rounded-circle btn-delete-user"
                                                data-id="<?php echo e($user['id']); ?>"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top"
                                                title="Xóa tài khoản">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-search display-6"></i>
                                        <p class="mt-3">Không tìm thấy kết quả phù hợp</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($totalPages > 1): ?>
            <div class="card-footer bg-white border-top py-4 px-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="text-secondary small">
                        Hiển thị <strong><?php echo count($users); ?></strong> trên tổng số <strong><?php echo $totalRecords; ?></strong> tài khoản
                    </div>
                    
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0 gap-1">
                            <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                <?php
                                    // Giữ lại các tham số search khi chuyển trang
                                    $prevParams = $_GET;
                                    $prevParams['page'] = $currentPage - 1;
                                    $prevUrl = BASE_URL . '/users?' . http_build_query($prevParams);
                                ?>
                                <a class="page-link rounded-2 border-0 bg-light text-secondary" href="<?php echo $prevUrl; ?>">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php
                                    $pageParams = $_GET;
                                    $pageParams['page'] = $i;
                                    $pageUrl = BASE_URL . '/users?' . http_build_query($pageParams);
                                    $activeClass = ($i == $currentPage) ? 'active shadow-sm' : 'text-secondary';
                                    $bgClass = ($i == $currentPage) ? '' : 'border-0';
                                ?>
                                <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                    <a class="page-link rounded-2 <?php echo $bgClass; ?> <?php echo $activeClass; ?>" href="<?php echo $pageUrl; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                                <?php
                                    $nextParams = $_GET;
                                    $nextParams['page'] = $currentPage + 1;
                                    $nextUrl = BASE_URL . '/users?' . http_build_query($nextParams);
                                ?>
                                <a class="page-link rounded-2 border-0 bg-light text-primary" href="<?php echo $nextUrl; ?>">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <?php endif; ?>
            </div>
    </div>
</div>

<style>
    /* CSS Tùy chỉnh cho thẩm mỹ */
    .bg-danger-subtle { background-color: #fceceb !important; }
    .bg-primary-subtle { background-color: #ebf5ff !important; }
    .bg-success-subtle { background-color: #e6f6ec !important; }
    .bg-secondary-subtle { background-color: #f1f3f5 !important; }
    
    .table-hover tbody tr:hover {
        background-color: #f9fafb;
    }
    .custom-table td {
        vertical-align: middle;
        padding-top: 16px;
        padding-bottom: 16px;
    }
    .btn-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-icon:hover {
        transform: scale(1.1);
        filter: brightness(0.95);
    }
    .object-fit-cover {
        object-fit: cover;
    }
    
    /* Style riêng cho Pagination Active */
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white !important;
    }
</style>

<script>
    // Kích hoạt Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Xử lý nút xóa AJAX (để trang không bị load lại)
    document.querySelectorAll('.btn-delete-user').forEach(button => {
        button.addEventListener('click', function() {
            if(!confirm('Bạn có chắc chắn muốn xóa user này không?')) return;
            
            const id = this.dataset.id;
            const row = document.getElementById('row-user-' + id);
            
            fetch('<?php echo BASE_URL; ?>/users/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    row.remove();
                    // Có thể thêm logic cập nhật lại số lượng hiển thị ở đây nếu muốn
                    alert(data.message);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
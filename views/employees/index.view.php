<div class="container-fluid py-4 bg-light">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Quản lý Nhân viên</h4>
            <p class="text-secondary small mb-0">Danh sách và hồ sơ nhân sự công ty</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="<?php echo BASE_URL; ?>/employees" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <input type="text" name="keyword" class="form-control ps-5 rounded-pill bg-light border-0" 
                               placeholder="Tên, Email, Mã NV..." 
                               value="<?php echo isset($keyword) ? e($keyword) : ''; ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="department_id" class="form-select rounded-pill bg-light border-0 cursor-pointer">
                        <option value="">-- Tất cả Phòng ban --</option>
                        <?php if(isset($departments)): ?>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>" <?php echo (isset($department_id) && $department_id == $dept['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($dept['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select rounded-pill bg-light border-0 cursor-pointer">
                        <option value="">-- Tất cả Trạng thái --</option>
                        <option value="active" <?php echo (isset($status) && $status === 'active') ? 'selected' : ''; ?>>Chính thức</option>
                        <option value="probation" <?php echo (isset($status) && $status === 'probation') ? 'selected' : ''; ?>>Thử việc</option>
                        <option value="terminated" <?php echo (isset($status) && $status === 'terminated') ? 'selected' : ''; ?>>Đã nghỉ</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-light rounded-pill border-0 flex-fill shadow-sm" data-bs-toggle="tooltip" title="Lọc dữ liệu">
                        <i class="bi bi-funnel"></i>
                    </button>
                    
                    <div class="dropdown flex-fill">
                        <button class="btn btn-success rounded-pill w-100 shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-download"></i>
                        </button>
                        <ul class="dropdown-menu shadow border-0 rounded-3">
                            <?php 
                                $query = http_build_query([
                                    'keyword' => $keyword ?? '',
                                    'status' => $status ?? '',
                                    'department_id' => $department_id ?? ''
                                ]);
                            ?>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/employees/export-excel?<?php echo $query; ?>">Excel</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/employees/export-pdf?<?php echo $query; ?>">PDF</a></li>
                        </ul>
                    </div>

                    <a href="<?php echo BASE_URL; ?>/employees/create" class="btn btn-primary rounded-pill flex-fill fw-medium shadow-sm text-nowrap">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-uppercase fw-bold small opacity-75" style="width: 80px;">Ảnh</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Nhân viên</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Liên hệ</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Vị trí</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Phòng ban</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Trạng thái</th>
                            <th class="pe-4 py-3 text-end text-secondary text-uppercase fw-bold small opacity-75">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($employees)): ?>
                            <?php foreach ($employees as $employee): ?>
                                <tr class="border-bottom-dashed" id="row-employee-<?php echo e($employee['id']); ?>">
                                    <td class="ps-4 py-3">
                                        <?php if (!empty($employee['photo_path']) && file_exists(BASE_PATH . 'public/' . $employee['photo_path'])): ?>
                                            <img src="<?php echo BASE_URL . '/' . e($employee['photo_path']); ?>" 
                                                 alt="Avatar" class="rounded-circle object-fit-cover border shadow-sm"
                                                 style="width: 48px; height: 48px;">
                                        <?php else: ?>
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold border border-primary border-opacity-10"
                                                 style="width: 48px; height: 48px; font-size: 1.1rem;">
                                                <?php echo strtoupper(mb_substr($employee['full_name'], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <h6 class="mb-0 text-dark fw-bold"><?php echo e($employee['full_name']); ?></h6>
                                        <span class="text-secondary small font-monospace">ID: #<?php echo e($employee['id']); ?></span>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <a href="mailto:<?php echo e($employee['email']); ?>" class="text-decoration-none text-secondary small hover-primary">
                                                <i class="bi bi-envelope me-1"></i><?php echo e($employee['email']); ?>
                                            </a>
                                            <?php if ($employee['phone']): ?>
                                                <a href="tel:<?php echo e($employee['phone']); ?>" class="text-decoration-none text-secondary small hover-primary">
                                                    <i class="bi bi-telephone me-1"></i><?php echo e($employee['phone']); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td><span class="fw-medium text-dark"><?php echo e($employee['job_title']); ?></span></td>

                                    <td>
                                        <span class="badge bg-light text-dark border fw-normal px-2 py-1 rounded-2">
                                            <?php echo e($employee['department_name'] ?? '--'); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php 
                                        $statusClass = match($employee['status']) {
                                            'active' => 'success',
                                            'probation' => 'warning',
                                            'terminated' => 'secondary',
                                            default => 'secondary'
                                        };
                                        $statusText = match($employee['status']) {
                                            'active' => 'Chính thức',
                                            'probation' => 'Thử việc',
                                            'terminated' => 'Đã nghỉ',
                                            default => $employee['status']
                                        };
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?>-subtle text-<?php echo $statusClass; ?> px-2 py-1 rounded-2 border border-<?php echo $statusClass; ?> border-opacity-10">
                                            <?php echo $statusText; ?>
                                        </span>
                                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                            Start: <?php echo date('d/m/Y', strtotime($employee['start_date'])); ?>
                                        </div>
                                    </td>

                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?php echo BASE_URL; ?>/employees/edit?id=<?php echo e($employee['id']); ?>" 
                                               class="btn btn-icon btn-light text-primary rounded-circle" 
                                               data-bs-toggle="tooltip" title="Sửa hồ sơ">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>

                                            <?php if (!empty($employee['contract_path'])): ?>
                                                <a href="<?php echo BASE_URL . '/' . e($employee['contract_path']); ?>" 
                                                   class="btn btn-icon btn-light text-info rounded-circle" 
                                                   target="_blank" data-bs-toggle="tooltip" title="Tải hợp đồng">
                                                    <i class="bi bi-file-earmark-text-fill"></i>
                                                </a>
                                            <?php endif; ?>

                                            <form action="<?php echo BASE_URL; ?>/employees/delete" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?');">
                                                <input type="hidden" name="id" value="<?php echo e($employee['id']); ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                                <button type="submit" class="btn btn-icon btn-light text-danger rounded-circle" data-bs-toggle="tooltip" title="Xóa">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center py-5 text-muted">Không tìm thấy nhân viên nào</td></tr>
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
    .custom-table td { vertical-align: middle; padding: 1rem 1.5rem; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .btn-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .hover-primary:hover { color: var(--bs-primary) !important; text-decoration: underline !important; }
    
    .bg-success-subtle { background-color: #d1e7dd !important; }
    .bg-warning-subtle { background-color: #fff3cd !important; }
    .bg-secondary-subtle { background-color: #e2e3e5 !important; }

    /* Dark Mode Overrides */
    [data-theme="dark"] .bg-light { background-color: #1e1e1e !important; color: #e0e0e0; }
    [data-theme="dark"] .text-dark { color: #fff !important; }
    [data-theme="dark"] .text-secondary, [data-theme="dark"] .text-muted { color: #a0a0a5 !important; }
    [data-theme="dark"] .table { color: #e0e0e0; border-color: #333; }
    [data-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.05); }
    [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background-color: #2b2b2b; border-color: #444; color: #fff; }
    [data-theme="dark"] .btn-light { background-color: rgba(255,255,255,0.1); color: #fff; border: none; }
    [data-theme="dark"] .bg-white { background-color: #1e1e1e !important; }
    [data-theme="dark"] .bg-success-subtle { background-color: rgba(25, 135, 84, 0.2) !important; color: #75b798; border-color: #198754; }
    [data-theme="dark"] .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.2) !important; color: #ffca2c; border-color: #ffc107; }
    [data-theme="dark"] .bg-secondary-subtle { background-color: rgba(108, 117, 125, 0.2) !important; color: #a7acb1; border-color: #6c757d; }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })
</script>
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
                                <option value="<?php echo $dept['id']; ?>" 
                                    <?php echo (isset($department_id) && $department_id == $dept['id']) ? 'selected' : ''; ?>>
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
                        <option value="resigned" <?php echo (isset($status) && $status === 'resigned') ? 'selected' : ''; ?>>Đã nghỉ</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-light rounded-pill border-0 flex-fill" data-bs-toggle="tooltip" title="Áp dụng bộ lọc">
                        <i class="bi bi-funnel"></i>
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-success rounded-pill shadow-sm dropdown-toggle text-nowrap" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download"></i> Xuất file
                        </button>
                        <ul class="dropdown-menu shadow border-0">
                            <?php 
                                $queryString = http_build_query([
                                    'keyword' => $keyword ?? '',
                                    'status' => $status ?? '',
                                    'department_id' => $department_id ?? ''
                                ]);
                            ?>
                            <li>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>/employees/export-excel?<?php echo $queryString; ?>">
                                    <i class="bi bi-file-earmark-excel text-success me-2"></i> Xuất Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>/employees/export-pdf?<?php echo $queryString; ?>">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Xuất PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <a href="<?php echo BASE_URL; ?>/employees/create" class="btn btn-primary rounded-pill flex-fill fw-medium shadow-sm text-nowrap">
                        <i class="bi bi-plus-lg"></i> Thêm
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small" style="width: 80px;">Ảnh</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Nhân viên</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Liên hệ</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Vị trí</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Phòng ban</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Trạng thái</th>
                            <th class="py-3 px-4 text-end text-secondary fw-semibold text-uppercase small">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($employees as $employee): ?>
                            <tr id="row-employee-<?php echo e($employee['id']); ?>" class="border-bottom-dashed">
                                <td class="px-4 py-3">
                                    <?php if (!empty($employee['photo_path']) && file_exists($employee['photo_path'])): ?>
                                        <img src="<?php echo BASE_URL . '/' . e($employee['photo_path']); ?>" 
                                             alt="<?php echo e($employee['full_name']); ?>" 
                                             class="rounded-circle object-fit-cover border shadow-sm"
                                             style="width: 48px; height: 48px;">
                                    <?php else: ?>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold border border-primary border-opacity-10"
                                             style="width: 48px; height: 48px; font-size: 1.1rem;">
                                            <?php echo strtoupper(mb_substr($employee['full_name'], 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="fw-bold text-dark d-block"><?php echo e($employee['full_name']); ?></span>
                                    <span class="text-muted small font-monospace">ID: #<?php echo e($employee['id']); ?></span>
                                </td>

                                <td class="px-4 py-3">
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

                                <td class="px-4 py-3">
                                    <span class="text-dark fw-medium"><?php echo e($employee['job_title']); ?></span>
                                </td>

                                <td class="px-4 py-3">
                                    <?php if (!empty($employee['department_name'])): ?>
                                        <span class="badge bg-light text-dark border fw-normal px-2 py-1 rounded-2">
                                            <?php echo e($employee['department_name']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">--</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?php if ($employee['status'] === 'active'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2 border border-success border-opacity-10">
                                            <i class="bi bi-check-circle me-1"></i>Chính thức
                                        </span>
                                    <?php elseif ($employee['status'] === 'probation'): ?>
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-2 border border-warning border-opacity-10">
                                            <i class="bi bi-hourglass-split me-1"></i>Thử việc
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-2 border border-secondary border-opacity-10">
                                            <i class="bi bi-dash-circle me-1"></i>Đã nghỉ
                                        </span>
                                    <?php endif; ?>
                                    <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                        Start: <?php echo e(date('d/m/Y', strtotime($employee['start_date']))); ?>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="<?php echo BASE_URL; ?>/employees/edit?id=<?php echo e($employee['id']); ?>" 
                                           class="btn btn-sm btn-light text-primary border-0 btn-icon"
                                           title="Sửa hồ sơ" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <?php if (!empty($employee['contract_path'])): ?>
                                            <a href="<?php echo BASE_URL . '/' . e($employee['contract_path']); ?>" 
                                               class="btn btn-sm btn-light text-info border-0 btn-icon"
                                               title="Tải Hợp đồng" target="_blank" data-bs-toggle="tooltip">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </a>
                                        <?php endif; ?>

                                        <form method="POST" action="<?php echo BASE_URL; ?>/employees/delete"
                                              class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn xóa nhân viên này?');">
                                            <input type="hidden" name="id" value="<?php echo e($employee['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-light text-danger border-0 btn-icon"
                                                    title="Xóa hồ sơ" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($employees)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-people text-secondary" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-2">Không tìm thấy nhân viên nào</p>
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
    .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.01); }
    .border-bottom-dashed { border-bottom: 1px dashed #dee2e6 !important; }
    .btn-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-icon:hover { transform: scale(1.1); filter: brightness(0.95); background-color: #e9ecef; }
    .hover-primary:hover { color: var(--bs-primary) !important; text-decoration: underline !important; }
    .cursor-pointer { cursor: pointer; }
    
    /* Active Pagination */
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white !important;
    }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
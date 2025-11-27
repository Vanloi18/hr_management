<div class="container-fluid py-4 bg-light">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Quản lý Phòng ban</h4>
            <p class="text-secondary small mb-0">Cơ cấu tổ chức và nhân sự trực thuộc</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center justify-content-between">
                <div class="col-md-6 col-lg-5">
                    <form action="<?php echo BASE_URL; ?>/departments" method="GET" class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <input type="text" name="keyword" class="form-control form-control-lg ps-5 rounded-pill bg-light border-0" 
                               placeholder="Tìm tên phòng ban..." 
                               value="<?php echo isset($keyword) ? e($keyword) : ''; ?>">
                    </form>
                </div>
                <div class="col-md-6 col-lg-7 text-md-end">
                    <a href="<?php echo BASE_URL; ?>/departments/create" class="btn btn-primary rounded-pill px-4 py-2 fw-medium shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Thêm Phòng ban
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small" style="width: 100px;">Mã PB</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Tên Phòng ban</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Nhân sự</th> <th class="py-3 px-4 text-end text-secondary fw-semibold text-uppercase small" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($departments as $department): ?>
                            <tr id="row-department-<?php echo e($department['id']); ?>" class="border-bottom-dashed">
                                <td class="px-4 py-3">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 px-3 py-2 rounded-pill font-monospace">
                                        #<?php echo e($department['id']); ?>
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 text-primary bg-primary bg-opacity-10" 
                                             style="width: 48px; height: 48px; font-weight: 700; font-size: 1.1rem; border: 1px solid rgba(var(--bs-primary-rgb), 0.1);">
                                            <?php echo strtoupper(mb_substr($department['name'], 0, 2)); ?>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block" style="font-size: 1rem;">
                                                <?php echo e($department['name']); ?>
                                            </span>
                                            <?php if (!empty($department['description'])): ?>
                                                <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                                    <?php echo e($department['description']); ?>
                                                </small>
                                            <?php else: ?>
                                                <small class="text-muted">
                                                    <i class="bi bi-building me-1"></i>Department
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <?php $count = $department['employee_count'] ?? 0; ?>
                                    <?php if ($count > 0): ?>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill border border-info border-opacity-10">
                                            <i class="bi bi-people-fill me-1"></i> <?php echo $count; ?> Nhân viên
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic ms-2">Chưa có nhân sự</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3 text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="<?php echo BASE_URL; ?>/departments/edit?id=<?php echo e($department['id']); ?>"
                                           class="btn btn-sm btn-light text-primary border-0 btn-icon" 
                                           title="Chỉnh sửa" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form method="POST" action="<?php echo BASE_URL; ?>/departments/delete" 
                                              class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phòng ban này?');">
                                            <input type="hidden" name="id" value="<?php echo e($department['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-light text-danger border-0 btn-icon" 
                                                    title="Xóa" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($departments)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-building-add text-secondary" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-2">Chưa có dữ liệu phòng ban</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="px-4 py-3 border-top bg-white rounded-bottom-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <span class="text-secondary small">
                        Hiển thị <strong><?php echo count($departments); ?></strong> trên tổng số <strong><?php echo $totalRecords ?? 0; ?></strong> phòng ban
                    </span>
                    <?php require BASE_PATH . 'views/partials/pagination.view.php'; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.015); }
    .border-bottom-dashed { border-bottom: 1px dashed #dee2e6 !important; }
    .btn-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-icon:hover { transform: scale(1.1); filter: brightness(0.95); background-color: #e9ecef; }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
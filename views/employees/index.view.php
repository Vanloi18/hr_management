<div class="container-fluid py-4 bg-light">
    <div class="card border-0 shadow-sm rounded-4">
        
        <div class="card-header bg-white border-0 pt-4 pb-3 px-4 rounded-top-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">
                        <?php echo e($title); ?>
                    </h4>
                    <p class="mb-0 text-secondary small">Danh sách và quản lý hồ sơ nhân sự</p>
                </div>

                <a href="<?php echo BASE_URL; ?>/employees/create" 
                   class="btn btn-primary rounded-pill px-4 fw-medium">
                    <i class="bi bi-plus-lg me-1"></i> Thêm mới
                </a>
            </div>
        </div>

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
                                    <?php if (!empty($employee['photo_path'])): ?>
                                        <img src="<?php echo BASE_URL . '/' . e($employee['photo_path']); ?>" 
                                             alt="<?php echo e($employee['full_name']); ?>" 
                                             class="rounded-circle object-fit-cover border"
                                             style="width: 48px; height: 48px;">
                                    <?php else: ?>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-primary fw-bold border"
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
                                        <a href="mailto:<?php echo e($employee['email']); ?>" class="text-decoration-none text-secondary small">
                                            <i class="bi bi-envelope me-1"></i><?php echo e($employee['email']); ?>
                                        </a>
                                        <?php if ($employee['phone']): ?>
                                            <a href="tel:<?php echo e($employee['phone']); ?>" class="text-decoration-none text-secondary small">
                                                <i class="bi bi-telephone me-1"></i><?php echo e($employee['phone']); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="text-dark fw-medium">
                                        <?php echo e($employee['job_title']); ?>
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    <?php if ($employee['department_name']): ?>
                                        <span class="badge bg-light text-dark border fw-normal px-2 py-1 rounded-2">
                                            <?php echo e($employee['department_name']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">Chưa cập nhật</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?php if ($employee['status'] === 'active'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2 border border-success border-opacity-10">
                                            <i class="bi bi-dot me-1"></i>Chính thức
                                        </span>
                                    <?php elseif ($employee['status'] === 'probation'): ?>
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-2 border border-warning border-opacity-10">
                                            <i class="bi bi-dot me-1"></i>Thử việc
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-2 border border-secondary border-opacity-10">
                                            <i class="bi bi-dot me-1"></i>Đã nghỉ
                                        </span>
                                    <?php endif; ?>
                                    <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                        Start: <?php echo e(date('d/m/Y', strtotime($employee['start_date']))); ?>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="<?php echo BASE_URL; ?>/employees/edit?id=<?php echo e($employee['id']); ?>" 
                                           class="btn btn-sm btn-light text-primary border-0"
                                           title="Sửa hồ sơ" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <?php if (!empty($employee['contract_path'])): ?>
                                            <a href="<?php echo BASE_URL . '/' . e($employee['contract_path']); ?>" 
                                               class="btn btn-sm btn-light text-info border-0"
                                               title="Tải Hợp đồng" target="_blank" data-bs-toggle="tooltip">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </a>
                                        <?php endif; ?>

                                        <form method="POST" action="<?php echo BASE_URL; ?>/employees/delete"
                                              class="form-delete-ajax d-inline-block"
                                              data-row-id="row-employee-<?php echo e($employee['id']); ?>">
                                            <?php csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($employee['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-light text-danger border-0"
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
                                    <div class="py-4">
                                        <i class="bi bi-people text-secondary opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-2">Chưa có dữ liệu nhân viên</p>
                                        <a href="<?php echo BASE_URL; ?>/employees/create" class="btn btn-sm btn-primary">
                                            Tạo mới ngay
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0 py-3 px-4 rounded-bottom-4">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    Tổng số: <strong class="text-dark"><?php echo count($employees); ?></strong> nhân viên
                </span>
                </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.01);
    }
    .border-bottom-dashed {
        border-bottom: 1px dashed #dee2e6 !important;
    }
    /* Tooltip fix nếu cần */
    .btn-light:hover {
        background-color: #e9ecef;
    }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
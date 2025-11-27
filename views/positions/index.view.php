<div class="container-fluid py-4 bg-light">
    <div class="card border-0 shadow-sm rounded-4">
        
        <div class="card-header bg-white border-0 pt-4 pb-3 px-4 rounded-top-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">
                        <?php echo e($title); ?>
                    </h4>
                    <p class="mb-0 text-secondary small">Quản lý các vị trí công việc và trạng thái tuyển dụng</p>
                </div>
                
                <a href="<?php echo BASE_URL; ?>/positions/create" 
                   class="btn btn-dark rounded-pill px-4 fw-medium shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Đăng tin mới
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small" style="width: 60px;">ID</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small" style="min-width: 250px;">Vị trí tuyển dụng</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Công ty / Lĩnh vực</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Trạng thái</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Người tạo</th>
                            <th class="py-3 px-4 text-end text-secondary fw-semibold text-uppercase small" style="width: 160px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($positions as $position): ?>
                            <tr id="row-position-<?php echo e($position['id']); ?>" class="border-bottom-dashed">
                                
                                <td class="px-4 py-3">
                                    <span class="text-muted font-monospace small">#<?php echo e($position['id']); ?></span>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-2 d-flex align-items-center justify-content-center me-3 text-primary bg-primary bg-opacity-10 flex-shrink-0" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-briefcase fs-6"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block mb-1">
                                                <?php echo e($position['title']); ?>
                                            </span>
                                            <?php if (!empty($position['description'])): ?>
                                                <p class="text-muted small mb-0 text-truncate" style="max-width: 250px;">
                                                    <?php echo e(mb_substr(strip_tags($position['description']), 0, 50)); ?>...
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="d-flex flex-column gap-1">
                                        <span class="text-dark fw-medium small">
                                            <i class="bi bi-building me-1 text-secondary"></i><?php echo e($position['company_name']); ?>
                                        </span>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 fw-normal align-self-start">
                                            <?php echo e($position['field_name']); ?>
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <?php if ($position['status'] === 'open'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2 border border-success border-opacity-10">
                                            <i class="bi bi-check-circle-fill me-1"></i>Đang mở
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-2 border border-secondary border-opacity-10">
                                            <i class="bi bi-lock-fill me-1"></i>Đã đóng
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-secondary fw-bold small" 
                                             style="width: 32px; height: 32px;">
                                            <?php echo strtoupper(substr($position['created_by_name'] ?? 'N', 0, 1)); ?>
                                        </div>
                                        <span class="small text-secondary"><?php echo e($position['created_by_name'] ?? 'N/A'); ?></span>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo BASE_URL; ?>/candidates/create?position_id=<?php echo e($position['id']); ?>" 
                                           class="btn btn-sm btn-light text-success border-0" 
                                           title="Thêm hồ sơ ứng viên"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-person-plus-fill"></i>
                                        </a>

                                        <a href="<?php echo BASE_URL; ?>/positions/edit?id=<?php echo e($position['id']); ?>" 
                                           class="btn btn-sm btn-light text-primary border-0" 
                                           title="Chỉnh sửa"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form method="POST" 
                                              action="<?php echo BASE_URL; ?>/positions/delete" 
                                              class="form-delete-ajax" 
                                              data-row-id="row-position-<?php echo e($position['id']); ?>">
                                            <?php csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($position['id']); ?>">
                                            
                                            <button type="submit" 
                                                    class="btn btn-sm btn-light text-danger border-0" 
                                                    title="Xóa tin"
                                                    data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($positions)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-clipboard-data text-secondary opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-2">Chưa có tin tuyển dụng nào</p>
                                        <a href="<?php echo BASE_URL; ?>/positions/create" class="btn btn-sm btn-dark rounded-pill px-3">
                                            Đăng tin đầu tiên
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
                    Tổng số: <strong class="text-dark"><?php echo count($positions); ?></strong> tin tuyển dụng
                </span>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-normal">
                   <i class="bi bi-circle-fill me-1 small" style="font-size: 0.5rem;"></i> Live Update
                </span>
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.015);
    }
    .border-bottom-dashed {
        border-bottom: 1px dashed #dee2e6 !important;
    }
    .btn-light:hover {
        background-color: #e9ecef;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
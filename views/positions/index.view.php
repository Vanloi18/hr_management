<div class="container-fluid py-4 bg-light">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Quản lý Tin tuyển dụng</h4>
            <p class="text-secondary small mb-0">Theo dõi các vị trí công việc và trạng thái tuyển dụng</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="<?php echo BASE_URL; ?>/positions" method="GET" class="row g-3">
                <div class="col-md-5">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <input type="text" name="keyword" class="form-control ps-5 rounded-pill bg-light border-0" 
                               placeholder="Tìm theo vị trí, tên công ty..." 
                               value="<?php echo isset($keyword) ? e($keyword) : ''; ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="recruiter_id" class="form-select rounded-pill bg-light border-0 cursor-pointer">
                        <option value="">-- Tất cả Công ty --</option>
                        <?php if(isset($recruitersList)): ?>
                            <?php foreach ($recruitersList as $rec): ?>
                                <option value="<?php echo $rec['id']; ?>" 
                                    <?php echo (isset($recruiter_id) && $recruiter_id == $rec['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($rec['company_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select rounded-pill bg-light border-0 cursor-pointer">
                        <option value="">-- Trạng thái --</option>
                        <option value="open" <?php echo (isset($status) && $status === 'open') ? 'selected' : ''; ?>>Đang mở</option>
                        <option value="closed" <?php echo (isset($status) && $status === 'closed') ? 'selected' : ''; ?>>Đã đóng</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-light rounded-pill border-0 flex-fill" data-bs-toggle="tooltip" title="Lọc dữ liệu">
                        <i class="bi bi-funnel"></i>
                    </button>
                    <a href="<?php echo BASE_URL; ?>/positions/create" class="btn btn-dark rounded-pill flex-fill fw-medium shadow-sm text-nowrap">
                        <i class="bi bi-plus-lg"></i> Đăng tin
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
                                            <i class="bi bi-building me-1 text-secondary"></i>
                                            <?php echo e($position['company_name'] ?? 'Chưa cập nhật'); ?>
                                        </span>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 fw-normal align-self-start">
                                            <?php echo e($position['field_name'] ?? 'N/A'); ?>
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
                                            <?php echo strtoupper(substr($position['created_by_name'] ?? 'A', 0, 1)); ?>
                                        </div>
                                        <span class="small text-secondary"><?php echo e($position['created_by_name'] ?? 'Admin'); ?></span>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo BASE_URL; ?>/candidates/create?position_id=<?php echo e($position['id']); ?>" 
                                           class="btn btn-sm btn-light text-success border-0 btn-icon" 
                                           title="Thêm hồ sơ ứng viên" data-bs-toggle="tooltip">
                                            <i class="bi bi-person-plus-fill"></i>
                                        </a>

                                        <a href="<?php echo BASE_URL; ?>/positions/edit?id=<?php echo e($position['id']); ?>" 
                                           class="btn btn-sm btn-light text-primary border-0 btn-icon" 
                                           title="Chỉnh sửa" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form method="POST" action="<?php echo BASE_URL; ?>/positions/delete" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin tuyển dụng này?');">
                                            <input type="hidden" name="id" value="<?php echo e($position['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-light text-danger border-0 btn-icon" 
                                                    title="Xóa tin" data-bs-toggle="tooltip">
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
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-clipboard-data" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-2">Không tìm thấy tin tuyển dụng nào</p>
                                        <a href="<?php echo BASE_URL; ?>/positions/create" class="btn btn-sm btn-dark rounded-pill px-3">
                                            Đăng tin ngay
                                        </a>
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
    .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.015); }
    .border-bottom-dashed { border-bottom: 1px dashed #dee2e6 !important; }
    .btn-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-icon:hover { transform: scale(1.1); filter: brightness(0.95); background-color: #e9ecef; }
    .text-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cursor-pointer { cursor: pointer; }
    
    /* Pagination Active Style */
    .pagination .page-item.active .page-link {
        background-color: #212529; /* Màu dark theo tông của trang này */
        border-color: #212529;
        color: white !important;
    }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
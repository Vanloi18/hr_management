<div class="container-fluid py-4 bg-light">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Quản lý Lĩnh vực</h4>
            <p class="text-secondary small mb-0">Danh mục ngành nghề và phân loại công việc</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center justify-content-between">
                <div class="col-md-6 col-lg-5">
                    <form action="<?php echo BASE_URL; ?>/fields" method="GET" class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <input type="text" name="keyword" 
                               class="form-control form-control-lg ps-5 rounded-pill bg-light border-0 fs-6" 
                               placeholder="Tìm tên lĩnh vực, mô tả..." 
                               value="<?php echo isset($keyword) ? e($keyword) : '' ?>">
                    </form>
                </div>

                <div class="col-md-6 col-lg-7 text-md-end">
                    <div class="d-flex gap-2 justify-content-md-end">
                        
                        <div class="dropdown">
                            <button class="btn btn-success rounded-pill px-3 py-2 shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-1"></i> Xuất file
                            </button>
                            <ul class="dropdown-menu shadow border-0 rounded-3 mt-2">
                                <?php $query = http_build_query(['keyword' => $keyword ?? '']); ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/fields/export-excel?<?php echo $query; ?>">
                                        <i class="bi bi-file-earmark-excel text-success me-2"></i> Xuất Excel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/fields/export-pdf?<?php echo $query; ?>">
                                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Xuất PDF
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <a href="<?php echo BASE_URL; ?>/fields/create" class="btn btn-primary rounded-pill px-4 py-2 fw-medium shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Thêm mới
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
                            <th class="ps-4 py-3 text-secondary text-uppercase fw-bold small opacity-75" style="width: 60px;">ID</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Tên lĩnh vực</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Mô tả</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Thống kê</th>
                            <th class="pe-4 py-3 text-end text-secondary text-uppercase fw-bold small opacity-75">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($fields)): ?>
                            <?php foreach ($fields as $field): ?>
                                <tr class="border-bottom-dashed">
                                    <td class="ps-4 py-3">
                                        <span class="text-muted small fw-medium font-monospace">#<?php echo e($field['id']); ?></span>
                                    </td>
                                    
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape icon-sm bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-tags-fill"></i>
                                            </div>
                                            <h6 class="mb-0 text-dark fw-bold"><?php echo e($field['field_name']); ?></h6>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="text-secondary small d-inline-block text-truncate" style="max-width: 300px;" title="<?php echo e($field['description']); ?>">
                                            <?php echo e($field['description'] ?? 'Chưa có mô tả'); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill border border-info border-opacity-10">
                                            <?php echo $field['position_count'] ?? 0; ?> tin tuyển dụng
                                        </span>
                                    </td>
                                    
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?php echo BASE_URL; ?>/fields/edit?id=<?php echo e($field['id']); ?>" 
                                               class="btn btn-icon btn-light text-primary rounded-circle" 
                                               data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            
                                            <form action="<?php echo BASE_URL; ?>/fields/delete" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lĩnh vực này?');">
                                                <input type="hidden" name="id" value="<?php echo e($field['id']); ?>">
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
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-tags display-6 text-secondary"></i>
                                        <p class="mt-3 mb-0 text-muted">Chưa có lĩnh vực nào được tạo</p>
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
    /* Table & Button Styles */
    .custom-table td { vertical-align: middle; padding: 1rem 1.5rem; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .btn-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .btn-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .bg-info-subtle { background-color: #eff6ff !important; }

    /* Dark Mode Overrides */
    [data-theme="dark"] .bg-light { background-color: #1e1e1e !important; color: #e0e0e0; }
    [data-theme="dark"] .text-dark { color: #fff !important; }
    [data-theme="dark"] .text-secondary { color: #a0a0a5 !important; }
    [data-theme="dark"] .table { color: #e0e0e0; border-color: #333; }
    [data-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.05); }
    [data-theme="dark"] .form-control { background-color: #2b2b2b; border-color: #444; color: #fff; }
    [data-theme="dark"] .btn-light { background-color: rgba(255,255,255,0.1); color: #fff; border: none; }
    [data-theme="dark"] .bg-white { background-color: #1e1e1e !important; }
    [data-theme="dark"] .bg-info-subtle { background-color: rgba(13, 202, 240, 0.2) !important; color: #6edff6 !important; border-color: rgba(13, 202, 240, 0.3) !important; }
    [data-theme="dark"] .pagination .page-link { background-color: #2b2b2b; border-color: #444; color: #fff; }
    [data-theme="dark"] .pagination .page-item.active .page-link { background-color: #0d6efd; border-color: #0d6efd; }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
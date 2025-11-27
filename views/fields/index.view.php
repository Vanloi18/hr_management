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
                        <input type="text" name="keyword" class="form-control form-control-lg ps-5 rounded-pill bg-light border-0" 
                               placeholder="Tìm tên lĩnh vực, mô tả..." 
                               value="<?php echo isset($keyword) ? e($keyword) : ''; ?>">
                    </form>
                </div>
                <div class="col-md-6 col-lg-7 text-md-end">
                    <a href="<?php echo BASE_URL; ?>/fields/create" class="btn btn-primary rounded-pill px-4 py-2 fw-medium shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Thêm Lĩnh vực
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
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small" style="width: 100px;">Mã LV</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Tên Lĩnh vực</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Tin tuyển dụng</th> <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Mô tả</th>
                            <th class="py-3 px-4 text-end text-secondary fw-semibold text-uppercase small" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach ($fields as $field): ?>
                            <tr id="row-field-<?php echo e($field['id']); ?>" class="border-bottom-dashed">
                                <td class="px-4 py-3">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 px-3 py-2 rounded-pill font-monospace">
                                        #<?php echo e($field['id']); ?>
                                    </span>
                                </td>
                                
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10" 
                                             style="width: 45px; height: 45px; font-weight: 700; font-size: 1.1rem;">
                                            <?php echo strtoupper(mb_substr($field['field_name'], 0, 2)); ?>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block"><?php echo e($field['field_name']); ?></span>
                                            <span class="small text-muted">Hoạt động</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <?php $count = $field['position_count'] ?? 0; ?>
                                    <?php if ($count > 0): ?>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill border border-info border-opacity-10">
                                            <i class="bi bi-briefcase-fill me-1"></i> <?php echo $count; ?> Vị trí
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic ms-1">Chưa có tin</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-4 py-3">
                                    <?php if (!empty($field['description'])): ?>
                                        <span class="text-secondary small text-truncate d-block" style="max-width: 300px;" title="<?php echo e($field['description']); ?>">
                                            <?php echo e($field['description']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic opacity-50">--</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo BASE_URL; ?>/fields/edit?id=<?php echo e($field['id']); ?>" 
                                           class="btn btn-sm btn-light text-primary border-0 btn-icon" 
                                           title="Chỉnh sửa" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <form method="POST" action="<?php echo BASE_URL; ?>/fields/delete" 
                                              class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lĩnh vực này?');">
                                            <input type="hidden" name="id" value="<?php echo e($field['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-light text-danger border-0 btn-icon" 
                                                    title="Xóa lĩnh vực" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($fields)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-tags text-secondary" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-2">Chưa có dữ liệu lĩnh vực</p>
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
                        Hiển thị <strong><?php echo count($fields); ?></strong> trên tổng số <strong><?php echo $totalRecords ?? 0; ?></strong> lĩnh vực
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
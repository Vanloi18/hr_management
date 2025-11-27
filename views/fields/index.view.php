<div class="container-fluid py-4 bg-light">
    <div class="card border-0 shadow-sm rounded-4">
        
        <div class="card-header bg-white border-0 pt-4 pb-3 px-4 rounded-top-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">
                        <?php echo e($title); ?>
                    </h4>
                    <p class="mb-0 text-secondary small">Danh mục các ngành nghề và lĩnh vực hoạt động</p>
                </div>
                
                <a href="<?php echo BASE_URL; ?>/fields/create" 
                   class="btn btn-primary rounded-pill px-4 fw-medium shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Thêm Lĩnh vực
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small" style="width: 100px;">Mã LV</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Tên Lĩnh vực</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Mô tả</th>
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
                                            <span class="small text-muted">Active</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-4 py-3">
                                    <?php if (!empty($field['description'])): ?>
                                        <span class="text-secondary small text-truncate d-block" style="max-width: 350px;">
                                            <?php echo e($field['description']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic opacity-50">Chưa có mô tả chi tiết</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo BASE_URL; ?>/fields/edit?id=<?php echo e($field['id']); ?>" 
                                           class="btn btn-sm btn-light text-primary border-0" 
                                           title="Chỉnh sửa"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <form method="POST" 
                                              action="<?php echo BASE_URL; ?>/fields/delete" 
                                              class="form-delete-ajax" 
                                              data-row-id="row-field-<?php echo e($field['id']); ?>">
                                            <?php csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($field['id']); ?>">
                                            
                                            <button type="submit" 
                                                    class="btn btn-sm btn-light text-danger border-0" 
                                                    title="Xóa lĩnh vực"
                                                    data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($fields)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-tags text-secondary opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-2">Chưa có dữ liệu lĩnh vực</p>
                                        <a href="<?php echo BASE_URL; ?>/fields/create" class="btn btn-sm btn-primary rounded-pill px-3">
                                            Thêm mới ngay
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
                    Tổng số: <strong class="text-dark"><?php echo count($fields); ?></strong> lĩnh vực
                </span>
                <span class="text-muted small">
                    <i class="bi bi-check-circle text-success me-1"></i>System Standard
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
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
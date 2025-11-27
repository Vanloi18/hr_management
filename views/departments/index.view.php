<div class="container-fluid py-4 bg-light">
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 pt-4 pb-3 px-4 rounded-top-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">
                        <?php echo e($title); ?>
                    </h4>
                    <p class="mb-0 text-secondary small">Cơ cấu tổ chức và danh mục phòng ban</p>
                </div>
                
                <a href="<?php echo BASE_URL; ?>/departments/create" 
                   class="btn btn-primary rounded-pill px-4 fw-medium">
                    <i class="bi bi-plus-lg me-1"></i> Thêm Phòng ban
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th scope="col" class="py-3 px-4 text-secondary fw-semibold text-uppercase small" style="width: 120px;">
                                Mã PB
                            </th>
                            <th scope="col" class="py-3 px-4 text-secondary fw-semibold text-uppercase small">
                                Tên Phòng ban
                            </th>
                            <th scope="col" class="py-3 px-4 text-end text-secondary fw-semibold text-uppercase small" style="width: 180px;">
                                Hành động
                            </th>
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
                                            <small class="text-muted">
                                                <i class="bi bi-building me-1"></i>Department
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="<?php echo BASE_URL; ?>/departments/edit?id=<?php echo e($department['id']); ?>"
                                           class="btn btn-sm btn-light text-primary border-0" 
                                           title="Chỉnh sửa"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form method="POST" 
                                              action="<?php echo BASE_URL; ?>/departments/delete" 
                                              class="form-delete-ajax d-inline-block" 
                                              data-row-id="row-department-<?php echo e($department['id']); ?>">
                                            <?php csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($department['id']); ?>">
                                            
                                            <button type="submit" 
                                                    class="btn btn-sm btn-light text-danger border-0" 
                                                    title="Xóa"
                                                    data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($departments)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="mb-3">
                                            <i class="bi bi-building-add text-secondary opacity-25" style="font-size: 3.5rem;"></i>
                                        </div>
                                        <h6 class="text-secondary fw-normal">Chưa có dữ liệu phòng ban</h6>
                                        <p class="text-muted small mb-3">Bắt đầu xây dựng cơ cấu tổ chức ngay</p>
                                        <a href="<?php echo BASE_URL; ?>/departments/create" class="btn btn-sm btn-primary px-4 rounded-pill">
                                            Tạo mới
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
                    Tổng số: <strong class="text-dark"><?php echo count($departments); ?></strong> phòng ban
                </span>
                <span class="text-muted small">
                    <i class="bi bi-check-all text-success me-1"></i>Đã đồng bộ
                </span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Hiệu ứng hover nhẹ nhàng */
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.015);
    }
    /* Đường kẻ đứt đoạn hiện đại */
    .border-bottom-dashed {
        border-bottom: 1px dashed #dee2e6 !important;
    }
    /* Nút action khi hover */
    .btn-light:hover {
        background-color: #e9ecef;
        transform: translateY(-1px);
        transition: all 0.2s;
    }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
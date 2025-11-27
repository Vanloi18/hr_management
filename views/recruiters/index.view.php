<div class="container-fluid py-4 bg-light">
    <div class="card border-0 shadow-sm rounded-4">
        
        <div class="card-header bg-white border-0 pt-4 pb-3 px-4 rounded-top-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">
                        <?php echo e($title); ?>
                    </h4>
                    <p class="mb-0 text-secondary small">Quản lý hồ sơ công ty và đối tác tuyển dụng</p>
                </div>
                
                <a href="<?php echo BASE_URL; ?>/recruiters/create" 
                   class="btn btn-primary rounded-pill px-4 fw-medium shadow-sm">
                    <i class="bi bi-building-add me-1"></i> Thêm Nhà tuyển dụng
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small" style="width: 80px;">ID</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Công ty</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Người liên hệ</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Email</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Điện thoại</th>
                            <th class="py-3 px-4 text-end text-secondary fw-semibold text-uppercase small" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach ($recruiters as $recruiter): ?>
                            <tr id="row-recruiter-<?php echo e($recruiter['id']); ?>" class="border-bottom-dashed">
                                
                                <td class="px-4 py-3">
                                    <span class="text-muted font-monospace small">#<?php echo e($recruiter['id']); ?></span>
                                </td>
                                
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 bg-indigo bg-opacity-10 text-indigo border border-indigo border-opacity-10" 
                                             style="width: 48px; height: 48px; font-weight: 700; font-size: 1.1rem; color: #6610f2; background-color: rgba(102, 16, 242, 0.1);">
                                            <?php echo strtoupper(substr($recruiter['company_name'], 0, 2)); ?>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block"><?php echo e($recruiter['company_name']); ?></span>
                                            <?php if (!empty($recruiter['address'])): ?>
                                                <small class="text-muted text-truncate d-block" style="max-width: 200px;">
                                                    <i class="bi bi-geo-alt me-1"></i><?php echo e(mb_substr($recruiter['address'], 0, 30)); ?>...
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-4 py-3">
                                    <?php if (!empty($recruiter['contact_person'])): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center me-2 text-secondary" style="width: 28px; height: 28px;">
                                                <i class="bi bi-person-fill small"></i>
                                            </div>
                                            <span class="text-dark"><?php echo e($recruiter['contact_person']); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">--</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-4 py-3">
                                    <?php if (!empty($recruiter['email'])): ?>
                                        <a href="mailto:<?php echo e($recruiter['email']); ?>" class="text-decoration-none text-secondary small hover-link">
                                            <?php echo e($recruiter['email']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">--</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-4 py-3">
                                    <?php if (!empty($recruiter['phone'])): ?>
                                        <span class="text-dark font-monospace small bg-light px-2 py-1 rounded border">
                                            <?php echo e($recruiter['phone']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">--</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo BASE_URL; ?>/recruiters/edit?id=<?php echo e($recruiter['id']); ?>" 
                                           class="btn btn-sm btn-light text-primary border-0" 
                                           title="Chỉnh sửa thông tin"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <form method="POST" 
                                              action="<?php echo BASE_URL; ?>/recruiters/delete" 
                                              class="form-delete-ajax" 
                                              data-row-id="row-recruiter-<?php echo e($recruiter['id']); ?>">
                                            <?php csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($recruiter['id']); ?>">
                                            
                                            <button type="submit" 
                                                    class="btn btn-sm btn-light text-danger border-0" 
                                                    title="Xóa nhà tuyển dụng"
                                                    data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($recruiters)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-buildings text-secondary opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-2">Chưa có đối tác tuyển dụng nào</p>
                                        <a href="<?php echo BASE_URL; ?>/recruiters/create" class="btn btn-sm btn-primary rounded-pill px-3">
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
                    Tổng số: <strong class="text-dark"><?php echo count($recruiters); ?></strong> nhà tuyển dụng
                </span>
                <span class="text-muted small">
                    <i class="bi bi-check2-all text-success me-1"></i>Active
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
    .hover-link:hover {
        color: var(--bs-primary) !important;
        text-decoration: underline !important;
    }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
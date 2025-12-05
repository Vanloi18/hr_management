<div class="container-fluid py-4 bg-light">
    
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Quản lý Nhà tuyển dụng</h4>
            <p class="text-secondary small mb-0">Danh sách các công ty và đối tác tuyển dụng</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center justify-content-between">
                <div class="col-md-6 col-lg-5">
                    <form action="<?php echo BASE_URL; ?>/recruiters" method="GET" class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <input type="text" 
                               name="keyword" 
                               class="form-control form-control-lg ps-5 rounded-pill bg-light border-0 fs-6" 
                               placeholder="Tìm công ty, email, liên hệ..." 
                               value="<?php echo isset($keyword) ? e($keyword) : '' ?>">
                    </form>
                </div>
                <div class="col-md-6 col-lg-7 text-md-end">
                    <a href="<?php echo BASE_URL; ?>/recruiters/create" class="btn btn-primary rounded-pill px-4 py-2 fw-medium shadow-sm">
                        <i class="bi bi-building-add me-1"></i> Thêm mới
                    </a>
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
                            <th class="ps-4 py-3 text-secondary text-uppercase fw-bold small opacity-75" style="width: 50px;">ID</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Công ty</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Người liên hệ</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Thông tin</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Địa chỉ</th>
                            <th class="pe-4 py-3 text-end text-secondary text-uppercase fw-bold small opacity-75">Hành động</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php if (!empty($recruiters)): ?>
                            <?php foreach ($recruiters as $recruiter): ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <span class="text-muted small fw-medium font-monospace">#<?php echo e($recruiter['id']); ?></span>
                                    </td>

                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($recruiter['logo']) && file_exists(BASE_PATH . 'public/uploads/logos/' . $recruiter['logo'])): ?>
                                                <img src="<?php echo BASE_URL . '/uploads/logos/' . $recruiter['logo']; ?>" 
                                                     class="rounded-3 me-3 border shadow-sm object-fit-contain bg-white" 
                                                     width="48" height="48" alt="Logo">
                                            <?php else: ?>
                                                <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3 fw-bold border border-primary border-opacity-10" 
                                                     style="width: 48px; height: 48px; font-size: 1.2rem;">
                                                    <?php echo strtoupper(substr($recruiter['company_name'], 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div>
                                                <h6 class="mb-0 text-dark fw-bold"><?php echo e($recruiter['company_name']); ?></h6>
                                                <span class="text-secondary small d-block mt-1">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    <?php echo date('d/m/Y', strtotime($recruiter['created_at'])); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-circle text-secondary me-2 fs-5 opacity-50"></i>
                                            <span class="fw-medium text-dark"><?php echo e($recruiter['contact_person']); ?></span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <a href="mailto:<?php echo e($recruiter['email']); ?>" class="text-decoration-none text-secondary small hover-primary">
                                                <i class="bi bi-envelope me-2"></i><?php echo e($recruiter['email']); ?>
                                            </a>
                                            <a href="tel:<?php echo e($recruiter['phone']); ?>" class="text-decoration-none text-secondary small hover-primary">
                                                <i class="bi bi-telephone me-2"></i><?php echo e($recruiter['phone']); ?>
                                            </a>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="text-secondary small d-inline-block text-truncate" style="max-width: 200px;" title="<?php echo e($recruiter['address']); ?>">
                                            <i class="bi bi-geo-alt me-1 opacity-50"></i>
                                            <?php echo e($recruiter['address']); ?>
                                        </span>
                                    </td>
                                    
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?php echo BASE_URL; ?>/recruiters/edit?id=<?php echo e($recruiter['id']); ?>" 
                                               class="btn btn-icon btn-light text-primary rounded-circle" 
                                               data-bs-toggle="tooltip" title="Sửa thông tin">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            
                                            <form action="<?php echo BASE_URL; ?>/recruiters/delete" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đối tác này?');">
                                                <input type="hidden" name="id" value="<?php echo e($recruiter['id']); ?>">
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
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-building-slash display-6 text-secondary"></i>
                                        <p class="mt-3 mb-0 text-muted">Không tìm thấy nhà tuyển dụng nào</p>
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
    /* Table Styling */
    .custom-table td { vertical-align: middle; padding: 1rem 1.5rem; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .btn-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .hover-primary:hover { color: var(--bs-primary) !important; text-decoration: underline !important; }

    /* Dark Mode Overrides */
    [data-theme="dark"] .bg-light { background-color: #1e1e1e !important; color: #e0e0e0; }
    [data-theme="dark"] .text-dark { color: #fff !important; }
    [data-theme="dark"] .text-secondary, [data-theme="dark"] .text-muted { color: #a0a0a5 !important; }
    [data-theme="dark"] .table { color: #e0e0e0; border-color: #333; }
    [data-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.05); }
    [data-theme="dark"] .form-control { background-color: #2b2b2b; border-color: #444; color: #fff; }
    [data-theme="dark"] .form-control::placeholder { color: #777; }
    [data-theme="dark"] .btn-light { background-color: rgba(255,255,255,0.1); color: #fff; border: none; }
    [data-theme="dark"] .bg-white { background-color: #1e1e1e !important; }
    [data-theme="dark"] .pagination .page-link { background-color: #2b2b2b; border-color: #444; color: #fff; }
    [data-theme="dark"] .pagination .page-item.active .page-link { background-color: #0d6efd; border-color: #0d6efd; }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
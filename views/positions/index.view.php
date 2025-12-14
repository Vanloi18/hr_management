<div class="container-fluid py-4 bg-light">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Quản lý Tin tuyển dụng</h4>
            <p class="text-secondary small mb-0">Theo dõi các vị trí công việc và trạng thái tuyển dụng</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center justify-content-between">
                
                <div class="col-lg-7">
                    <form action="<?php echo BASE_URL; ?>/positions" method="GET" class="row g-2">
                        <div class="col-md-5">
                            <div class="position-relative">
                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                                <input type="text" name="keyword" class="form-control ps-5 rounded-pill bg-light border-0" 
                                       placeholder="Tìm vị trí, công ty..." 
                                       value="<?php echo isset($keyword) ? e($keyword) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="recruiter_id" class="form-select rounded-pill bg-light border-0 cursor-pointer">
                                <option value="">-- Tất cả Công ty --</option>
                                <?php if(isset($recruitersList)): ?>
                                    <?php foreach ($recruitersList as $rec): ?>
                                        <option value="<?php echo $rec['id']; ?>" <?php echo (isset($recruiter_id) && $recruiter_id == $rec['id']) ? 'selected' : ''; ?>>
                                            <?php echo e($rec['company_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-light w-100 rounded-pill border-0 shadow-sm">
                                <i class="bi bi-funnel"></i> Lọc
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-lg-5 text-lg-end">
                    <div class="d-flex gap-2 justify-content-lg-end">
                        
                        <div class="dropdown">
                            <button class="btn btn-success rounded-pill px-3 py-2 shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-1"></i> Xuất file
                            </button>
                            <ul class="dropdown-menu shadow border-0 rounded-3 mt-2">
                                <?php 
                                    $query = http_build_query([
                                        'keyword' => $keyword ?? '',
                                        'status' => $status ?? '',
                                        'recruiter_id' => $recruiter_id ?? ''
                                    ]);
                                ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/positions/export-excel?<?php echo $query; ?>">
                                        <i class="bi bi-file-earmark-excel text-success me-2"></i> Xuất Excel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/positions/export-pdf?<?php echo $query; ?>">
                                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Xuất PDF
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <a href="<?php echo BASE_URL; ?>/positions/create" class="btn btn-primary rounded-pill px-4 py-2 fw-medium shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Đăng tin
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
                            <th class="ps-4" style="width: 50px;">ID</th>
                            <th style="width: 30%;">Vị trí tuyển dụng</th>
                            <th>Công ty</th>
                            <th>Lĩnh vực</th>
                            <th>Trạng thái</th>
                            <th class="pe-4 text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($positions)): ?>
                            <?php foreach ($positions as $pos): ?>
                                <tr class="border-bottom-dashed" id="row-pos-<?php echo $pos['id']; ?>">
                                    <td class="ps-4">
                                        <span class="text-muted small fw-medium font-monospace">#<?php echo e($pos['id']); ?></span>
                                    </td>
                                    
                                    <td class="py-3">
                                        <h6 class="mb-1 text-dark fw-bold"><?php echo e($pos['title']); ?></h6>
                                        <div class="small text-secondary">
                                            <i class="bi bi-cash me-1"></i><?php echo e($pos['salary_range'] ?? 'Thỏa thuận'); ?>
                                            <span class="mx-1">•</span>
                                            <i class="bi bi-geo-alt"></i> <?php echo e($pos['location'] ?? 'Toàn quốc'); ?>
                                        </div>
                                    </td>

                                    <td><span class="fw-medium text-dark"><?php echo e($pos['company_name']); ?></span></td>
                                    
                                    <td><span class="badge bg-light text-dark border fw-normal"><?php echo e($pos['field_name']); ?></span></td>

                                    <td>
                                        <?php if($pos['status'] === 'open'): ?>
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill border border-success border-opacity-10">
                                                <i class="bi bi-check-circle me-1"></i>Đang tuyển
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill border border-secondary border-opacity-10">
                                                <i class="bi bi-x-circle me-1"></i>Đã đóng
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" 
                                                    class="btn btn-icon btn-light text-info rounded-circle" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Xem chi tiết"
                                                    onclick="showDetailModal(<?php echo htmlspecialchars(json_encode($pos), ENT_QUOTES, 'UTF-8'); ?>)">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                            
                                            <a href="<?php echo BASE_URL; ?>/positions/edit?id=<?php echo e($pos['id']); ?>" 
                                               class="btn btn-icon btn-light text-primary rounded-circle" data-bs-toggle="tooltip" title="Sửa">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            
                                            <form action="<?php echo BASE_URL; ?>/positions/delete" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin này?');">
                                                <input type="hidden" name="id" value="<?php echo e($pos['id']); ?>">
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
                            <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có tin tuyển dụng nào.</td></tr>
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

<!-- Modal Chi tiết -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold" id="detailModalLabel">
                    <i class="bi bi-info-circle me-2"></i>Chi tiết tin tuyển dụng
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Thông tin cơ bản -->
                    <div class="col-12">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-briefcase-fill text-primary fs-3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="fw-bold text-dark mb-2" id="detail-title"></h4>
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill" id="detail-status-badge"></span>
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill" id="detail-field"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grid thông tin -->
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-building text-primary me-2"></i>
                                <small class="text-muted text-uppercase fw-semibold">Công ty</small>
                            </div>
                            <p class="fw-medium text-dark mb-0" id="detail-company"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-cash-stack text-success me-2"></i>
                                <small class="text-muted text-uppercase fw-semibold">Mức lương</small>
                            </div>
                            <p class="fw-medium text-dark mb-0" id="detail-salary"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                <small class="text-muted text-uppercase fw-semibold">Địa điểm</small>
                            </div>
                            <p class="fw-medium text-dark mb-0" id="detail-location"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar-check text-info me-2"></i>
                                <small class="text-muted text-uppercase fw-semibold">Hạn nộp</small>
                            </div>
                            <p class="fw-medium text-dark mb-0" id="detail-deadline"></p>
                        </div>
                    </div>

                    <!-- Mô tả công việc -->
                    <div class="col-12">
                        <div class="card bg-light border-0 rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-card-text text-primary me-2 fs-5"></i>
                                    <h6 class="fw-bold mb-0">Mô tả công việc</h6>
                                </div>
                                <div class="text-dark" id="detail-description"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Yêu cầu -->
                    <div class="col-12">
                        <div class="card bg-light border-0 rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-list-check text-warning me-2 fs-5"></i>
                                    <h6 class="fw-bold mb-0">Yêu cầu ứng viên</h6>
                                </div>
                                <div class="text-dark" id="detail-requirements"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quyền lợi -->
                    <div class="col-12">
                        <div class="card bg-light border-0 rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-gift text-success me-2 fs-5"></i>
                                    <h6 class="fw-bold mb-0">Quyền lợi</h6>
                                </div>
                                <div class="text-dark" id="detail-benefits"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="col-12">
                        <div class="border-top pt-3">
                            <div class="row g-2 text-muted small">
                                <div class="col-md-6">
                                    <i class="bi bi-hash me-1"></i><strong>ID:</strong> <span id="detail-id"></span>
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-clock me-1"></i><strong>Ngày đăng:</strong> <span id="detail-created"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-table td { vertical-align: middle; padding: 1rem 1.5rem; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .btn-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .bg-success-subtle { background-color: #d1e7dd !important; }
    .bg-secondary-subtle { background-color: #e2e3e5 !important; }
    .bg-primary-subtle { background-color: #cfe2ff !important; }
    
    .info-item { 
        padding: 1rem; 
        background: #f8f9fa; 
        border-radius: 0.75rem; 
        height: 100%;
        transition: all 0.2s;
    }
    .info-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    /* Dark Mode Overrides */
    [data-theme="dark"] .bg-light { background-color: #1e1e1e !important; color: #e0e0e0; }
    [data-theme="dark"] .text-dark { color: #fff !important; }
    [data-theme="dark"] .text-secondary { color: #a0a0a5 !important; }
    [data-theme="dark"] .table { color: #e0e0e0; border-color: #333; }
    [data-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.05); }
    [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background-color: #2b2b2b; border-color: #444; color: #fff; }
    [data-theme="dark"] .btn-light { background-color: rgba(255,255,255,0.1); color: #fff; border: none; }
    [data-theme="dark"] .bg-white { background-color: #1e1e1e !important; }
    [data-theme="dark"] .bg-success-subtle { background-color: rgba(25, 135, 84, 0.2) !important; color: #75b798; border-color: #198754; }
    [data-theme="dark"] .bg-secondary-subtle { background-color: rgba(108, 117, 125, 0.2) !important; color: #a7acb1; border-color: #6c757d; }
    [data-theme="dark"] .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.2) !important; color: #6ea8fe; }
    [data-theme="dark"] .modal-content { background-color: #2b2b2b; color: #e0e0e0; }
    [data-theme="dark"] .modal-footer { background-color: #1e1e1e !important; }
    [data-theme="dark"] .info-item { background-color: #1e1e1e; }
    [data-theme="dark"] .info-item:hover { background-color: #333; }
    [data-theme="dark"] .card.bg-light { background-color: #1e1e1e !important; }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })
</script>
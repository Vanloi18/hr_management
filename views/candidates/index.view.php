<div class="container-fluid py-4 bg-light">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Quản lý Hồ sơ Ứng viên</h4>
            <p class="text-secondary small mb-0">Theo dõi quy trình tuyển dụng và phỏng vấn</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="<?php echo BASE_URL; ?>/candidates" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <input type="text" name="keyword" class="form-control ps-5 rounded-pill bg-light border-0" 
                               placeholder="Tìm tên, email, SĐT..." 
                               value="<?php echo isset($keyword) ? e($keyword) : ''; ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="position_id" class="form-select rounded-pill bg-light border-0 cursor-pointer">
                        <option value="">-- Vị trí tuyển dụng --</option>
                        <?php if(isset($positionsList)): ?>
                            <?php foreach ($positionsList as $pos): ?>
                                <option value="<?php echo $pos['id']; ?>" <?php echo (isset($position_id) && $position_id == $pos['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($pos['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select rounded-pill bg-light border-0 cursor-pointer">
                        <option value="">-- Trạng thái --</option>
                        <option value="applied" <?php echo (isset($status) && $status === 'applied') ? 'selected' : ''; ?>>Mới ứng tuyển</option>
                        <option value="interviewing" <?php echo (isset($status) && $status === 'interviewing') ? 'selected' : ''; ?>>Phỏng vấn</option>
                        <option value="hired" <?php echo (isset($status) && $status === 'hired') ? 'selected' : ''; ?>>Đã tuyển</option>
                        <option value="rejected" <?php echo (isset($status) && $status === 'rejected') ? 'selected' : ''; ?>>Từ chối</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-light rounded-pill border-0 flex-fill shadow-sm" data-bs-toggle="tooltip" title="Lọc hồ sơ">
                        <i class="bi bi-funnel"></i>
                    </button>
                    
                    <div class="dropdown flex-fill">
                        <button class="btn btn-success rounded-pill w-100 shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-download"></i>
                        </button>
                        <ul class="dropdown-menu shadow border-0 rounded-3">
                            <?php 
                                $query = http_build_query([
                                    'keyword' => $keyword ?? '',
                                    'status' => $status ?? '',
                                    'position_id' => $position_id ?? ''
                                ]);
                            ?>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/candidates/export-excel?<?php echo $query; ?>">Excel</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/candidates/export-pdf?<?php echo $query; ?>">PDF</a></li>
                        </ul>
                    </div>

                    <a href="<?php echo BASE_URL; ?>/candidates/create" class="btn btn-primary rounded-pill flex-fill fw-medium shadow-sm">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-uppercase fw-bold small opacity-75" style="width: 50px;">ID</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Ứng viên</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Vị trí ứng tuyển</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Ngày nộp</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Trạng thái</th>
                            <th class="pe-4 py-3 text-end text-secondary text-uppercase fw-bold small opacity-75">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($candidates)): ?>
                            <?php foreach ($candidates as $candidate): ?>
                                <tr class="border-bottom-dashed" id="row-candidate-<?php echo e($candidate['id']); ?>">
                                    <td class="ps-4 py-3">
                                        <span class="text-muted small fw-medium font-monospace">#<?php echo e($candidate['id']); ?></span>
                                    </td>

                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center me-3 fw-bold border border-info border-opacity-10" 
                                                 style="width: 40px; height: 40px; font-size: 1rem;">
                                                <?php echo strtoupper(mb_substr($candidate['full_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-dark fw-bold"><?php echo e($candidate['full_name']); ?></h6>
                                                <a href="mailto:<?php echo e($candidate['email']); ?>" class="text-secondary small text-decoration-none hover-primary">
                                                    <?php echo e($candidate['email']); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border fw-normal px-2 py-1 rounded-2">
                                            <?php echo e($candidate['position_title'] ?? 'N/A'); ?>
                                        </span>
                                    </td>

                                    <td class="text-secondary small">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($candidate['applied_at'])); ?>
                                    </td>

                                    <td>
                                        <?php 
                                        $statusClass = match($candidate['status']) {
                                            'applied'      => 'warning',
                                            'interviewing' => 'primary',
                                            'hired'        => 'success',
                                            'rejected'     => 'secondary',
                                            default        => 'secondary'
                                        };
                                        $statusText = match($candidate['status']) {
                                            'applied'      => 'Mới ứng tuyển',
                                            'interviewing' => 'Phỏng vấn',
                                            'hired'        => 'Đã tuyển',
                                            'rejected'     => 'Từ chối',
                                            default        => $candidate['status']
                                        };
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?>-subtle text-<?php echo $statusClass; ?> px-2 py-1 rounded-2 border border-<?php echo $statusClass; ?> border-opacity-10">
                                            <?php echo $statusText; ?>
                                        </span>
                                    </td>

                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?php echo BASE_URL; ?>/candidates/edit?id=<?php echo e($candidate['id']); ?>" 
                                               class="btn btn-icon btn-light text-primary rounded-circle" 
                                               data-bs-toggle="tooltip" title="Xem & Sửa">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            
                                            <?php if (!empty($candidate['cv_file_path'])): ?>
                                                <a href="<?php echo BASE_URL . '/public/uploads/cvs/' . e($candidate['cv_file_path']); ?>" 
                                                   class="btn btn-icon btn-light text-info rounded-circle" 
                                                   target="_blank" data-bs-toggle="tooltip" title="Xem CV">
                                                    <i class="bi bi-file-earmark-person"></i>
                                                </a>
                                            <?php endif; ?>

                                            <form action="<?php echo BASE_URL; ?>/candidates/delete" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn xóa hồ sơ này?');">
                                                <input type="hidden" name="id" value="<?php echo e($candidate['id']); ?>">
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
                            <tr><td colspan="6" class="text-center py-5 text-muted">Không tìm thấy hồ sơ nào</td></tr>
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
    .custom-table td { vertical-align: middle; padding: 1rem 1.5rem; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .btn-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .hover-primary:hover { color: var(--bs-primary) !important; text-decoration: underline !important; }
    
    .bg-info-subtle { background-color: #cff4fc !important; }
    .bg-primary-subtle { background-color: #cfe2ff !important; }
    .bg-success-subtle { background-color: #d1e7dd !important; }
    .bg-warning-subtle { background-color: #fff3cd !important; }
    .bg-secondary-subtle { background-color: #e2e3e5 !important; }

    /* Dark Mode Overrides */
    [data-theme="dark"] .bg-light { background-color: #1e1e1e !important; color: #e0e0e0; }
    [data-theme="dark"] .text-dark { color: #fff !important; }
    [data-theme="dark"] .text-secondary, [data-theme="dark"] .text-muted { color: #a0a0a5 !important; }
    [data-theme="dark"] .table { color: #e0e0e0; border-color: #333; }
    [data-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.05); }
    [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background-color: #2b2b2b; border-color: #444; color: #fff; }
    [data-theme="dark"] .btn-light { background-color: rgba(255,255,255,0.1); color: #fff; border: none; }
    [data-theme="dark"] .bg-white { background-color: #1e1e1e !important; }
    
    [data-theme="dark"] .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.2) !important; color: #6ea8fe; border-color: #0d6efd; }
    [data-theme="dark"] .bg-success-subtle { background-color: rgba(25, 135, 84, 0.2) !important; color: #75b798; border-color: #198754; }
    [data-theme="dark"] .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.2) !important; color: #ffca2c; border-color: #ffc107; }
    [data-theme="dark"] .bg-secondary-subtle { background-color: rgba(108, 117, 125, 0.2) !important; color: #a7acb1; border-color: #6c757d; }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })
</script>
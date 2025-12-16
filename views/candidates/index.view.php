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
                                <option value="<?php echo $pos['id']; ?>" 
                                    <?php echo (isset($position_id) && $position_id == $pos['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($pos['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select rounded-pill bg-light border-0 cursor-pointer">
                        <option value="">-- Trạng thái hồ sơ --</option>
                        <option value="pending" <?php echo (isset($status) && $status === 'pending') ? 'selected' : ''; ?>>Chờ duyệt</option>
                        <option value="interviewing" <?php echo (isset($status) && $status === 'interviewing') ? 'selected' : ''; ?>>Phỏng vấn</option>
                        <option value="hired" <?php echo (isset($status) && $status === 'hired') ? 'selected' : ''; ?>>Đã tuyển</option>
                        <option value="rejected" <?php echo (isset($status) && $status === 'rejected') ? 'selected' : ''; ?>>Từ chối</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-light rounded-pill border-0 flex-fill" data-bs-toggle="tooltip" title="Lọc dữ liệu">
                        <i class="bi bi-funnel"></i>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-success rounded-pill shadow-sm dropdown-toggle text-nowrap" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download"></i> Xuất file
                        </button>
                        <ul class="dropdown-menu shadow border-0">
                            <?php 
                                $queryString = http_build_query([
                                    'keyword' => $keyword ?? '',
                                    'status' => $status ?? '',
                                    'position_id' => $position_id ?? '' 
                                ]);
                            ?>
                            <li>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>/candidates/export-excel?<?php echo $queryString; ?>">
                                    <i class="bi bi-file-earmark-excel text-success me-2"></i> Xuất Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>/candidates/export-pdf?<?php echo $queryString; ?>">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Xuất PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/candidates/create" class="btn btn-success rounded-pill flex-fill fw-medium shadow-sm text-nowrap">
                        <i class="bi bi-person-plus-fill"></i> Thêm
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Ứng viên</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Liên hệ</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Vị trí</th>
                            <th class="py-3 px-4 text-center text-secondary fw-semibold text-uppercase small">CV</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Trạng thái</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Ngày nộp</th>
                            <th class="py-3 px-4 text-end text-secondary fw-semibold text-uppercase small">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($candidates as $candidate): ?>
                            <tr id="row-candidate-<?php echo e($candidate['id']); ?>" class="border-bottom-dashed">
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-3 border border-success border-opacity-10" 
                                             style="width: 42px; height: 42px; font-weight: 600;">
                                            <?php echo strtoupper(mb_substr($candidate['full_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block"><?php echo e($candidate['full_name']); ?></span>
                                            <small class="text-muted" style="font-size: 0.75rem;">ID: #<?php echo e($candidate['id']); ?></small>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="d-flex flex-column gap-1">
                                        <a href="mailto:<?php echo e($candidate['email']); ?>" class="text-decoration-none text-secondary small hover-primary">
                                            <i class="bi bi-envelope me-2"></i><?php echo e($candidate['email']); ?>
                                        </a>
                                        <a href="tel:<?php echo e($candidate['phone']); ?>" class="text-decoration-none text-secondary small hover-primary">
                                            <i class="bi bi-telephone me-2"></i><?php echo e($candidate['phone']); ?>
                                        </a>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="fw-medium text-dark"><?php echo e($candidate['position_title']); ?></span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <?php if (!empty($candidate['cv_file_path'])): ?>
                                        <a href="<?php echo BASE_URL . '/' . e($candidate['cv_file_path']); ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-light text-danger border-0 px-3 rounded-pill"
                                           title="Xem file PDF" data-bs-toggle="tooltip">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">--</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3">
                                    <?php if ($candidate['status'] === 'hired'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2 border border-success border-opacity-10">
                                            <i class="bi bi-check-circle-fill me-1"></i>Đã tuyển
                                        </span>
                                    <?php elseif ($candidate['status'] === 'interviewing'): ?>
                                        <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-2 border border-info border-opacity-10">
                                            <i class="bi bi-camera-video-fill me-1"></i>Phỏng vấn
                                        </span>
                                    <?php elseif ($candidate['status'] === 'rejected'): ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2 border border-danger border-opacity-10">
                                            <i class="bi bi-x-circle-fill me-1"></i>Từ chối
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-2 border border-secondary border-opacity-10">
                                            <i class="bi bi-hourglass-split me-1"></i>Chờ duyệt
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="text-secondary small">
                                        <?php echo e(date('d/m/Y', strtotime($candidate['applied_at']))); ?>
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo BASE_URL; ?>/candidates/edit?id=<?php echo e($candidate['id']); ?>" 
                                           class="btn btn-sm btn-light text-primary border-0 btn-icon" 
                                           title="Cập nhật" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <form method="POST" action="<?php echo BASE_URL; ?>/candidates/delete" 
                                              class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hồ sơ này?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                            <input type="hidden" name="id" value="<?php echo e($candidate['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-light text-secondary border-0 btn-icon" 
                                                    title="Xóa hồ sơ" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($candidates)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-inbox text-secondary" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-2">Không tìm thấy hồ sơ nào</p>
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
    .hover-primary:hover { color: var(--bs-primary) !important; text-decoration: underline !important; }
    .cursor-pointer { cursor: pointer; }
    
    .pagination .page-item.active .page-link {
        background-color: #198754;
        border-color: #198754;
        color: white !important;
    }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
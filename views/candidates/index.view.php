<div class="container-fluid py-4 bg-light">
    <div class="card border-0 shadow-sm rounded-4">
        
        <div class="card-header bg-white border-0 pt-4 pb-3 px-4 rounded-top-4">
            <div class="row align-items-center gy-3">
                
                <div class="col-12 col-lg-4">
                    <h4 class="mb-1 fw-bold text-dark">
                        <?php echo e($title); ?>
                    </h4>
                    <p class="mb-0 text-secondary small">Quản lý hồ sơ và trạng thái phỏng vấn</p>
                </div>
                
                <div class="col-12 col-lg-8">
                    <div class="d-flex justify-content-lg-end gap-3 flex-wrap">
                        
                        <form method="GET" action="<?php echo BASE_URL; ?>/candidates" class="d-flex flex-grow-1" style="max-width: 350px;">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 ps-3 rounded-start-pill">
                                    <i class="bi bi-search text-secondary"></i>
                                </span>
                                <input type="text" 
                                       class="form-control bg-light border-0" 
                                       name="search" 
                                       placeholder="Tìm tên, email..." 
                                       value="<?php echo e($search ?? ''); ?>">
                                <button class="btn btn-light border-0 rounded-end-pill text-primary fw-medium" type="submit">
                                    Tìm kiếm
                                </button>
                            </div>
                        </form>
                        
                        <a href="<?php echo BASE_URL; ?>/candidates/create" 
                           class="btn btn-success rounded-pill px-4 fw-medium shadow-sm flex-shrink-0">
                            <i class="bi bi-plus-lg me-1"></i> Thêm ứng viên
                        </a>
                    </div>
                </div>
            </div>
        </div>

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
                                        <a href="mailto:<?php echo e($candidate['email']); ?>" class="text-decoration-none text-secondary small">
                                            <i class="bi bi-envelope me-2"></i><?php echo e($candidate['email']); ?>
                                        </a>
                                        <a href="tel:<?php echo e($candidate['phone']); ?>" class="text-decoration-none text-secondary small">
                                            <i class="bi bi-telephone me-2"></i><?php echo e($candidate['phone']); ?>
                                        </a>
                                    </div>
                                </td>
                                
                                <td class="px-4 py-3">
                                    <span class="fw-medium text-dark">
                                        <?php echo e($candidate['position_title']); ?>
                                    </span>
                                </td>
                                
                                <td class="px-4 py-3 text-center">
                                    <a href="<?php echo BASE_URL; ?>/<?php echo e($candidate['cv_file_path']); ?>" 
                                       target="_blank" 
                                       class="btn btn-sm btn-light text-danger border-0 px-3 rounded-pill"
                                       title="Xem file PDF" data-bs-toggle="tooltip">
                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                                    </a>
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
                                           class="btn btn-sm btn-light text-primary border-0" 
                                           title="Cập nhật trạng thái" data-bs-toggle="tooltip">
                                           <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <form method="POST" action="<?php echo BASE_URL; ?>/candidates/delete" 
                                              class="form-delete-ajax" 
                                              data-row-id="row-candidate-<?php echo e($candidate['id']); ?>">
                                            <?php csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($candidate['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-light text-secondary border-0" 
                                                    title="Xóa hồ sơ" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($candidates) && $currentPage > 1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <h6 class="text-muted">Không tìm thấy kết quả ở trang này</h6>
                                        <a href="<?php echo BASE_URL . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>?page=1" class="btn btn-sm btn-primary mt-2">
                                            Về trang 1
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php elseif (empty($candidates)): ?>
                             <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-inbox text-secondary opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3">Chưa có dữ liệu ứng viên</p>
                                        <a href="<?php echo BASE_URL; ?>/candidates/create" class="btn btn-sm btn-success px-4 rounded-pill">
                                            Thêm ứng viên mới
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="px-4 py-3 border-top">
                 <?php require BASE_PATH . 'views/partials/pagination.view.php'; ?>
            </div>
        </div>

        <div class="card-footer bg-white border-0 py-3 px-4 rounded-bottom-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-muted small">
                    <i class="bi bi-database me-1"></i>
                    Dữ liệu hồ sơ ứng tuyển
                </span>
                <div class="d-flex gap-2 opacity-75">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10">Hired</span>
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10">Interview</span>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10">Rejected</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Hover rows */
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.015);
    }
    /* Dashed border for clean look */
    .border-bottom-dashed {
        border-bottom: 1px dashed #dee2e6 !important;
    }
    /* Custom Input Group */
    .input-group .form-control:focus {
        box-shadow: none;
        background-color: #fff;
    }
    .input-group:focus-within .input-group-text {
        background-color: #fff !important;
    }
</style>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?php 
$errors = $_SESSION['_flash']['errors'] ?? [];
unset($_SESSION['_flash']['errors']); 
?>

<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden candidate-card">
                
                <div class="card-header border-0 py-4 bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-person-gear fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold"><?php echo e($title); ?></h4>
                            <p class="mb-0 opacity-75 small">Đang chỉnh sửa: <strong><?php echo e($candidate['full_name']); ?></strong></p>
                        </div>
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold shadow-sm d-none d-md-block">
                            ID: #<?php echo e($candidate['id']); ?>
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
                            <ul class="mb-0 ps-3 small">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo BASE_URL; ?>/candidates/update" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        <input type="hidden" name="id" value="<?php echo e($candidate['id']); ?>">

                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-uppercase text-secondary text-xs opacity-75 mb-3 ls-1">
                                    <i class="bi bi-person-vcard me-1"></i> Thông tin cơ bản
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($candidate['full_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($candidate['email']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control form-control-lg bg-light border-0" 
                                           value="<?php echo e($candidate['phone']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Cập nhật CV mới (Nếu có)</label>
                                    <input type="file" name="cv_file" class="form-control bg-light border-0">
                                    <?php if ($candidate['cv_file_path']): ?>
                                        <div class="mt-2">
                                            <a href="<?php echo BASE_URL . '/public/uploads/cvs/' . e($candidate['cv_file_path']); ?>" target="_blank" class="text-primary small text-decoration-none">
                                                <i class="bi bi-file-earmark-pdf me-1"></i>Xem CV hiện tại
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold text-uppercase text-secondary text-xs opacity-75 mb-3 ls-1">
                                    <i class="bi bi-briefcase me-1"></i> Trạng thái & Ghi chú
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Vị trí ứng tuyển</label>
                                    <select name="position_id" class="form-select form-select-lg bg-light border-0 cursor-pointer">
                                        <?php foreach ($positions as $pos): ?>
                                            <option value="<?php echo $pos['id']; ?>" <?php echo $candidate['position_id'] == $pos['id'] ? 'selected' : ''; ?>>
                                                <?php echo e($pos['title']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Trạng thái hồ sơ</label>
                                    <select name="status" id="status" class="form-select form-select-lg bg-light border-0 cursor-pointer">
                                        <option value="applied" <?php echo $candidate['status'] == 'applied' ? 'selected' : ''; ?>>Mới ứng tuyển</option>
                                        <option value="interviewing" <?php echo $candidate['status'] == 'interviewing' ? 'selected' : ''; ?>>Phỏng vấn</option>
                                        <option value="hired" <?php echo $candidate['status'] == 'hired' ? 'selected' : ''; ?>>Đã tuyển</option>
                                        <option value="rejected" <?php echo $candidate['status'] == 'rejected' ? 'selected' : ''; ?>>Từ chối</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Ghi chú</label>
                                    <textarea name="notes" rows="4" class="form-control bg-light border-0 rounded-3"><?php echo e($candidate['notes']); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="border-secondary border-opacity-10 my-4">

                        <div id="interview-section" class="d-none">
                            <h6 class="fw-bold text-uppercase text-primary text-xs opacity-75 mb-3 ls-1">
                                <i class="bi bi-calendar-event me-1"></i> Lịch phỏng vấn
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Ngày giờ phỏng vấn</label>
                                    <input type="datetime-local" name="interview_date" class="form-control bg-light border-0" 
                                           value="<?php echo $candidate['interview_date'] ? date('Y-m-d\TH:i', strtotime($candidate['interview_date'])) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Địa điểm / Link Online</label>
                                    <input type="text" name="interview_location" class="form-control bg-light border-0" 
                                           value="<?php echo e($candidate['interview_location']); ?>" placeholder="Phòng họp A hoặc Google Meet Link...">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-5">
                            <a href="<?php echo BASE_URL; ?>/candidates" class="btn btn-light btn-lg px-4 rounded-pill border-0 text-secondary">Hủy</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm fw-medium">
                                <i class="bi bi-check2-circle me-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus { background-color: #fff; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15); border: 1px solid #86b7fe !important; }
    /* Dark Mode */
    [data-theme="dark"] .candidate-card { background-color: #1e1e1e !important; border: 1px solid #333 !important; }
    [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background-color: #2b2b2b !important; color: #fff !important; border-color: #444 !important; }
    [data-theme="dark"] .form-control:focus { background-color: #333 !important; border-color: #0d6efd !important; }
    [data-theme="dark"] .text-secondary { color: #a0a0a0 !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const interviewSection = document.getElementById('interview-section');

        function toggleInterview() {
            if (statusSelect.value === 'interviewing') {
                interviewSection.classList.remove('d-none');
            } else {
                interviewSection.classList.add('d-none');
            }
        }

        statusSelect.addEventListener('change', toggleInterview);
        toggleInterview(); // Chạy lần đầu khi load trang
    });
</script>
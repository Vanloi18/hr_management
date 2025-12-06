<div class="container-fluid py-4 bg-light">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Quản lý Phòng ban</h4>
            <p class="text-secondary small mb-0">Cơ cấu tổ chức và nhân sự trực thuộc</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center justify-content-between">
                <div class="col-md-6 col-lg-5">
                    <form action="<?php echo BASE_URL; ?>/departments" method="GET" class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <input type="text" name="keyword" 
                               class="form-control form-control-lg ps-5 rounded-pill bg-light border-0 fs-6" 
                               placeholder="Tìm tên phòng ban..." 
                               value="<?php echo isset($keyword) ? e($keyword) : '' ?>">
                    </form>
                </div>
                <div class="col-md-6 col-lg-7 text-md-end">
                    <div class="d-flex gap-2 justify-content-md-end">
                        
                        <div class="dropdown">
                            <button class="btn btn-success rounded-pill px-3 py-2 shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-1"></i> Xuất file
                            </button>
                            <ul class="dropdown-menu shadow border-0 rounded-3 mt-2">
                                <?php $query = http_build_query(['keyword' => $keyword ?? '']); ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/departments/export-excel?<?php echo $query; ?>">
                                        <i class="bi bi-file-earmark-excel text-success me-2"></i> Xuất Excel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/departments/export-pdf?<?php echo $query; ?>">
                                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Xuất PDF
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <a href="<?php echo BASE_URL; ?>/departments/create" class="btn btn-primary rounded-pill px-4 py-2 fw-medium shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Thêm mới
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
                            <th class="ps-4 py-3 text-secondary text-uppercase fw-bold small opacity-75" style="width: 60px;">ID</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Tên phòng ban</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Nhân sự</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small opacity-75">Ngày tạo</th>
                            <th class="pe-4 py-3 text-end text-secondary text-uppercase fw-bold small opacity-75">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($departments)): ?>
                            <?php foreach ($departments as $dept): ?>
                                <tr class="border-bottom-dashed" id="row-dept-<?php echo $dept['id']; ?>">
                                    <td class="ps-4 py-3">
                                        <span class="text-muted small fw-medium font-monospace">#<?php echo e($dept['id']); ?></span>
                                    </td>
                                    
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape icon-sm bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-briefcase-fill"></i>
                                            </div>
                                            <h6 class="mb-0 text-dark fw-bold"><?php echo e($dept['name']); ?></h6>
                                        </div>
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-outline-info rounded-pill px-3 view-employees-btn" 
                                                data-id="<?php echo $dept['id']; ?>"
                                                data-bs-toggle="modal" data-bs-target="#employeesModal">
                                            <i class="bi bi-people-fill me-1"></i> <?php echo $dept['employee_count']; ?> nhân viên
                                        </button>
                                    </td>

                                    <td>
                                        <span class="text-secondary small">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($dept['created_at'])); ?>
                                        </span>
                                    </td>
                                    
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?php echo BASE_URL; ?>/departments/edit?id=<?php echo e($dept['id']); ?>" 
                                               class="btn btn-icon btn-light text-primary rounded-circle" 
                                               data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            
                                            <form action="<?php echo BASE_URL; ?>/departments/delete" 
                                                method="POST" 
                                                class="d-inline form-delete-ajax" 
                                                data-row-id="row-dept-<?php echo $dept['id']; ?>"
                                                data-confirm="Cảnh báo: Xóa phòng ban sẽ ảnh hưởng đến nhân sự thuộc phòng ban này.\nBạn có chắc chắn muốn xóa?">
                                                
                                                <input type="hidden" name="id" value="<?php echo e($dept['id']); ?>">
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
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-briefcase display-6 text-secondary"></i>
                                        <p class="mt-3 mb-0 text-muted">Chưa có phòng ban nào</p>
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

<div class="modal fade" id="employeesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-people-fill me-2"></i>Nhân sự thuộc phòng: <span id="modal-dept-name">...</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="modal-loader" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-secondary">Đang tải dữ liệu...</p>
                </div>
                <div class="table-responsive d-none" id="modal-content">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Họ tên</th>
                                <th>Ngày vào làm</th>
                            </tr>
                        </thead>
                        <tbody id="employee-list-body"></tbody>
                    </table>
                </div>
                <div id="no-employees-message" class="text-center py-5 d-none">
                    <i class="bi bi-people text-secondary display-6 opacity-25"></i>
                    <p class="text-muted mt-2">Chưa có nhân viên nào trong phòng này.</p>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Table & Button */
    .custom-table td { vertical-align: middle; padding: 1rem 1.5rem; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .btn-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .btn-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    
    /* Dark Mode Overrides */
    [data-theme="dark"] .bg-light { background-color: #1e1e1e !important; color: #e0e0e0; }
    [data-theme="dark"] .text-dark { color: #fff !important; }
    [data-theme="dark"] .text-secondary { color: #a0a0a5 !important; }
    [data-theme="dark"] .table { color: #e0e0e0; border-color: #333; }
    [data-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.05); }
    [data-theme="dark"] .form-control { background-color: #2b2b2b; border-color: #444; color: #fff; }
    [data-theme="dark"] .bg-white { background-color: #1e1e1e !important; }
    [data-theme="dark"] .modal-content { background-color: #1e1e1e; border: 1px solid #333; }
    [data-theme="dark"] .modal-header { border-bottom: 1px solid #333; }
    [data-theme="dark"] .modal-footer { background-color: #1e1e1e !important; border-top: 1px solid #333; }
</style>

<script>
    // 1. Tooltip
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    document.addEventListener('click', function(e) {
        // Tìm nút xóa
        const button = e.target.closest('.btn-delete');
        if (!button) return;

        e.preventDefault();

        if(!confirm('Bạn có chắc chắn muốn xóa phòng ban này không?')) return;

        // Tìm form chứa nút bấm để lấy dữ liệu
        const form = button.closest('form');
        const id = form.querySelector('input[name="id"]').value;
        const token = form.querySelector('input[name="csrf_token"]').value;
        const row = document.getElementById('row-dept-' + id);

        if(row) row.style.opacity = '0.5';

        fetch('<?php echo BASE_URL; ?>/departments/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id) + '&csrf_token=' + encodeURIComponent(token)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) { 
                if(row) row.remove(); 
                alert(data.message); 
            } else { 
                if(row) row.style.opacity = '1';
                alert(data.message); 
            }
        })
        .catch(err => {
            if(row) row.style.opacity = '1';
            console.error(err);
            alert('Lỗi kết nối server');
        });
    });

    // 3. Xem danh sách nhân viên (Modal)
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-employees-btn');
        if (!btn) return;

        const deptId = btn.dataset.id;
        const modalTitle = document.getElementById('modal-dept-name');
        const listBody = document.getElementById('employee-list-body');
        const loader = document.getElementById('modal-loader');
        const content = document.getElementById('modal-content');
        const emptyMsg = document.getElementById('no-employees-message');

        // Reset modal
        modalTitle.textContent = '...';
        listBody.innerHTML = '';
        loader.classList.remove('d-none');
        content.classList.add('d-none');
        emptyMsg.classList.add('d-none');

        // Gọi API
        fetch(`<?php echo BASE_URL; ?>/departments/api/employees?id=${deptId}`)
            .then(res => res.json())
            .then(data => {
                loader.classList.add('d-none');
                if (data.success) {
                    modalTitle.textContent = data.department_name;
                    if (data.employees.length > 0) {
                        content.classList.remove('d-none');
                        data.employees.forEach(emp => {
                            const row = listBody.insertRow();
                            row.innerHTML = `
                                <td class="ps-4"><span class="badge bg-secondary bg-opacity-10 text-secondary border px-2">#${emp.id}</span></td>
                                <td class="fw-bold">${emp.full_name}</td>
                                <td>${emp.created_at ? emp.created_at.substring(0,10) : 'N/A'}</td>
                            `;
                        });
                    } else {
                        emptyMsg.classList.remove('d-none');
                    }
                } else {
                    alert('Lỗi: ' + (data.error || 'Không tải được dữ liệu'));
                }
            })
            .catch(err => {
                loader.classList.add('d-none');
                alert('Lỗi kết nối server');
            });
    });
</script>
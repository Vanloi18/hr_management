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
                        <input type="text" name="keyword" class="form-control form-control-lg ps-5 rounded-pill bg-light border-0" 
                               placeholder="Tìm tên phòng ban..." 
                               value="<?php echo isset($keyword) ? e($keyword) : ''; ?>">
                    </form>
                </div>
                <div class="col-md-6 col-lg-7 text-md-end">
                    <a href="<?php echo BASE_URL; ?>/departments/create" class="btn btn-primary rounded-pill px-4 py-2 fw-medium shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Thêm Phòng ban
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small" style="width: 100px;">Mã PB</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Tên Phòng ban</th>
                            <th class="py-3 px-4 text-secondary fw-semibold text-uppercase small">Nhân sự</th>
                            <th class="py-3 px-4 text-end text-secondary fw-semibold text-uppercase small" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($departments as $department): ?>
                            <tr id="row-department-<?php echo e($department['id']); ?>" 
                                class="border-bottom-dashed"
                                data-bs-toggle="modal" 
                                data-bs-target="#employeesModal"
                                data-department-id="<?php echo e($department['id']); ?>" 
                                data-department-name="<?php echo e($department['name']); ?>"
                                style="cursor: pointer;">
                                
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
                                            <span class="fw-bold text-dark d-block text-decoration-none hover-primary" style="font-size: 1rem;">
                                                <?php echo e($department['name']); ?>
                                            </span>
                                            <?php if (!empty($department['description'])): ?>
                                                <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                                    <?php echo e($department['description']); ?>
                                                </small>
                                            <?php else: ?>
                                                <small class="text-muted">
                                                    <i class="bi bi-building me-1"></i>Department
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <?php $count = $department['employee_count'] ?? 0; ?>
                                    <?php if ($count > 0): ?>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill border border-info border-opacity-10">
                                            <i class="bi bi-people-fill me-1"></i> <?php echo $count; ?> Nhân viên
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic ms-2">Chưa có nhân sự</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3 text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="<?php echo BASE_URL; ?>/departments/edit?id=<?php echo e($department['id']); ?>"
                                           class="btn btn-sm btn-light text-primary border-0 btn-icon" 
                                           title="Chỉnh sửa" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form method="POST" action="<?php echo BASE_URL; ?>/departments/delete" 
                                              class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phòng ban này?');">
                                            <input type="hidden" name="id" value="<?php echo e($department['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-light text-danger border-0 btn-icon" 
                                                    title="Xóa" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($departments)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-building-add text-secondary" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-2">Chưa có dữ liệu phòng ban</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="px-4 py-3 border-top bg-white rounded-bottom-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <span class="text-secondary small">
                        Hiển thị <strong><?php echo count($departments); ?></strong> trên tổng số <strong><?php echo $totalRecords ?? 0; ?></strong> phòng ban
                    </span>
                    <?php require BASE_PATH . 'views/partials/pagination.view.php'; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="employeesModal" tabindex="-1" aria-labelledby="employeesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="employeesModalLabel">
                    Nhân viên thuộc Phòng ban: <span id="department-name-title" class="text-primary"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-2 px-3 text-secondary fw-semibold small">ID</th>
                                <th class="py-2 px-3 text-secondary fw-semibold small">Họ tên</th>
                                <th class="py-2 px-3 text-secondary fw-semibold small">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody id="employees-list-body">
                            </tbody>
                    </table>
                </div>
                <div id="loading-spinner" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="text-muted small mt-2">Đang tải danh sách nhân viên...</p>
                </div>
                <div id="no-employees-message" class="text-center py-5 d-none">
                    <i class="bi bi-person-slash text-secondary" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3 mb-0 fw-medium">Phòng ban này hiện chưa có nhân viên nào.</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>


<style>
    .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.025); } /* Làm nổi bật hơn khi click */
    .border-bottom-dashed { border-bottom: 1px dashed #dee2e6 !important; }
    .btn-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-icon:hover { transform: scale(1.1); filter: brightness(0.95); background-color: #e9ecef; }
    /* Thêm style để người dùng biết có thể click */
    .table-hover tbody tr:hover { 
        cursor: pointer;
        background-color: #f8f9fa;
    }
</style>

<script>
  // Logic AJAX cho Modal
var employeesModal = document.getElementById('employeesModal');
employeesModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; 
    
    var departmentId = button.getAttribute('data-department-id');
    var departmentName = button.getAttribute('data-department-name');
    
    // Các phần tử DOM cần thao tác
    var modalTitle = document.getElementById('department-name-title');
    var employeeListBody = document.getElementById('employees-list-body');
    var loadingSpinner = document.getElementById('loading-spinner');
    var noEmployeesMessage = document.getElementById('no-employees-message');
    
    // Reset trạng thái và hiển thị loading
    modalTitle.textContent = departmentName;
    employeeListBody.innerHTML = '';
    noEmployeesMessage.classList.add('d-none');
    loadingSpinner.classList.remove('d-none');

    // 2. Gọi AJAX để lấy dữ liệu nhân viên
    fetch(`<?php echo BASE_URL; ?>/departments/employees?id=${departmentId}`)
        .then(response => {
            if (!response.ok) {
                // Nếu lỗi 403 (Forbidden) hoặc 404 (Not Found), ném ra lỗi với status code
                throw new Error(`Lỗi HTTP ${response.status}: API truy cập thất bại.`);
            }
            return response.json();
        })
        .then(data => {
            loadingSpinner.classList.add('d-none');
            
            if (data.success && data.employees && data.employees.length > 0) {
                // 3. Hiển thị dữ liệu
                data.employees.forEach(employee => {
                    const row = employeeListBody.insertRow();
                    
                    const idCell = row.insertCell();
                    idCell.innerHTML = `<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 px-2 py-1 rounded-pill font-monospace">#${employee.id}</span>`;
                    
                    const nameCell = row.insertCell();
                    nameCell.textContent = employee.full_name;
                    
                    const dateCell = row.insertCell();
                    const dateString = employee.created_at ? employee.created_at.substring(0, 10) : 'N/A';
                    dateCell.textContent = dateString.split('-').reverse().join('/');
                });
            } else {
                // 4. Không có nhân viên (hoặc data trả về rỗng)
                noEmployeesMessage.classList.remove('d-none');
            }
        })
        .catch(error => {
            loadingSpinner.classList.add('d-none');
            console.error('LỖI GỌI API:', error);
            
            // Hiển thị lỗi rõ ràng cho người dùng
            noEmployeesMessage.classList.remove('d-none');
            document.getElementById('no-employees-message').innerHTML = `<p class="text-danger mt-2 mb-0 fw-medium">${error.message}</p>`;
        });
});
</script>
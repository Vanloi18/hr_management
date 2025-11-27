<?php
// 1. Kiểm tra logic cơ bản
if (!isset($totalPages) || $totalPages <= 1) {
    return;
}

// 2. Lấy tham số URL hiện tại để giữ lại bộ lọc (keyword, status...)
$queryParams = $_GET;

// Hàm helper tạo link
function getPageLink($params, $page) {
    $params['page'] = $page;
    return '?' . http_build_query($params);
}

// --- CẤU HÌNH ĐỘ GỌN CỦA PHÂN TRANG ---
$range = 1; // Chỉ hiển thị 1 trang bên cạnh trang hiện tại (Ví dụ: ... 4 [5] 6 ...)
// ---------------------------------------
?>

<nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination pagination-sm justify-content-end mb-0 gap-1 flex-wrap">

        <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link rounded-2 border-0 d-flex align-items-center justify-content-center" 
               href="<?php echo ($currentPage <= 1) ? '#' : getPageLink($queryParams, $currentPage - 1); ?>" 
               style="width: 36px; height: 36px;">
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php 
                // Logic: Luôn hiện trang 1 và trang cuối ($totalPages)
                // Và hiện các trang nằm trong khoảng $range xung quanh trang hiện tại
                if ($i == 1 || $i == $totalPages || ($i >= $currentPage - $range && $i <= $currentPage + $range)): 
            ?>
                <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                    <a class="page-link rounded-2 border-0 fw-medium d-flex align-items-center justify-content-center" 
                       href="<?php echo getPageLink($queryParams, $i); ?>"
                       style="min-width: 36px; height: 36px; padding: 0 10px;">
                        <?php echo $i; ?>
                    </a>
                </li>
            
            <?php elseif (
                ($i == $currentPage - $range - 1) || 
                ($i == $currentPage + $range + 1)
            ): 
                // Logic: Chỉ hiển thị dấu "..." nếu có khoảng cách
            ?>
                <li class="page-item disabled">
                    <span class="page-link border-0 bg-transparent text-muted d-flex align-items-end justify-content-center" 
                          style="width: 36px; height: 36px;">...</span>
                </li>
            <?php endif; ?>
        <?php endfor; ?>

        <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link rounded-2 border-0 d-flex align-items-center justify-content-center" 
               href="<?php echo ($currentPage >= $totalPages) ? '#' : getPageLink($queryParams, $currentPage + 1); ?>"
               style="width: 36px; height: 36px;">
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>

    </ul>
</nav>

<style>
    /* Ép buộc hiển thị hàng ngang */
    .pagination {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
    }

    .pagination .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        transition: all 0.2s ease;
        text-decoration: none; /* Xóa gạch chân */
    }
    
    .pagination .page-link:hover {
        background-color: #e9ecef;
        color: #0d6efd;
        transform: translateY(-1px);
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        color: #ffffff;
        box-shadow: 0 2px 5px rgba(13, 110, 253, 0.3);
    }
    
    .pagination .page-item.disabled .page-link {
        color: #adb5bd;
        background-color: transparent;
        cursor: default;
    }
</style>
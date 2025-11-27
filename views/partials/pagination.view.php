<?php
// views/partials/pagination.view.php

// --- DEBUG: Hiển thị thông số để kiểm tra (Xóa sau khi fix xong) ---
if (isset($totalRecords)) {
    // echo "<div class='alert alert-info py-1 small'>Debug: Tìm thấy {$totalRecords} kết quả | Tổng {$totalPages} trang.</div>";
}
// ------------------------------------------------------------------

// 1. Kiểm tra logic
if (empty($totalPages) || $totalPages <= 1) {
    return; // Nếu chỉ có 1 trang thì ẩn phân trang
}

// 2. Thiết lập Link chuẩn (Dùng BASE_URL để tránh lỗi server)
// Lưu ý: Đảm bảo biến BASE_URL không có dấu / ở cuối
$targetUrl = BASE_URL . '/users'; 

// 3. Giữ lại tham số tìm kiếm (role, keyword)
$queryParams = $_GET;
unset($queryParams['page']); // Xóa page cũ

// Hàm tạo link an toàn
function buildPageLink($url, $params, $page) {
    $params['page'] = $page;
    return $url . '?' . http_build_query($params);
}

$window = 2; // Số trang hiển thị 2 bên
?>

<nav aria-label="Page navigation" class="mt-3">
    <ul class="pagination pagination-sm justify-content-center justify-content-md-end mb-0">

        <?php if ($currentPage > 1): ?>
            <li class="page-item">
                <a class="page-link rounded-2 border-0 bg-light text-secondary me-1" 
                   href="<?php echo buildPageLink($targetUrl, $queryParams, $currentPage - 1); ?>">
                   <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <span class="page-link rounded-2 border-0 bg-light text-muted me-1"><i class="bi bi-chevron-left"></i></span>
            </li>
        <?php endif; ?>


        <?php
        $start = max(1, $currentPage - $window);
        $end = min($totalPages, $currentPage + $window);

        // Logic hiển thị dấu "..."
        if ($currentPage - $window < 1) {
            $end = min($totalPages, $start + ($window * 2));
        }
        if ($currentPage + $window > $totalPages) {
            $start = max(1, $end - ($window * 2));
        }

        // Trang 1
        if ($start > 1) {
            echo '<li class="page-item"><a class="page-link rounded-2 border-0 text-secondary" href="' . buildPageLink($targetUrl, $queryParams, 1) . '">1</a></li>';
            if ($start > 2) echo '<li class="page-item disabled"><span class="page-link border-0">...</span></li>';
        }

        // Loop các trang giữa
        for ($i = $start; $i <= $end; $i++): 
            $isActive = ($i == $currentPage);
        ?>
            <li class="page-item <?php echo $isActive ? 'active' : ''; ?>">
                <a class="page-link rounded-2 border-0 mx-1 <?php echo $isActive ? 'bg-primary text-white shadow-sm' : 'text-secondary'; ?>" 
                   href="<?php echo buildPageLink($targetUrl, $queryParams, $i); ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        // Trang cuối
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link border-0">...</span></li>';
            echo '<li class="page-item"><a class="page-link rounded-2 border-0 text-secondary" href="' . buildPageLink($targetUrl, $queryParams, $totalPages) . '">' . $totalPages . '</a></li>';
        }
        ?>


        <?php if ($currentPage < $totalPages): ?>
            <li class="page-item">
                <a class="page-link rounded-2 border-0 bg-light text-primary ms-1" 
                   href="<?php echo buildPageLink($targetUrl, $queryParams, $currentPage + 1); ?>">
                   <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <span class="page-link rounded-2 border-0 bg-light text-muted ms-1"><i class="bi bi-chevron-right"></i></span>
            </li>
        <?php endif; ?>

    </ul>
</nav>
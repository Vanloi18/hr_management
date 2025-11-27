<footer class="footer mt-auto bg-white border-top">
    <div class="container-fluid px-4">
        <div class="footer-content d-none d-md-flex justify-content-between align-items-center py-3">
            <div class="footer-left text-muted small">
                <span class="fw-medium">&copy; <?php echo date('Y'); ?> <strong>HR-System</strong>.</span>
                <span class="ms-1">All rights reserved.</span>
            </div>
            <div class="footer-right small">
                <span class="text-secondary me-1">Phát triển bởi</span>
                <span class="fw-bold text-primary">VanLoi205</span>
            </div>
        </div>
        
        <div class="footer-mobile d-md-none text-center py-2">
            <div class="text-muted small">&copy; 2025 HR-System. Dev by VanLoi205</div>
        </div>
    </div>
</footer>

<style>
    /* CSS Footer đã sửa lỗi */
    .footer {
        width: 100%; /* Chiếm hết chiều rộng của main-content */
        background-color: #ffffff;
        border-top: 1px solid #e5e7eb;
        /* QUAN TRỌNG: Xóa margin-left vì main-content đã lo việc này */
        margin-left: 0 !important; 
        padding: 0;
        z-index: 10;
    }

    /* Responsive: Nếu màn hình nhỏ (Mobile) thì main-content sẽ mất margin-left */
    @media (max-width: 992px) {
        .main-content {
            margin-left: 0 !important; /* Sidebar ẩn đi thì content tràn ra full màn hình */
        }
    }
</style>
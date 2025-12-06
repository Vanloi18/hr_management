<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'HR Management System'); ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/custom.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
    
    <style>
        /* Áp dụng font Inter cho toàn trang */
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<?php
// Kiểm tra nếu là trang Login thì không load Sidebar/Topbar
$isLoginPage = (strpos($path, 'login.view.php') !== false);
?>

<?php if ($isLoginPage): ?>
    
    <?php require $path; ?>

<?php else: ?>

    <div class="wrapper d-flex flex-grow-1">
    
        <?php require BASE_PATH . 'views/partials/sidebar.view.php'; ?>

        <div class="main-content d-flex flex-column flex-grow-1" style="min-width: 0;">
        
            <?php require BASE_PATH . 'views/partials/topbar.view.php'; ?>

            <main class="content flex-grow-1">
                
                <?php if ($message = flash('success')): ?>
                    <div class="container-fluid px-4 mt-3">
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                <div><?php echo e($message); ?></div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                <?php elseif ($message = flash('error')): ?>
                    <div class="container-fluid px-4 mt-3">
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <div><?php echo e($message); ?></div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php require $path; ?> 

            </main> 
            
            <?php require BASE_PATH . 'views/partials/footer.view.php'; ?>
            
        </div> 
    </div> 
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo BASE_URL; ?>/assets/js/custom.js"></script>

</body>
</html>
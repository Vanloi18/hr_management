<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'HR Management'); ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/custom.css">

</head>
<body class="d-flex flex-column min-vh-100">

<?php
$isLoginPage = (strpos($path, 'login.view.php') !== false);
?>

<?php if ($isLoginPage): ?>
    
    <?php require $path; ?>

<?php else: ?>

    <div class="wrapper d-flex flex-grow-1">
    
        <?php require BASE_PATH . 'views/partials/sidebar.view.php'; ?>

        <div class="main-content d-flex flex-column flex-grow-1">
        
            <?php require BASE_PATH . 'views/partials/topbar.view.php'; ?>

            <main class="content flex-grow-1">
                
                <?php if ($message = flash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($message = flash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php require $path; ?> 

            </main> 
            
            <?php require BASE_PATH . 'views/partials/footer.view.php'; ?>
            
        </div> 
    </div> 
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="<?php echo BASE_URL; ?>/assets/js/custom.js"></script>

</body>
</html>
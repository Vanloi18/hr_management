<?php 
$title = "Đăng nhập hệ thống"; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4f46e5; /* Indigo 600 */
            --primary-hover: #4338ca; /* Indigo 700 */
            --bg-color: #f8fafc;
            --text-main: #1e293b;
            --text-secondary: #64748b;
            --input-bg: #ffffff;
            --input-border: #e2e8f0;
            --input-focus: rgba(79, 70, 229, 0.25);
        }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            /* Modern abstract background */
            background: linear-gradient(135deg, #e0e7ff 0%, #f3f4f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Floating shapes for background */
        body::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -5%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, rgba(79, 70, 229, 0) 70%);
            border-radius: 50%;
            z-index: -1;
            animation: float 10s infinite ease-in-out alternate;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: -10%;
            left: -5%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.1) 0%, rgba(56, 189, 248, 0) 70%);
            border-radius: 50%;
            z-index: -1;
            animation: float 15s infinite ease-in-out alternate-reverse;
        }

        .login-card {
            max-width: 420px;
            width: 100%;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.8);
            animation: fadeInUp 0.6s cubic-bezier(0.22, 1, 0.36, 1);
            position: relative;
            backdrop-filter: blur(10px);
        }

        .login-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px auto;
            border-radius: 24px;
            background: linear-gradient(135deg, #eff6ff, #e0e7ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 36px;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.6), 0 10px 20px -5px rgba(79, 70, 229, 0.15);
            transition: transform 0.4s ease;
        }

        .login-card:hover .login-icon {
            transform: scale(1.05) rotate(-5deg);
        }

        .card-title {
            color: var(--text-main);
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-main);
            font-size: 0.925rem;
            margin-bottom: 0.5rem;
        }

        .input-group {
            border: 1px solid var(--input-border);
            border-radius: 12px;
            background: var(--input-bg);
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px var(--input-focus);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            padding-left: 1.25rem;
        }

        .form-control {
            border: none;
            padding: 0.75rem 1rem 0.75rem 0.5rem;
            font-size: 0.95rem;
            color: var(--text-main);
            background: transparent;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .form-check-input {
            border-color: var(--input-border);
            width: 1.1em;
            height: 1.1em;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #4f46e5);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.1);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca, #3730a3);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        .text-primary {
            color: var(--primary-color) !important;
            font-weight: 500;
            transition: color 0.2s;
        }

        .text-primary:hover {
            color: var(--primary-hover) !important;
            text-decoration: underline;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(20px, 20px); }
        }
    </style>
</head>

<body>

    <div class="login-card p-4 p-md-5"> 
        <div class="card-body">

            <div class="login-icon">
                <i class="bi bi-person-fill"></i>
            </div>

            <h2 class="card-title text-center mb-2">Đăng nhập hệ thống</h2>
            <p class="text-center text-muted mb-4">Quản lý tuyển dụng</p>
                <?php if (isset($_SESSION['auth_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                            <div>
                                <strong>Thành công!</strong> <?php echo $_SESSION['auth_message']['text']; ?>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <?php 
                    unset($_SESSION['auth_message']); 
                    ?>
                <?php endif; ?>
            <form method="POST" action="<?php echo BASE_URL; ?>/login" id="form-login">
                <?php csrf_field(); ?>
                
                <?php if ($message = flash('error')): ?>
                    <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3 mb-4" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i> <?php echo e($message); ?>
                    </div>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               placeholder="Nhập email của bạn" 
                               required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="Nhập mật khẩu" 
                               required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted" for="remember">
                            Ghi nhớ
                        </label>
                    </div>
                    <a href="#" class="small text-decoration-none text-primary">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    Đăng nhập
                </button>

            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

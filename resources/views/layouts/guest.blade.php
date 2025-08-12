{{-- Login Layout (layouts/guest.blade.php) - Khusus untuk halaman login/register --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Lotte Mart Career</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --lotte-primary: #ff6b35;
            --lotte-secondary: #ff4757;
            --lotte-accent: #ffa502;
            --lotte-dark: #2c3e50;
            --lotte-light: #ecf0f1;
            --sidebar-red: #dc3545;
            --google-blue: #4285f4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8f9fa;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 80px;
            background: var(--sidebar-red);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            position: relative;
        }

        .sidebar-toggle {
            color: white;
            font-size: 24px;
            margin-bottom: 30px;
            cursor: pointer;
        }

        .sidebar-text {
            writing-mode: vertical-lr;
            text-orientation: mixed;
            color: white;
            font-size: 14px;
            letter-spacing: 2px;
            margin-bottom: auto;
            font-weight: 500;
        }

        .social-links {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }

        .social-links a {
            color: white;
            font-size: 20px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: #ffd700;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            display: flex;
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .left-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .right-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        /* Illustration */
        .illustration {
            width: 100%;
            max-width: 500px;
            height: 350px;
            background: url('https://img.freepik.com/free-vector/job-interview-conversation_74855-7566.jpg') center/cover no-repeat;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .info-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .info-card h3 {
            color: #2c3e50;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .info-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }

        /* Login Form */
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        .login-card h2 {
            color: var(--lotte-dark);
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--lotte-dark);
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--lotte-primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        .password-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            cursor: pointer;
            font-size: 16px;
        }

        /* Captcha */
        .captcha-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .captcha-checkbox {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .captcha-text {
            flex: 1;
            font-size: 14px;
            color: #495057;
        }

        .captcha-logo {
            width: 40px;
            height: 40px;
            background: #4285f4;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }

        /* Buttons */
        .btn-primary {
            width: 100%;
            background: #c62828;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .btn-primary:hover {
            background: #b71c1c;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(198, 40, 40, 0.3);
        }

        .btn-google {
            width: 100%;
            background: var(--google-blue);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            color: white;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .btn-google:hover {
            background: #357ae8;
            transform: translateY(-1px);
        }

        /* Links */
        .auth-links {
            text-align: center;
            margin-top: 20px;
        }

        .auth-links a {
            color: var(--lotte-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .auth-links a:hover {
            color: var(--lotte-secondary);
            text-decoration: underline;
        }

        .auth-links p {
            color: #6c757d;
            margin-bottom: 10px;
            font-size: 14px;
        }

        /* Forgot Password */
        .forgot-password {
            text-align: center;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: var(--lotte-primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
            padding: 12px 15px;
            font-size: 14px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: 60px;
                flex-direction: row;
                padding: 15px 20px;
            }
            
            .sidebar-text {
                writing-mode: horizontal-tb;
                text-orientation: mixed;
                margin-bottom: 0;
                margin-right: auto;
                margin-left: 20px;
            }
            
            .social-links {
                flex-direction: row;
                margin-bottom: 0;
            }
            
            .content-area {
                flex-direction: column;
            }
            
            .left-section, .right-section {
                flex: none;
                padding: 20px;
            }
            
            .illustration {
                max-width: 300px;
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-text">
                BERGABUNG DENGAN LOTTE MART CAREER 
            </div>
            <div class="social-links">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
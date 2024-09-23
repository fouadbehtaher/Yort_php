<?php
session_start();

// التحقق من تسجيل الدخول
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة الطلبات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('welcome-image.webp'); /* استخدم صورة خلفية */
            background-size: cover;
            background-position: center;
            height: 100vh;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h2 {
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="col-md-6">
        <div class="card shadow text-center">
            <h2 class="mb-4">مرحبًا بك في نظام إدارة الطلبات</h2>
            <a href="login.php" class="btn btn-primary w-100 mb-2">تسجيل الدخول</a>
            <a href="register.php" class="btn btn-secondary w-100">إنشاء حساب جديد</a>
        </div>
    </div>
</div>

</body>
</html>

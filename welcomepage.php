<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحبًا بك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('home-image.webp'); /* استخدم صورة home-image.webp كخلفية */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .welcome-message {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 3rem;
            color: #333;
        }

        p {
            font-size: 1.5rem;
            color: #555;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>

<div class="container">
    <div class="welcome-message">
        <h1>مرحبًا بك في نظام إدارة الطلبات</h1>
        <p>ابدأ بتنظيم طلبات الإيجار الآن بسهولة وفعالية.</p>
        <a href="login.php" class="btn btn-primary">تسجيل الدخول</a>
    </div>
</div>

</body>
</html>

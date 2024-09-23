<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('dashboard-image.webp'); /* استخدم صورة خلفية */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9); /* لون خلفية البطاقة شفاف */
            border: none;
            padding: 30px;
            margin-top: 50px;
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

        /* تحسين العرض على الأجهزة المحمولة */
        @media (max-width: 768px) {
            .card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="col-md-8">
        <div class="card shadow">
            <h2 class="text-center mb-4">لوحة التحكم</h2>
            <p class="text-center">مرحبًا، <?php echo $_SESSION['username']; ?>! هذه لوحة التحكم الخاصة بك.</p>
        </div>
    </div>
</div>

</body>
</html>

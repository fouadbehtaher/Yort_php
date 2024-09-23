<?php
session_start();
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
    <title>تغيير كلمة المرور</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('change-password-image.webp'); /* استخدم صورة change-password-image.webp كخلفية */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* طبقة داكنة */
            z-index: -1;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.85); /* لون خلفية البطاقة */
            border: none;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); /* ظل أكبر لجعلها أكثر بروزًا */
            max-width: 400px;
            width: 100%;
        }

        h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            font-size: 1rem;
            font-weight: bold;
            color: #555;
        }

        .form-control {
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px;
            font-size: 1.2rem;
            border-radius: 8px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
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

<div class="overlay"></div>

<div class="card shadow">
    <h2>تغيير كلمة المرور</h2>
    <form action="update_password.php" method="POST">
        <div class="mb-3">
            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">تأكيد كلمة المرور الجديدة</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">تحديث كلمة المرور</button>
    </form>
</div>

</body>
</html>

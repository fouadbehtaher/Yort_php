<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_apartment";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// جلب معلومات المستخدم من قاعدة البيانات
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "حدث خطأ في جلب البيانات.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('profile-image.webp'); /* استخدم صورة خلفية */
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
    <div class="col-md-6">
        <div class="card shadow">
            <h2 class="text-center mb-4">الملف الشخصي</h2>
            <p><strong>الاسم:</strong> <?php echo $user['name']; ?></p>
            <p><strong>البريد الإلكتروني:</strong> <?php echo $user['email']; ?></p>
            <a href="update_profile.php" class="btn btn-primary w-100 mb-2">تحديث البيانات</a>
            <a href="change_password.php" class="btn btn-secondary w-100 mb-2">تغيير كلمة المرور</a>
            <a href="logout.php" class="btn btn-danger w-100 mb-2">تسجيل الخروج</a>
            <!-- زر العودة إلى الصفحة الرئيسية -->
            <a href="home.php" class="btn btn-success w-100">العودة إلى الصفحة الرئيسية</a>
        </div>
    </div>
</div>

</body>
</html>

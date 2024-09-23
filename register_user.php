<?php
session_start();

// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_apartment";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// معالجة تسجيل المستخدم الجديد
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // تشفير كلمة المرور

    // التحقق من أن البريد الإلكتروني غير مسجل مسبقًا
    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        $error_message = "البريد الإلكتروني مسجل بالفعل.";
    } else {
        // إدراج بيانات المستخدم في قاعدة البيانات
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = "تم إنشاء حسابك بنجاح! يمكنك الآن تسجيل الدخول.";
            header('Location: login.php');
            exit();
        } else {
            $error_message = "حدث خطأ أثناء إنشاء الحساب: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('register-image.webp'); /* استخدم صورة register-image.webp كخلفية */
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

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
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
            <h2 class="text-center mb-4">إنشاء حساب جديد</h2>

            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form action="register_user.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">إنشاء حساب</button>
            </form>

            <div class="text-center mt-3">
                <a href="login.php" class="text-decoration-none">تسجيل الدخول</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>

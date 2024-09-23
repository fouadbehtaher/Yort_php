<?php
session_start();

// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_apartment";

// الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// دالة للتحقق من المدخلات
function validate_input($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

// دالة لتسجيل محاولات الدخول الفاشلة
function log_failed_login($email, $conn) {
    $sql = "INSERT INTO login_attempts (email, attempt_time) VALUES (?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->close();
}

// دالة للتحقق من محاولات الدخول المتعددة
function check_brute_force($email, $conn) {
    $time_limit = 15 * 60; // 15 دقيقة
    $max_attempts = 5;

    $sql = "SELECT COUNT(*) AS attempt_count FROM login_attempts WHERE email = ? AND attempt_time > NOW() - INTERVAL ? SECOND";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $email, $time_limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $attempt_data = $result->fetch_assoc();
    $stmt->close();

    return $attempt_data['attempt_count'] >= $max_attempts;
}

// التحقق من تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = validate_input($_POST['email']);  // إزالة المسافات من بداية ونهاية البريد الإلكتروني
    $password = $_POST['password'];

    // التحقق من صحة الإدخال
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {

        // التحقق من محاولات الدخول الفاشلة
        if (check_brute_force($email, $conn)) {
            $error_message = "تم حظر الحساب مؤقتاً بسبب محاولات متعددة لتسجيل الدخول. يرجى المحاولة لاحقاً.";
        } else {
            // استعلام باستخدام prepared statement لحماية ضد SQL Injection
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            // التحقق من وجود المستخدم بالبريد الإلكتروني المدخل
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                // التحقق من كلمة المرور
                if (password_verify($password, $user['password'])) {
                    // تخزين معلومات المستخدم في الجلسة
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];  // إضافة دور المستخدم

                    // إعادة التوجيه بناءً على دور المستخدم
                    if ($user['role'] === 'admin' || $user['role'] === 'supervisor') {
                        header('Location: admin_dashboard.php');  // توجيه المشرف أو المدير إلى لوحة الإدارة
                    } else {
                        header('Location: home.php');  // توجيه المستخدم العادي إلى الصفحة الرئيسية
                    }
                    exit();
                } else {
                    // تسجيل محاولة فاشلة
                    log_failed_login($email, $conn);
                    $error_message = "كلمة المرور غير صحيحة.";
                }
            } else {
                $error_message = "البريد الإلكتروني غير مسجل.";
            }
            $stmt->close();
        }
    } else {
        $error_message = "يرجى إدخال بريد إلكتروني صحيح وكلمة مرور صالحة.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('login-image.webp'); /* استخدم صورة login-image.webp كخلفية */
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
            <h2 class="text-center mb-4">تسجيل الدخول</h2>

            <!-- عرض رسالة الخطأ إن وجدت -->
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form action="authenticate.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>
            </form>

            <div class="text-center mt-3">
                <a href="register.php" class="text-decoration-none">إنشاء حساب جديد</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>

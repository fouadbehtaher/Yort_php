<?php
session_start();

// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_apartment";

// دالة مخصصة للتعامل مع أخطاء الاتصال بقاعدة البيانات
function handle_db_error($conn) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// الاتصال بقاعدة البيانات مع التحقق
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    handle_db_error($conn);
}

// التحقق من صحة المدخلات
function validate_input($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

// دالة لتسجيل محاولة فاشلة لتسجيل الدخول
function log_failed_login($email) {
    // تسجيل محاولة الدخول الفاشلة في ملف (أو قاعدة بيانات)
    $log_file = 'failed_logins.txt';
    $current_time = date('Y-m-d H:i:s');
    file_put_contents($log_file, "محاولة فاشلة لتسجيل الدخول: $email في $current_time\n", FILE_APPEND);
}

// دالة للتحقق من محاولات الدخول المتعددة (Brute Force)
function check_brute_force($email, $conn) {
    $time_limit = 15 * 60; // 15 دقيقة (900 ثانية)
    $max_attempts = 5;

    // استعلام للتحقق من عدد المحاولات
    $sql = "SELECT COUNT(*) AS attempt_count 
            FROM login_attempts 
            WHERE email = ? 
            AND attempt_time > (NOW() - INTERVAL ? SECOND)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('si', $email, $time_limit); 
        $stmt->execute();
        $result = $stmt->get_result();
        $attempt_data = $result->fetch_assoc();
        $stmt->close();

        return $attempt_data['attempt_count'] >= $max_attempts;
    } else {
        // اطبع الخطأ في الاستعلام
        die("فشل إعداد الاستعلام: " . $conn->error);
    }
}

// دالة لتسجيل محاولة الدخول في قاعدة البيانات
function record_login_attempt($email, $conn) {
    $sql = "INSERT INTO login_attempts (email, attempt_time) VALUES (?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->close();
}

// دالة لتحويل "admin" أو "supervisor" إلى بريد إلكتروني كامل
function normalize_email($email) {
    if ($email === 'admin') {
        return 'admin@example.com';
    } elseif ($email === 'supervisor') {
        return 'supervisor@example.com';
    }
    return $email;
}

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // التحقق من صحة البريد الإلكتروني وكلمة المرور
    $email = validate_input($_POST['email']);
    $password = validate_input($_POST['password']);

    // تحويل "admin" و"supervisor" إلى بريد إلكتروني
    $email = normalize_email($email);

    // التحقق من صحة البريد الإلكتروني وكلمة المرور إذا كانت فارغة
    if (empty($email)) {
        $error_message = "يرجى إدخال البريد الإلكتروني.";
    } elseif (empty($password)) {
        $error_message = "يرجى إدخال كلمة المرور.";
    } else {
        // تحقق من محاولات الدخول الفاشلة
        if (check_brute_force($email, $conn)) {
            $error_message = "تم حظر الحساب مؤقتاً بسبب محاولات متعددة لتسجيل الدخول.";
        } else {
            // استعلام للتحقق من وجود المستخدم بالبريد الإلكتروني
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $email); // حماية الاستعلام من SQL Injection
            $stmt->execute();
            $result = $stmt->get_result();

            // التحقق من وجود المستخدم
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // التحقق من كلمة المرور باستخدام hash
                if (password_verify($password, $user['password'])) {
                    // تخزين معلومات المستخدم في الجلسة
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role']; // إضافة دور المستخدم في الجلسة

                    // إعادة التوجيه إلى الصفحة الرئيسية بعد تسجيل الدخول بنجاح
                    header('Location: home.php');
                    exit();
                } else {
                    // تسجيل محاولة فاشلة
                    record_login_attempt($email, $conn);
                    log_failed_login($email);
                    $error_message = "كلمة المرور غير صحيحة.";
                }
            } else {
                // تسجيل محاولة فاشلة
                record_login_attempt($email, $conn);
                log_failed_login($email);
                $error_message = "البريد الإلكتروني غير مسجل.";
            }

            // إغلاق الاستعلام
            $stmt->close();
        }
    }
}

// إغلاق الاتصال بقاعدة البيانات
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

            <form action="login.php" method="POST">
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

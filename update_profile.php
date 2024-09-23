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

// التحقق من اتصال قاعدة البيانات
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// جلب معلومات المستخدم
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "حدث خطأ في جلب البيانات.";
    exit();
}

// معالجة تحديث بيانات الملف الشخصي
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    // التحقق من صحة البيانات
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($name)) {
        // استعلام لتحديث معلومات المستخدم
        $sql_update = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssi", $name, $email, $user_id);

        if ($stmt_update->execute()) {
            $_SESSION['success_message'] = "تم تحديث الملف الشخصي بنجاح!";
            header('Location: profile.php');
            exit();
        } else {
            echo "خطأ في التحديث: " . $conn->error;
        }
    } else {
        echo "يرجى إدخال اسم صحيح وبريد إلكتروني صالح.";
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث الملف الشخصي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('update_profile-image.webp'); /* استخدم صورة خلفية */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
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
            margin-bottom: 10px;
        }

        .success-message {
            color: green;
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
            <h2 class="text-center mb-4">تحديث الملف الشخصي</h2>
            
            <!-- عرض رسالة نجاح أو خطأ -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <p class="success-message text-center"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
            <?php elseif (isset($error_message)): ?>
                <p class="error-message text-center"><?php echo $error_message; ?></p>
            <?php endif; ?>
            
            <!-- نموذج تحديث الملف الشخصي -->
            <form action="update_profile.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">تحديث الملف الشخصي</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

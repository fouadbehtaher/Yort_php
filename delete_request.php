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

// التحقق من وجود معرف الطلب في رابط الحذف
if (isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // التأكد من أن معرف الطلب رقم صحيح
    if (filter_var($request_id, FILTER_VALIDATE_INT)) {
        // تنفيذ استعلام حذف الطلب
        $sql = "DELETE FROM requests WHERE id = $request_id";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = "تم حذف الطلب بنجاح.";
        } else {
            $_SESSION['error_message'] = "فشل في حذف الطلب: " . $conn->error;
        }
    } else {
        $_SESSION['error_message'] = "معرف الطلب غير صالح.";
    }

    // إعادة التوجيه إلى صفحة الطلبات بعد الحذف
    header('Location: requests.php');
    exit();
} else {
    $_SESSION['error_message'] = "لم يتم تحديد معرف الطلب للحذف.";
    header('Location: requests.php');
    exit();
}

$conn->close();

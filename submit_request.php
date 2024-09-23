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

// معالجة إضافة الطلب
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $description = $_POST['description'];
    $title = 'طلب جديد'; // يمكن تعديل النص حسب الحاجة

    if (isset($_POST['smoker']) && isset($_POST['price'])) {
        $smoker = $_POST['smoker'];
        $price = $_POST['price'];
        $sql = "INSERT INTO requests (id_number, title, description, smoker, price, created_at) 
                VALUES ('$id', '$title', '$description', '$smoker', '$price', NOW())";
    } elseif (isset($_POST['apartment_info']) && isset($_POST['has_ac']) && isset($_POST['num_acs'])) {
        $apartment_info = $_POST['apartment_info'];
        $has_ac = $_POST['has_ac'];
        $num_acs = $_POST['num_acs'];
        $sql = "INSERT INTO requests (id_number, title, description, apartment_info, has_ac, num_acs, created_at) 
                VALUES ('$id', '$title', '$description', '$apartment_info', '$has_ac', '$num_acs', NOW())";
    } else {
        echo "الرجاء التأكد من إدخال جميع البيانات المطلوبة.";
        exit();
    }

    // تنفيذ الاستعلام
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "تم إضافة طلبك بنجاح!";
        header('Location: requests.php'); // إعادة التوجيه إلى صفحة عرض الطلبات
        exit();
    } else {
        echo "خطأ في التنفيذ: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

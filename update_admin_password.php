<?php
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

// تعيين كلمة مرور جديدة وتحديثها في قاعدة البيانات
$new_password = password_hash('0185540520', PASSWORD_DEFAULT);
$email = 'admin@example.com'; // أو 'supervisor@example.com'

$sql = "UPDATE users SET password = '$new_password' WHERE email = '$email'";

if ($conn->query($sql) === TRUE) {
    echo "تم تحديث كلمة المرور بنجاح!";
} else {
    echo "خطأ: " . $conn->error;
}

$conn->close();

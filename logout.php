<?php
session_start();

// إلغاء جميع بيانات الجلسة وتسجيل الخروج
session_unset();
session_destroy();

// إعادة التوجيه إلى صفحة تسجيل الدخول
header('Location: login.php');
exit();
?>

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

// التحقق من وجود معرف الطلب في رابط العرض
if (!isset($_GET['id'])) {
    die("خطأ: لم يتم تحديد معرف الطلب.");
}

$request_id = $_GET['id'];

// جلب بيانات الطلب من قاعدة البيانات
$sql = "SELECT * FROM requests WHERE id = '$request_id'";
$result = $conn->query($sql);

// التحقق من نجاح الاستعلام
if (!$result) {
    die("خطأ في استعلام SQL: " . $conn->error);
}

// التحقق من وجود بيانات
if ($result->num_rows === 0) {
    die("خطأ: لم يتم العثور على الطلب.");
}

$request = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض تفاصيل الطلب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('view_request-image.webp');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            font-family: 'Cairo', sans-serif;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.95); 
            border: none;
            padding: 30px;
            margin-top: 50px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
            padding: 20px;
        }

        h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }

        .detail-row {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
            font-size: 16px;
            width: 40%;
        }

        .detail-value {
            font-size: 16px;
            width: 60%;
            text-align: right;
            color: #333;
        }

        .img-thumbnail {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-top: 10px;
        }

        .btn-back {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        .section-title {
            font-size: 20px;
            margin-top: 20px;
            color: #007bff;
            font-weight: bold;
            border-bottom: 2px solid #007bff;
            display: inline-block;
        }

        /* تحسين العرض على الأجهزة المحمولة */
        @media (max-width: 768px) {
            .card {
                padding: 20px;
                margin: 20px;
                width: 100%;
            }

            h2 {
                font-size: 24px;
            }

            .detail-label, .detail-value {
                font-size: 14px;
            }

            .btn-back {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <h2 class="mb-4">تفاصيل الطلب</h2>

        <div class="detail-row">
            <span class="detail-label">نوع المستخدم:</span>
            <span class="detail-value"><?php echo $request['user_type'] === 'student' ? 'طالب' : 'مالك'; ?></span>
        </div>

        <div class="detail-row">
            <span class="detail-label">رقم الهوية:</span>
            <span class="detail-value"><?php echo $request['id_number']; ?></span>
        </div>

        <div class="detail-row">
            <span class="detail-label">الوصف:</span>
            <span class="detail-value"><?php echo $request['description']; ?></span>
        </div>

        <div class="detail-row">
            <span class="detail-label">السعر المطلوب للإيجار:</span>
            <span class="detail-value"><?php echo number_format($request['price'], 2); ?> جنية مصري</span>
        </div>

        <?php if ($request['user_type'] === 'student'): ?>
            <div class="section-title">معلومات الطالب</div>
            <div class="detail-row">
                <span class="detail-label">اسم الجامعة:</span>
                <span class="detail-value"><?php echo $request['university_name']; ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">رقم الهوية الجامعية:</span>
                <span class="detail-value"><?php echo $request['university_id']; ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">صورة الطالب:</span><br>
                <?php if (!empty($request['student_image'])): ?>
                    <img src="uploads/<?php echo $request['student_image']; ?>" class="img-thumbnail" alt="صورة الطالب">
                <?php else: ?>
                    <span class="detail-value">لا توجد صورة.</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($request['user_type'] === 'owner'): ?>
            <div class="section-title">معلومات المالك</div>
            <div class="detail-row">
                <span class="detail-label">صورة الشقة:</span><br>
                <?php if (!empty($request['apartment_image'])): ?>
                    <img src="uploads/<?php echo $request['apartment_image']; ?>" class="img-thumbnail" alt="صورة الشقة">
                <?php else: ?>
                    <span class="detail-value">لا توجد صورة.</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="detail-row">
            <span class="detail-label">حالة الطلب:</span>
            <span class="detail-value"><?php echo isset($request['status']) ? ($request['status'] === 'pending' ? 'قيد المعالجة' : 'مكتمل') : 'غير محدد'; ?></span>
        </div>

        <div class="detail-row">
            <span class="detail-label">تاريخ الإنشاء:</span>
            <span class="detail-value"><?php echo date("d-m-Y", strtotime($request['created_at'])); ?></span>
        </div>

        <a href="requests.php" class="btn btn-back">العودة إلى القائمة</a>
    </div>
</div>

</body>
</html>

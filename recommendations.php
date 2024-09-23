<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    die("خطأ: لم يتم العثور على معرف المستخدم في الجلسة. الرجاء تسجيل الدخول.");
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

// الحصول على معرف المستخدم من الجلسة
$user_id = $_SESSION['user_id'];
if (!$user_id) {
    die("خطأ: معرف المستخدم في الجلسة غير موجود.");
}

// جلب نوع المستخدم الحالي من جدول users
$user_type_query = "SELECT user_type FROM users WHERE id = '$user_id'";
$user_type_result = $conn->query($user_type_query);

// التحقق من وجود نتائج للاستعلام
if ($user_type_result === false) {
    die("خطأ في استعلام SQL: " . $conn->error);
} elseif ($user_type_result->num_rows === 0) {
    die("خطأ: لم يتم العثور على المستخدم بمعرف $user_id في جدول users.");
}

$current_user_type = $user_type_result->fetch_assoc()['user_type'];

// منطق الذكاء الاصطناعي - جلب التوصيات بناءً على نوع المستخدم
$recommendations = [];
$recommend_query = '';

if ($current_user_type === 'student') {
    // الطالب يحتاج توصيات من أصحاب العقارات
    $recommend_query = "SELECT * FROM requests WHERE user_type = 'owner' ORDER BY RAND() LIMIT 5"; // عينة عشوائية من 5 عقارات
} else {
    // المالك يحتاج توصيات من الطلاب
    $recommend_query = "SELECT * FROM requests WHERE user_type = 'student' ORDER BY RAND() LIMIT 5"; // عينة عشوائية من 5 طلاب
}

$recommend_result = $conn->query($recommend_query);

// التحقق من نجاح استعلام التوصيات
if ($recommend_result === false) {
    die("خطأ في استعلام التوصيات SQL: " . $conn->error);
}

// التحقق من وجود نتائج
if ($recommend_result->num_rows > 0) {
    while ($row = $recommend_result->fetch_assoc()) {
        $recommendations[] = $row;
    }
} else {
    $recommendations = [];
}

// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التوصيات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('recommendations-image.webp'); /* إضافة الخلفية */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9); /* لون خلفية البطاقة شفاف */
            border: none;
            padding: 30px;
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow-y: auto;
        }

        h2 {
            color: #333;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .price {
            text-align: right;
        }

        .alert-warning {
            background-color: #ffc107;
            color: #333;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }

        /* تحسين العرض على الأجهزة المحمولة */
        @media (max-width: 768px) {
            .card {
                padding: 20px;
                margin: 20px;
            }

            h2 {
                font-size: 20px;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2 class="text-center my-4">التوصيات المعتمدة على الذكاء الاصطناعي</h2>

            <?php if (count($recommendations) > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>نوع المستخدم</th>
                            <th>رقم الهوية</th>
                            <th>الوصف</th>
                            <th>السعر</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recommendations as $recommendation): ?>
                            <tr>
                                <td>
                                    <!-- عكس نوع المستخدم لتوصية صحيحة -->
                                    <?php echo $recommendation['user_type'] === 'student' ? 'طالب' : 'مالك'; ?>
                                </td>
                                <td><?php echo $recommendation['id_number']; ?></td>
                                <td><?php echo $recommendation['description']; ?></td>
                                <td class="price"><?php echo number_format($recommendation['price'], 2); ?> جنية مصري</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning text-center">لا توجد توصيات حالياً.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

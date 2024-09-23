<?php
session_start();

// التحقق من صلاحيات المدير أو المشرف
if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'supervisor')) {
    header('Location: login.php');
    exit();
}

// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_apartment";

// دالة لمعالجة أخطاء الاتصال
function handle_db_error($conn) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// الاتصال بقاعدة البيانات مع التحقق من الأخطاء
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    handle_db_error($conn);
}

// جلب بيانات الطلبات والمستخدمين
$requests_sql = "SELECT * FROM requests ORDER BY created_at DESC"; // إضافة الترتيب حسب تاريخ الإنشاء
$users_sql = "SELECT * FROM users";

// إعداد استعلام لجلب البيانات مع التحقق
function fetch_data($sql, $conn) {
    $result = $conn->query($sql);
    if (!$result) {
        die("خطأ في استعلام SQL: " . $conn->error);
    }
    return $result;
}

// جلب بيانات الطلبات والمستخدمين باستخدام الدوال
$requests_result = fetch_data($requests_sql, $conn);
$users_result = fetch_data($users_sql, $conn);

// غلق الاتصال بقاعدة البيانات
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة إدارة الموقع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .sidebar {
            background-color: #2c3e50;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
            width: 200px;
            top: 0;
            left: 0;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 15px;
            text-decoration: none;
            margin-bottom: 10px;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .navbar {
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin-left: 200px; /* Adjust for sidebar */
        }

        .navbar .user-info {
            color: white;
        }

        .content {
            margin-left: 200px;
            padding: 20px;
        }

        .card {
            margin-top: 20px;
        }

        .btn {
            margin-top: 10px;
        }

        .filter-form select, .filter-form input {
            margin-bottom: 10px;
        }

        /* تحسين العرض على الأجهزة المحمولة */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .navbar {
                margin-left: 0;
            }

            .content {
                margin-left: 0;
            }
        }

        /* إضافة الرسالة المنبثقة */
        .alert {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 20px;
            background-color: #d9534f;
            color: white;
            border-radius: 5px;
        }

        .alert.show {
            display: block;
        }
    </style>
</head>
<body>

<!-- شريط جانبي -->
<div class="sidebar">
    <h2 class="text-center text-white">لوحة الإدارة</h2>
    <a href="#dashboard"><i class="fas fa-home"></i> لوحة التحكم</a>
    <a href="#manage-requests"><i class="fas fa-file-alt"></i> إدارة الطلبات</a>
    <a href="#manage-users"><i class="fas fa-users"></i> إدارة المستخدمين</a>
    <a href="#settings"><i class="fas fa-cogs"></i> الإعدادات</a>
</div>

<!-- شريط علوي -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">إدارة الموقع</a>
        <div class="ms-auto user-info">
            <?php echo $_SESSION['user_name']; ?> (<?php echo $_SESSION['user_role']; ?>)
            <a href="logout.php" class="btn btn-danger ms-3">تسجيل الخروج</a>
        </div>
    </div>
</nav>

<!-- رسالة منبثقة -->
<div class="alert">تم الحذف بنجاح</div>

<!-- المحتوى الرئيسي -->
<div class="content">
    <h2 id="dashboard">لوحة التحكم</h2>
    <p>مرحباً بك في لوحة إدارة الموقع. هنا يمكنك إدارة المستخدمين والطلبات بالإضافة إلى التحكم في إعدادات الموقع.</p>

    <!-- إدارة الطلبات -->
    <div class="card">
        <div class="card-header">
            <h3>إدارة الطلبات</h3>
        </div>
        <div class="card-body">
            <form class="filter-form" method="GET" action="admin_dashboard.php">
                <div class="row">
                    <div class="col-md-3">
                        <select name="user_type" class="form-control">
                            <option value="">نوع المستخدم</option>
                            <option value="student">طالب</option>
                            <option value="owner">مالك</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="price_min" class="form-control" placeholder="الحد الأدنى للسعر">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="price_max" class="form-control" placeholder="الحد الأقصى للسعر">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_from" class="form-control">
                    </div>
                    <div class="col-md-3 mt-2">
                        <input type="date" name="date_to" class="form-control">
                    </div>
                    <div class="col-md-3 mt-2">
                        <button type="submit" class="btn btn-primary w-100">تصفية</button>
                    </div>
                </div>
            </form>

            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>نوع المستخدم</th>
                        <th>السعر</th>
                        <th>تاريخ الطلب</th>
                        <th>حالة الطلب</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($request = $requests_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $request['id']; ?></td>
                            <td><?php echo $request['user_type'] === 'student' ? 'طالب' : 'مالك'; ?></td>
                            <td><?php echo number_format($request['price'], 2); ?> جنية مصري</td>
                            <td><?php echo date('d-m-Y', strtotime($request['created_at'])); ?></td>
                            <td><?php echo $request['status']; ?></td>
                            <td>
                                <a href="view_request.php?id=<?php echo $request['id']; ?>" class="btn btn-primary btn-sm">عرض</a>
                                <a href="edit_request.php?id=<?php echo $request['id']; ?>" class="btn btn-warning btn-sm">تعديل</a>
                                <a href="delete_request.php?id=<?php echo $request['id']; ?>" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $request['id']; ?>">حذف</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- إدارة المستخدمين -->
    <div class="card" id="manage-users">
        <div class="card-header">
            <h3>إدارة المستخدمين</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>رقم المستخدم</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الدور</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['role']; ?></td>
                            <td>
                                <a href="view_user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">عرض</a>
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">تعديل</a>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- إضافة مكتبة Font Awesome للأيقونات -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
// إضافة وظيفة حذف الطلب
document.querySelectorAll('.delete-btn').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const requestId = this.getAttribute('data-id');
        if (confirm('هل أنت متأكد من الحذف؟')) {
            // طلب الحذف عبر Ajax (يمكن استكمال هذه الوظيفة لاحقًا)
            document.querySelector('.alert').classList.add('show');
            setTimeout(() => document.querySelector('.alert').classList.remove('show'), 3000);
        }
    });
});
</script>

</body>
</html>

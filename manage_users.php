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

// التحقق من صلاحيات admin
if ($_SESSION['email'] !== 'admin@example.com') {
    header('Location: home.php');
    exit();
}

// رسائل النجاح أو الخطأ
$success_message = '';
$error_message = '';

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// جلب بيانات المستخدمين مع إمكانية تصفية البيانات
$filter_email = isset($_GET['email']) ? $_GET['email'] : '';
$filter_role = isset($_GET['role']) ? $_GET['role'] : '';

$sql = "SELECT * FROM users WHERE 1=1";
if ($filter_email) {
    $sql .= " AND email LIKE '%$filter_email%'";
}
if ($filter_role) {
    $sql .= " AND role = '$filter_role'";
}

$result = $conn->query($sql);

// حذف مستخدم
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_sql = "DELETE FROM users WHERE id = $delete_id";
    
    if ($conn->query($delete_sql) === TRUE) {
        $_SESSION['success_message'] = "تم حذف المستخدم بنجاح.";
    } else {
        $_SESSION['error_message'] = "فشل في حذف المستخدم.";
    }
    header('Location: manage_users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            transition: background-color 0.3s;
        }

        .dark-mode {
            background-color: #343a40;
            color: #fff;
        }

        .container {
            margin-top: 50px;
            max-width: 1200px;
        }

        .table-container {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
        }

        .dark-mode .table-container {
            background-color: #495057;
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            text-align: center;
            padding: 12px;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .dark-mode th {
            background-color: #495057;
        }

        .btn-primary, .btn-danger, .btn-warning {
            margin-right: 5px;
            border-radius: 5px;
            padding: 8px 15px;
        }

        .btn-dark-mode {
            background-color: #333;
            color: white;
            border-radius: 5px;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
        }

        .btn-dark-mode:hover {
            background-color: #555;
        }

        .pagination a {
            color: #007bff;
            padding: 10px 20px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 5px;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
        }

        .pagination a:hover {
            background-color: #0056b3;
            color: white;
        }

        .alert {
            margin-bottom: 20px;
        }

        /* تحسين العرض على الأجهزة المحمولة */
        @media (max-width: 768px) {
            .table-container {
                padding: 15px;
            }

            .btn-primary, .btn-danger, .btn-warning {
                padding: 5px 10px;
                margin-bottom: 5px;
            }
        }

        .btn-dark-mode {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 999;
        }
    </style>
</head>
<body>

<!-- زر تفعيل الوضع الليلي -->
<button class="btn-dark-mode" onclick="toggleDarkMode()">تفعيل الوضع الليلي</button>

<div class="container">
    <div class="table-container shadow">
        <h2 class="text-center mb-4">إدارة المستخدمين</h2>

        <!-- رسائل التنبيه -->
        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- تصفية المستخدمين -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-5">
                    <input type="text" name="email" class="form-control" placeholder="ابحث بالبريد الإلكتروني" value="<?php echo $filter_email; ?>">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-control">
                        <option value="">كل الأدوار</option>
                        <option value="admin" <?php if ($filter_role === 'admin') echo 'selected'; ?>>مدير</option>
                        <option value="user" <?php if ($filter_role === 'user') echo 'selected'; ?>>مستخدم</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">بحث</button>
                </div>
            </div>
        </form>

        <!-- جدول عرض المستخدمين -->
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الدور</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['role'] === 'admin' ? 'مدير' : 'مستخدم'; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">تعديل</a>
                                <a href="manage_users.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">حذف</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">لا يوجد مستخدمون لعرضهم.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>

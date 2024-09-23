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

// التحقق من نوع المستخدم (admin أو user)
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// عرض رسالة نجاح عند حذف الطلب
$success_message = '';
if (isset($_SESSION['delete_success'])) {
    $success_message = $_SESSION['delete_success'];
    unset($_SESSION['delete_success']); // إزالة الرسالة بعد عرضها
}

// إعدادات Pagination
$results_per_page = 10; // عدد الطلبات التي ستعرض في كل صفحة
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // الصفحة الحالية
$start_from = ($page - 1) * $results_per_page;

// جلب الطلبات من قاعدة البيانات مع Pagination
$filter_user_type = isset($_GET['user_type']) ? $_GET['user_type'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : ''; // استخدام isset لمنع التحذير
$filter_price_min = isset($_GET['price_min']) ? (int)$_GET['price_min'] : 0; // الحد الأدنى للسعر
$filter_price_max = isset($_GET['price_max']) ? (int)$_GET['price_max'] : 99999; // الحد الأقصى للسعر
$filter_date_from = isset($_GET['date_from']) ? $_GET['date_from'] : ''; // تاريخ البداية
$filter_date_to = isset($_GET['date_to']) ? $_GET['date_to'] : ''; // تاريخ النهاية

$sql = "SELECT * FROM requests WHERE 1=1";
if ($filter_user_type) {
    $sql .= " AND user_type = '$filter_user_type'";
}
if ($filter_status) {
    $sql .= " AND status = '$filter_status'";
}
if ($filter_price_min || $filter_price_max) {
    $sql .= " AND price BETWEEN $filter_price_min AND $filter_price_max";
}
if ($filter_date_from && $filter_date_to) {
    $sql .= " AND created_at BETWEEN '$filter_date_from' AND '$filter_date_to'";
}
$sql .= " ORDER BY created_at DESC LIMIT $start_from, $results_per_page";

$result = $conn->query($sql);

// التحقق من نجاح الاستعلام
if (!$result) {
    die("خطأ في استعلام SQL: " . $conn->error);
}

// حساب عدد الصفحات المطلوبة
$total_requests_sql = "SELECT COUNT(id) AS total_requests FROM requests WHERE 1=1";
if ($filter_user_type) {
    $total_requests_sql .= " AND user_type = '$filter_user_type'";
}
if ($filter_status) {
    $total_requests_sql .= " AND status = '$filter_status'";
}
if ($filter_price_min || $filter_price_max) {
    $total_requests_sql .= " AND price BETWEEN $filter_price_min AND $filter_price_max";
}
if ($filter_date_from && $filter_date_to) {
    $total_requests_sql .= " AND created_at BETWEEN '$filter_date_from' AND '$filter_date_to'";
}

$total_result = $conn->query($total_requests_sql);

// التحقق من نجاح استعلام الحساب
if (!$total_result) {
    die("خطأ في استعلام SQL: " . $conn->error);
}

$total_row = $total_result->fetch_assoc();
$total_requests = $total_row['total_requests'];
$total_pages = ceil($total_requests / $results_per_page);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الطلبات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('requests-image.webp');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            overflow-x: hidden;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            width: 100%;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
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

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
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
            border: 1px solid #007bff;
        }

        .pagination a:hover {
            background-color: #0056b3;
            color: white;
        }

        .btn-edit {
            background-color: #ffc107;
            color: white;
            border: none;
            padding: 5px 10px;
            margin-right: 5px;
            border-radius: 5px;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .btn-more {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .btn-edit:hover, .btn-delete:hover, .btn-more:hover {
            opacity: 0.8;
        }

        .btn-home {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            display: block;
            margin: 20px auto;
            width: 200px;
            text-align: center;
            text-decoration: none;
        }

        .btn-home:hover {
            background-color: #218838;
        }

        /* نظام الوضع الليلي */
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        .card.dark-mode {
            background-color: rgba(48, 48, 48, 0.9);
            color: #e0e0e0;
        }

        .table-striped.dark-mode th,
        .table-striped.dark-mode td {
            border-color: #444;
        }

        .table-striped.dark-mode tr:hover {
            background-color: #333;
        }

        .pagination a.dark-mode {
            background-color: #444;
            color: #e0e0e0;
        }

        .pagination a.active.dark-mode {
            background-color: #007bff;
        }

        .btn-edit.dark-mode, .btn-delete.dark-mode, .btn-more.dark-mode, .btn-home.dark-mode {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<?php if ($success_message): ?>
    <div class="alert alert-success text-center">
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<div class="container">
    <div class="card shadow">
        <h2 class="text-center mb-4">إدارة الطلبات</h2>

        <?php if ($user_email === 'admin@example.com'): ?>
        <!-- إضافة خيارات لتصفية الطلبات -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <select name="user_type" class="form-control">
                        <option value="">كل الأنواع</option>
                        <option value="student" <?php if ($filter_user_type === 'student') echo 'selected'; ?>>طالب</option>
                        <option value="owner" <?php if ($filter_user_type === 'owner') echo 'selected'; ?>>مالك</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">كل الحالات</option>
                        <option value="pending" <?php if ($filter_status === 'pending') echo 'selected'; ?>>قيد المعالجة</option>
                        <option value="completed" <?php if ($filter_status === 'completed') echo 'selected'; ?>>مكتمل</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="price_min" class="form-control" placeholder="الحد الأدنى للسعر" value="<?php echo isset($_GET['price_min']) ? $_GET['price_min'] : ''; ?>">
                </div>
                <div class="col-md-3">
                    <input type="number" name="price_max" class="form-control" placeholder="الحد الأقصى للسعر" value="<?php echo isset($_GET['price_max']) ? $_GET['price_max'] : ''; ?>">
                </div>
                <div class="col-md-3 mt-2">
                    <input type="date" name="date_from" class="form-control" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : ''; ?>">
                </div>
                <div class="col-md-3 mt-2">
                    <input type="date" name="date_to" class="form-control" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : ''; ?>">
                </div>
                <div class="col-md-3 mt-2">
                    <button type="submit" class="btn btn-primary w-100">تصفية</button>
                </div>
            </div>
        </form>

        <!-- إضافة أزرار إضافية للمشرف -->
        <div class="d-flex justify-content-between mb-4">
            <a href="manage_users.php" class="btn btn-primary">إدارة المستخدمين</a>
            <button class="btn btn-dark" id="toggleDarkMode">تبديل الوضع الليلي</button>
        </div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>نوع المستخدم</th>
                    <th>رقم الهوية</th>
                    <th>الوصف</th>
                    <th>السعر المطلوب للإيجار</th>
                    <th>حالة الطلب</th>
                    <th>تاريخ الإنشاء</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['user_type'] === 'student' ? 'طالب' : 'مالك'; ?></td>
                            <td><?php echo $row['id_number']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td class="price"><?php echo number_format($row['price'], 2); ?> جنية مصري</td>
                            <td><?php echo isset($row['status']) ? ($row['status'] === 'pending' ? 'قيد المعالجة' : 'مكتمل') : 'غير محدد'; ?></td>
                            <td><?php echo date("d-m-Y", strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="view_request.php?id=<?php echo $row['id']; ?>" class="btn btn-more">عرض المزيد</a>
                                <a href="edit_request.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">تعديل</a>
                                <a href="delete_request.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">حذف</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">لا توجد طلبات حالياً.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="requests.php?page=<?php echo $page - 1; ?>">&laquo; الصفحة السابقة</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="requests.php?page=<?php echo $i; ?>" class="<?php if ($page == $i) echo 'active'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="requests.php?page=<?php echo $page + 1; ?>">الصفحة التالية &raquo;</a>
            <?php endif; ?>
        </div>

        <a href="home.php" class="btn-home">العودة إلى الصفحة الرئيسية</a>
    </div>
</div>

<script>
// تفعيل وضع النظام الليلي
document.getElementById('toggleDarkMode').addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
    document.querySelector('.card').classList.toggle('dark-mode');
    document.querySelectorAll('a').forEach(function(link) {
        link.classList.toggle('dark-mode');
    });
});
</script>

</body>
</html>

<?php $conn->close(); ?>

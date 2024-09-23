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

// التحقق من وجود معرف الطلب في رابط التعديل
if (isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // جلب بيانات الطلب من قاعدة البيانات لعرضها في نموذج التعديل
    $sql = "SELECT * FROM requests WHERE id = $request_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $request = $result->fetch_assoc();
    } else {
        $_SESSION['error_message'] = "لم يتم العثور على الطلب.";
        header('Location: requests.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = "لم يتم تحديد معرف الطلب للتعديل.";
    header('Location: requests.php');
    exit();
}

// معالجة تعديل الطلب
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $description = $_POST['description'];
    $smoker = $_POST['smoker'];
    $price = $_POST['price'];

    // تحديث بيانات الطلب في قاعدة البيانات
    $sql = "UPDATE requests SET id_number = '$id', description = '$description', smoker = '$smoker', price = '$price' WHERE id = $request_id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "تم تحديث الطلب بنجاح!";
        header('Location: requests.php');
        exit();
    } else {
        echo "خطأ في التحديث: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الطلب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('edit_request-image.webp'); /* استخدم صورة خلفية */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
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
            <h2 class="text-center mb-4">تعديل الطلب</h2>
            <form action="edit_request.php?id=<?php echo $request_id; ?>" method="POST">
                <div class="mb-3">
                    <label for="id" class="form-label">الهوية:</label>
                    <input type="text" class="form-control" id="id" name="id" value="<?php echo $request['id_number']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">وصف الطلب:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $request['description']; ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="smoker" class="form-label">هل أنت مدخن؟</label>
                    <select class="form-control" id="smoker" name="smoker" required>
                        <option value="yes" <?php if ($request['smoker'] == 'yes') echo 'selected'; ?>>نعم</option>
                        <option value="no" <?php if ($request['smoker'] == 'no') echo 'selected'; ?>>لا</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">السعر المطلوب للإيجار:</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo $request['price']; ?>" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">تعديل الطلب</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

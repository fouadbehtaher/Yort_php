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

// دالة للحصول على التوصيات بناءً على نوع المستخدم
function get_recommendations($user_type, $conn) {
    if ($user_type === 'student') {
        // إذا كان المستخدم طالبًا، نوصي بالشقق
        $sql = "SELECT * FROM requests WHERE user_type = 'owner' LIMIT 5";
    } else {
        // إذا كان المستخدم مالكًا، نوصي بالطلاب
        $sql = "SELECT * FROM requests WHERE user_type = 'student' LIMIT 5";
    }
    return $conn->query($sql);
}

// معالجة إضافة الطلب
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // التحقق من الحقول المطلوبة
    if (!empty($_POST['id']) && !empty($_POST['description']) && isset($_POST['agree_terms']) && !empty($_POST['user_type'])) {
        
        // التأكد من أن المستخدم وافق على شروط وصلاحيات استخدام الموقع
        if (!isset($_POST['agree_terms']) || $_POST['agree_terms'] != 'on') {
            echo "يجب عليك الموافقة على شروط وصلاحيات استخدام الموقع لإتمام الطلب.";
            exit();
        }

        $id = $_POST['id'];
        $description = $_POST['description'];
        $title = 'طلب جديد'; // يمكن تعديل النص حسب الحاجة
        $price = $_POST['price'];
        $smoker = isset($_POST['smoker']) ? $_POST['smoker'] : null;
        $user_type = $_POST['user_type'];

        // التحقق من السعر بحيث لا يزيد عن 5 أرقام
        if (strlen($price) > 5) {
            echo "السعر يجب ألا يزيد عن 5 أرقام.";
            exit();
        }

        // المتغير الذي سيحتوي على الاستعلام النهائي
        $sql = '';

        // التحقق من نوع المستخدم: طالب أو مالك
        if ($user_type === 'student') {
            // متغيرات الطالب
            $university_id = $_POST['university_id']; // رقم الهوية الجامعية
            $university_name = $_POST['university_name']; // اسم الجامعة
            $student_image = $_FILES['student_image']['name'];
            $smoking_preference = $_POST['smoking_preference']; // الإجابة عن رفقة المدخنين
            $has_ac = $_POST['has_ac']; // هل تريد تكييف؟
            $num_acs = $_POST['num_acs']; // عدد التكييفات

            // التأكد من صحة رقم الهوية الجامعية (لا يزيد عن 7 أرقام)
            if (strlen($university_id) > 7) {
                echo "رقم الهوية الجامعية يجب ألا يزيد عن 7 أرقام.";
                exit();
            }

            // رفع صورة الطالب إذا تم رفعها
            if (!empty($student_image)) {
                $target = "uploads/" . basename($student_image);
                if (!move_uploaded_file($_FILES['student_image']['tmp_name'], $target)) {
                    echo "حدث خطأ أثناء رفع صورة الطالب.";
                    exit();
                }
            }

            // إنشاء استعلام SQL لإضافة طلب الطالب
            $sql = "INSERT INTO requests (
                        id_number, 
                        title, 
                        description, 
                        smoker, 
                        price, 
                        university_id, 
                        university_name, 
                        student_image, 
                        smoking_preference, 
                        has_ac, 
                        num_acs, 
                        created_at, 
                        user_type
                    ) VALUES (
                        '$id', 
                        '$title', 
                        '$description', 
                        '$smoker', 
                        '$price', 
                        '$university_id', 
                        '$university_name', 
                        '$student_image', 
                        '$smoking_preference', 
                        '$has_ac', 
                        '$num_acs', 
                        NOW(), 
                        'student'
                    )";

        } elseif ($user_type === 'owner') {
            // متغيرات المالك
            $apartment_image = $_FILES['apartment_image']['name'];
            $has_ac = $_POST['has_ac']; // هل يوجد تكييف؟
            $num_acs_owner = $_POST['num_acs_owner']; // عدد التكييفات في حالة المالك

            // رفع صورة الشقة إذا تم رفعها
            if (!empty($apartment_image)) {
                $target = "uploads/" . basename($apartment_image);
                if (!move_uploaded_file($_FILES['apartment_image']['tmp_name'], $target)) {
                    echo "حدث خطأ أثناء رفع صورة الشقة.";
                    exit();
                }
            }

            // إنشاء استعلام SQL لإضافة طلب المالك
            $sql = "INSERT INTO requests (
                        id_number, 
                        title, 
                        description, 
                        smoker, 
                        price, 
                        apartment_image, 
                        has_ac, 
                        num_acs, 
                        created_at, 
                        user_type
                    ) VALUES (
                        '$id', 
                        '$title', 
                        '$description', 
                        '$smoker', 
                        '$price', 
                        '$apartment_image', 
                        '$has_ac', 
                        '$num_acs_owner', 
                        NOW(), 
                        'owner'
                    )";

        } else {
            echo "الرجاء تحديد ما إذا كنت طالبًا أو مالكًا.";
            exit();
        }

        // التحقق من وجود استعلام قبل تنفيذه
        if (!empty($sql)) {
            if ($conn->query($sql) === TRUE) {
                // تخزين رسالة النجاح في الجلسة
                $_SESSION['success_message'] = "تم إضافة طلبك بنجاح!";

                // الحصول على التوصيات بناءً على نوع المستخدم
                $recommendations = get_recommendations($user_type, $conn);

                // تخزين التوصيات في الجلسة فقط إذا كانت متاحة
                if ($recommendations->num_rows > 0) {
                    $_SESSION['recommendations'] = $recommendations->fetch_all(MYSQLI_ASSOC);
                } else {
                    $_SESSION['recommendations'] = []; // إذا لم توجد توصيات
                }

                // إعادة التوجيه إلى صفحة عرض الطلبات
                header('Location: requests.php');
                exit();
            } else {
                echo "خطأ في التنفيذ: " . $conn->error;
            }
        } else {
            echo "لم يتم إنشاء استعلام صالح.";
        }
    } else {
        echo "يرجى ملء جميع الحقول المطلوبة والموافقة على شروط وصلاحيات استخدام الموقع.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة طلب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('add-request-image.webp');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 15px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
            padding: 10px;
        }

        h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        .form-label, .form-control {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .card {
                padding: 10px;
            }

            h2 {
                font-size: 18px;
            }

            .form-label, .form-control {
                font-size: 12px;
            }

            .btn-primary {
                font-size: 12px;
                padding: 6px 10px;
            }
        }

        #num_acs_field, #num_acs_owner_field {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="col-md-6">
        <div class="card shadow">
            <h2 class="text-center mb-3">إضافة طلب جديد</h2>
            <form action="add_request.php" method="POST" enctype="multipart/form-data">

                <!-- السؤال الرئيسي: هل أنت طالب أم مالك؟ -->
                <div class="mb-2">
                    <label for="user_type" class="form-label">هل أنت:</label>
                    <select class="form-control" id="user_type" name="user_type" required>
                        <option value="">اختر...</option>
                        <option value="student">طالب</option>
                        <option value="owner">مالك</option>
                    </select>
                </div>

                <!-- الحقول الخاصة بالطالب -->
                <div id="student_fields" style="display:none;">
                    <div class="mb-2">
                        <label for="university_name" class="form-label">اسم الجامعة:</label>
                        <input type="text" class="form-control" id="university_name" name="university_name">
                    </div>

                    <div class="mb-2">
                        <label for="university_id" class="form-label">رقم الهوية الجامعية:</label>
                        <input type="text" class="form-control" id="university_id" name="university_id" maxlength="7" oninput="this.value = this.value.slice(0, 7);">
                    </div>

                    <div class="mb-2">
                        <label for="student_image" class="form-label">صورة الطالب (اختياري):</label>
                        <input type="file" class="form-control" id="student_image" name="student_image">
                    </div>

                    <div class="mb-2">
                        <label for="smoking_preference" class="form-label">هل تريد رفقة مدخنين؟</label>
                        <select class="form-control" id="smoking_preference" name="smoking_preference">
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="has_ac" class="form-label">هل تريد تكييف؟</label>
                        <select class="form-control" id="has_ac" name="has_ac">
                            <option value="">اختر...</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>
                        </select>
                    </div>

                    <div class="mb-2" id="num_acs_field">
                        <label for="num_acs" class="form-label">عدد التكييفات:</label>
                        <input type="number" class="form-control" id="num_acs" name="num_acs">
                    </div>
                </div>

                <!-- الحقول الخاصة بالمالك -->
                <div id="owner_fields" style="display:none;">
                    <div class="mb-2">
                        <label for="has_ac_owner" class="form-label">هل يوجد تكييف؟</label>
                        <select class="form-control" id="has_ac_owner" name="has_ac">
                            <option value="">اختر...</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>
                        </select>
                    </div>

                    <div class="mb-2" id="num_acs_owner_field">
                        <label for="num_acs_owner" class="form-label">عدد التكييفات:</label>
                        <input type="number" class="form-control" id="num_acs_owner" name="num_acs_owner">
                    </div>

                    <div class="mb-2">
                        <label for="apartment_image" class="form-label">صورة الشقة:</label>
                        <input type="file" class="form-control" id="apartment_image" name="apartment_image">
                    </div>
                </div>

                <!-- رقم الهوية القومية (مشترك للطالب والمالك) -->
                <div class="mb-2">
                    <label for="id" class="form-label">الهوية القومية:</label>
                    <input type="text" class="form-control" id="id" name="id" maxlength="14" required>
                </div>

                <!-- السعر المطلوب للإيجار -->
                <div class="mb-2">
                    <label for="price" class="form-label">السعر المطلوب للإيجار:</label>
                    <input type="number" class="form-control" id="price" name="price" maxlength="5" max="99999" required>
                </div>

                <!-- الوصف (مشترك للطالب والمالك) -->
                <div class="mb-2">
                    <label for="description" class="form-label">وصف الطلب:</label>
                    <textarea class="form-control" id="description" name="description" rows="2" required></textarea>
                </div>

                <!-- Checkbox للموافقة على الشروط -->
                <div class="mb-2">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required>
                    <label for="agree_terms">أوافق على <a href="terms.php" target="_blank">شروط وصلاحيات استخدام الموقع</a>.</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">إضافة الطلب</button>
            </form>
        </div>
    </div>
</div>

<script>
    // التحكم في إظهار وإخفاء الحقول بناءً على نوع المستخدم
    document.getElementById('user_type').addEventListener('change', function() {
        var userType = this.value;
        if (userType === 'student') {
            document.getElementById('student_fields').style.display = 'block';
            document.getElementById('owner_fields').style.display = 'none';
        } else if (userType === 'owner') {
            document.getElementById('student_fields').style.display = 'none';
            document.getElementById('owner_fields').style.display = 'block';
        } else {
            document.getElementById('student_fields').style.display = 'none';
            document.getElementById('owner_fields').style.display = 'none';
        }
    });

    // التحكم في ظهور حقل عدد التكييفات بناءً على اختيار "هل يوجد تكييف؟"
    document.getElementById('has_ac').addEventListener('change', function() {
        var hasAc = this.value;
        if (hasAc === 'yes') {
            document.getElementById('num_acs_field').style.display = 'block';
        } else {
            document.getElementById('num_acs_field').style.display = 'none';
        }
    });

    // التحكم في ظهور حقل عدد التكييفات في حالة المالك
    document.getElementById('has_ac_owner').addEventListener('change', function() {
        var hasAcOwner = this.value;
        if (hasAcOwner === 'yes') {
            document.getElementById('num_acs_owner_field').style.display = 'block';
        } else {
            document.getElementById('num_acs_owner_field').style.display = 'none';
        }
    });

    // تحديد الحد الأقصى للسعر
    document.getElementById('price').addEventListener('input', function() {
        if (this.value.length > 5) {
            this.value = this.value.slice(0, 5);
        }
    });

    // التحقق من الموافقة على الشروط قبل الإرسال
    document.querySelector('form').addEventListener('submit', function(event) {
        var agreeTerms = document.getElementById('agree_terms');
        if (!agreeTerms.checked) {
            alert("يجب عليك الموافقة على شروط وصلاحيات استخدام الموقع قبل تقديم الطلب.");
            event.preventDefault(); // إلغاء تقديم النموذج إذا لم يتم الموافقة
        }
    });
</script>

</body>
</html>

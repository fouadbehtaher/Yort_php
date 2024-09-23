<?php
session_start();

// التحقق مما إذا كانت هناك رسالة نجاح
if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success text-center' role='alert'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الرئيسية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-image: url('home-image.webp'); /* استخدم صورة home-image.webp كخلفية */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
        }

        /* تحسين المظهر العام للكروت */
        .card {
            background-color: rgba(255, 255, 255, 0.8); /* لون خلفية البطاقة شفاف */
            border-radius: 10px; /* زوايا مدورة للكروت */
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* ظل ناعم للكروت */
            transition: all 0.3s ease-in-out; /* تأثير الانتقال عند التفاعل */
        }

        /* تأثير Hover للكروت */
        .card:hover {
            transform: scale(1.05); /* تكبير بسيط عند تمرير الفأرة */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .card-icon {
            font-size: 3rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .container {
            margin-top: 100px;
        }

        h1, p {
            opacity: 0;
            animation: fadeInUp 2s forwards ease-out;
        }

        h1 {
            animation-delay: 0.5s;
            font-weight: bold;
            font-size: 2.5rem; /* تكبير حجم النص */
        }

        p {
            animation-delay: 1s;
            font-size: 1.2rem; /* تكبير حجم النص */
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* تحسين العرض على الأجهزة المحمولة */
        @media (max-width: 768px) {
            .card {
                padding: 20px;
            }

            h1 {
                font-size: 1.8rem;
            }

            p {
                font-size: 1rem;
            }
        }

        /* تنسيق الفوتر */
        .footer {
            margin-top: 50px;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            text-align: center;
            color: white;
        }

        .footer a {
            color: #ffffff;
            margin: 0 15px;
            text-decoration: none;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #1e90ff;
        }

        .footer p {
            margin: 10px 0;
        }

        .footer i {
            margin-right: 5px;
        }

        /* تحسين تفاعل الأزرار */
        .btn {
            font-size: 1rem;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-info:hover {
            background-color: #17a2b8;
            transform: translateY(-2px);
        }

        .btn-secondary:hover {
            background-color: #6c757d;
            transform: translateY(-2px);
        }

        /* إضافة تأثيرات الرسائل */
        .alert {
            transition: all 0.5s ease-in-out;
        }

    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <h1 class="text-white">مرحبًا بك في نظام إدارة الطلبات</h1>
                <p class="text-light">استمتع باستخدام منصتنا لإدارة طلبات الإيجار بطريقة سهلة وفعالة</p>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow text-center">
                        <div class="card-body">
                            <i class="fas fa-plus-circle card-icon"></i>
                            <h5 class="card-title">إضافة طلب جديد</h5>
                            <p class="card-text">قم بإضافة طلب جديد بكل سهولة عبر هذا الرابط.</p>
                            <a href="add_request.php" class="btn btn-primary w-100">إضافة طلب</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow text-center">
                        <div class="card-body">
                            <i class="fas fa-list card-icon"></i>
                            <h5 class="card-title">عرض الطلبات المتاحة</h5>
                            <p class="card-text">استعرض جميع الطلبات المتاحة وتفاعل معها بسهولة.</p>
                            <a href="requests.php" class="btn btn-info w-100">عرض الطلبات</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow text-center">
                        <div class="card-body">
                            <i class="fas fa-user card-icon"></i>
                            <h5 class="card-title">الملف الشخصي</h5>
                            <p class="card-text">قم بتحديث بيانات ملفك الشخصي ومتابعة حسابك.</p>
                            <a href="profile.php" class="btn btn-secondary w-100">الملف الشخصي</a>
                        </div>
                    </div>
                </div>

                <!-- الصف الإضافي لعرض المزيد من الكروت -->
                <div class="col-md-4 mt-4">
                    <div class="card shadow text-center">
                        <div class="card-body">
                            <i class="fas fa-info-circle card-icon"></i>
                            <h5 class="card-title">من نحن</h5>
                            <p class="card-text">تعرف على المزيد حول منصتنا وفريق العمل.</p>
                            <a href="about.php" class="btn btn-light w-100">من نحن</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mt-4">
                    <div class="card shadow text-center">
                        <div class="card-body">
                            <i class="fas fa-sign-out-alt card-icon"></i>
                            <h5 class="card-title">تسجيل الخروج</h5>
                            <p class="card-text">قم بتسجيل الخروج للحفاظ على أمان حسابك.</p>
                            <a href="logout.php" class="btn btn-danger w-100">تسجيل الخروج</a>
                        </div>
                    </div>
                </div>

                <!-- المزيد من الأعمدة يمكن إضافتها هنا بنفس الطريقة -->
            </div>
        </div>
    </div>
</div>

<!-- Footer Section -->
<div class="footer">
    <p>© 2024 - حقوق النشر محفوظة - FouadBehTaher</p>
    <a href="https://www.facebook.com/TaMaTEM.SniPeR.Hack?mibextid=LQQJ4d" target="_blank">
        <i class="fab fa-facebook-f"></i> Facebook
    </a>
    <a href="https://wa.me/00201147794004" target="_blank">
        <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
    <a href="https://github.com/fouadbehtaher" target="_blank">
        <i class="fab fa-github"></i> GitHub
    </a>
</div>

<!-- إضافة مكتبة Font Awesome للأيقونات -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- إخفاء الرسالة بعد 5 ثوانٍ -->
<script>
    setTimeout(function() {
        let alert = document.querySelector('.alert');
        if(alert) {
            alert.style.display = 'none';
        }
    }, 5000);
</script>

</body>
</html>

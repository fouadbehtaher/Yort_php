<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>من نحن</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-image: url('YourYort.webp'); /* ضع رابط صورة الخلفية هنا بصيغة webp */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.8); /* لون خلفية البطاقة شفاف */
            border: none;
        }

        .about-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* تنسيق الفوتر */
        .footer {
            margin-top: 50px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            text-align: center;
            color: white;
        }

        .footer a {
            color: #007bff;
            margin: 0 15px;
            text-decoration: none;
            font-size: 1.5rem;
        }

        .footer a:hover {
            color: #0056b3;
        }

        .footer p {
            margin: 10px 0;
        }

        .footer i {
            margin-right: 5px;
        }

        /* زر العودة إلى الصفحة الرئيسية */
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

    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h2>من نحن</h2>
                </div>
                <div class="card-body text-center">
                    <img src="Yort3.webp" alt="Image representing our mission" class="about-image"> <!-- ضع رابط الصورة هنا بصيغة webp -->
                    <p class="lead">نحن هنا لنساعدك في إدارة طلبات الإيجار بطريقة سهلة وفعالة. نقدم منصة موثوقة وآمنة لكل المستخدمين لتقديم وعرض الطلبات العقارية، سواء كنت طالبًا أو مالكًا.</p>
                    <p>نحن نؤمن بأن تسهيل عملية البحث عن الشقق وتقديم الطلبات يمكن أن يساهم في تحسين تجربتك ويجعلك تتمتع بوقت أكثر في حياتك اليومية.</p>
                    <img src="Yort2.webp" alt="Image representing our mission" class="about-image"> <!-- صورة أخرى بصيغة webp تدعم الفكرة -->

                    <!-- زر العودة إلى الصفحة الرئيسية -->
                    <a href="home.php" class="btn-home">العودة إلى الصفحة الرئيسية</a>
                </div>
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
        <i class="fab fa-whatsapp"></i> Whatsapp
    </a>
    <a href="https://github.com/fouadbehtaher" target="_blank">
        <i class="fab fa-github"></i> GitHub
    </a>
</div>

<!-- إضافة مكتبة Font Awesome للأيقونات -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>

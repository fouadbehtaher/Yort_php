<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شروط وصلاحيات استخدام الموقع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('terms-image.webp'); /* استخدم صورة الخلفية terms-image.webp */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            height: 100vh;
            color: white; /* جعل النص أبيض ليتناسب مع الخلفية */
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7); /* جعل الخلفية شفافة قليلاً */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            color: #f8f9fa;
            margin-bottom: 20px;
        }

        p, ul li {
            color: #f8f9fa;
            text-align: justify;
            margin-bottom: 20px; /* إضافة مسافة بين الفقرات */
        }

        /* تحسين العرض على الأجهزة المحمولة */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
        }

        /* تحسين مظهر الأزرار */
        .accordion-button {
            background-color: #007bff;
            color: #fff;
            border: none;
        }

        .accordion-button:not(.collapsed) {
            background-color: #0056b3;
            color: #fff;
        }

        .accordion-body {
            color: #f8f9fa;
            background-color: rgba(0, 0, 0, 0.6);
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">شروط وصلاحيات استخدام الموقع</h2>

    <!-- Accordion for Terms -->
    <div class="accordion" id="termsAccordion">
        <!-- المقدمة -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingIntro">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIntro" aria-expanded="true" aria-controls="collapseIntro">
                    المقدمة
                </button>
            </h2>
            <div id="collapseIntro" class="accordion-collapse collapse show" aria-labelledby="headingIntro" data-bs-parent="#termsAccordion">
                <div class="accordion-body">
                    باستخدام هذا الموقع الإلكتروني، فإنك توافق على الالتزام بالشروط والأحكام التالية. إذا كنت لا توافق على أي من هذه الشروط، فلا يجوز لك استخدام الموقع.
                </div>
            </div>
        </div>

        <!-- الأهلية -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingEligibility">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEligibility" aria-expanded="false" aria-controls="collapseEligibility">
                    الأهلية
                </button>
            </h2>
            <div id="collapseEligibility" class="accordion-collapse collapse" aria-labelledby="headingEligibility" data-bs-parent="#termsAccordion">
                <div class="accordion-body">
                    يجب أن يكون عمر المستخدم 18 عامًا أو أكثر لاستخدام هذا الموقع. باستخدام الموقع، تقر بأنك تفي بهذا الشرط.
                </div>
            </div>
        </div>

        <!-- شروط استخدام الموقع للطلاب -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingStudents">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStudents" aria-expanded="false" aria-controls="collapseStudents">
                    شروط استخدام الموقع للطلاب
                </button>
            </h2>
            <div id="collapseStudents" class="accordion-collapse collapse" aria-labelledby="headingStudents" data-bs-parent="#termsAccordion">
                <div class="accordion-body">
                    <ul>
                        <li><strong>الدقة في المعلومات:</strong> يلتزم الطالب بتقديم معلومات صحيحة ودقيقة عند تقديم أي طلب.</li>
                        <li><strong>الغرض من الاستخدام:</strong> يقتصر استخدام الموقع على البحث عن وحدات سكنية للإيجار فقط.</li>
                        <li><strong>الالتزام بالاتفاقيات:</strong> عند التواصل مع مالك الشقة والاتفاق على الإيجار، يلتزم الطالب بجميع الشروط المالية والقانونية.</li>
                        <li><strong>حماية الحساب:</strong> يلتزم الطالب بالحفاظ على أمان حسابه وعدم مشاركة معلومات تسجيل الدخول مع أي طرف آخر.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- شروط استخدام الموقع لملاك الشقق -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOwners">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOwners" aria-expanded="false" aria-controls="collapseOwners">
                    شروط استخدام الموقع لملاك الشقق
                </button>
            </h2>
            <div id="collapseOwners" class="accordion-collapse collapse" aria-labelledby="headingOwners" data-bs-parent="#termsAccordion">
                <div class="accordion-body">
                    <ul>
                        <li><strong>الدقة في المعلومات:</strong> يلتزم المالك بتقديم معلومات صحيحة حول الشقة.</li>
                        <li><strong>المسؤولية القانونية:</strong> يتحمل المالك المسؤولية الكاملة عن أي اتفاق يتم بينه وبين الطالب.</li>
                        <li><strong>التزامات التعاقد:</strong> يتعهد المالك بتوقيع عقود الإيجار الرسمية والالتزام بالقوانين المحلية.</li>
                        <li><strong>الشفافية في التسعير:</strong> يجب أن يكون سعر الإيجار المعلن هو السعر الفعلي النهائي دون أي رسوم خفية.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- بقية البنود -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOtherTerms">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOtherTerms" aria-expanded="false" aria-controls="collapseOtherTerms">
                    بقية البنود
                </button>
            </h2>
            <div id="collapseOtherTerms" class="accordion-collapse collapse" aria-labelledby="headingOtherTerms" data-bs-parent="#termsAccordion">
                <div class="accordion-body">
                    <ul>
                        <li><strong>حفظ البيانات:</strong> يوافق المستخدم على أن يتم تخزين واستخدام بياناته الشخصية وفقًا لسياسة الخصوصية الخاصة بالموقع.</li>
                        <li><strong>الحقوق الفكرية:</strong> جميع المحتويات المعروضة على الموقع هي ملك للموقع ولا يجوز نسخها أو إعادة نشرها.</li>
                        <li><strong>القيود:</strong> يحظر استخدام الموقع لأي غرض غير قانوني أو غير أخلاقي.</li>
                        <li><strong>التحديثات والتعديلات:</strong> يحتفظ الموقع بالحق في تعديل أو تحديث هذه الشروط في أي وقت.</li>
                        <li><strong>إنهاء الخدمة:</strong> يحتفظ الموقع بالحق في إنهاء أو تعليق حساب المستخدم في حالة انتهاك أي من الشروط.</li>
                        <li><strong>المسؤولية القانونية:</strong> لا يتحمل الموقع أي مسؤولية قانونية عن الاتفاقيات أو المعاملات بين الطلاب وملاك الشقق.</li>
                        <li><strong>الموافقة على الشروط:</strong> باستخدامك لهذا الموقع وإنشاء طلب، فإنك توافق على جميع الشروط والصلاحيات.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

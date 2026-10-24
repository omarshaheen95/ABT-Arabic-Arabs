
<?php
    if (app()->getLocale()=='ar'){
      $signature_2 = 'المديرُ التنفيذيُّ:';
      $signature_3 = 'أ. محمد جمال';
      $date = 'التاريخ:';
      $student = 'الطالب/ة : ';
      $dir='rtl';
      $local='ar';

    }else{
      $signature_2 = 'Executive Director';
      $signature_3 = 'Mr. Mohamed Gamal';
      $date = 'Date:';
      $dir = 'ltr';
      $local='en';
    }
?>
<html lang="ar" dir="rtl">
<head>
    <link rel="stylesheet" href="<?php echo e(asset('certification/css/bootstrap-5.0.2/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('certification/css/style.css')); ?>?v=2">
    <title>Certificate Awarded to <?php echo e($certificate->user->name); ?></title>
    <link rel="shortcut icon" href="<?php echo e(asset('web_assets/img/logo.svg')); ?>" type="image/x-icon">
</head>
<body class="no-page-break">
<?php if($certificate->model_type == \App\Models\Lesson::class): ?>
    <div class="page no-page-break position-relative p-0 d-flex justify-content-center">
        <div class="position-absolute">
            <img src="<?php echo e(asset('certification/img/border.svg')); ?>" style="width: 296mm; min-height: 209mm;">
        </div>
        <div class="subpage no-page-break">
            <div class="row mb-5">
                <img src="<?php echo e(asset('certification/img/header.svg')); ?>">
            </div>
            <div class="d-flex flex-column align-items-center gap-1" style="padding-top: 40px">
                <div class="d-flex justify-content-center mb-2 gap-1">
                    <h5 class="p-text"> تُمنح هذه الشهادة إلى </h5>
                    <h5 class="p-text"> : </h5>
                    <h5 class="p-s-text"><?php echo e($certificate->user->name); ?></h5>
                </div>

                <div class="d-flex justify-content-center gap-1 text-center">
                    <h6 class="s-text">تقديرًا لتقدُّمه/ها في تعلّم اللغة العربية من خلال إتقانه/ها لدرس : <br>
                        <span class="s-s-text" dir="rtl"><?php echo e($certificate->model->name); ?></span> </h6>
                </div>
                <div class="d-flex justify-content-center gap-1">
                    <h6 class="s-text"><?php echo e($certificate->model->grade->name); ?></h6>
                </div>
                <div class="d-flex justify-content-center gap-1">
                    <h6 class="s-text"></h6>
                </div>
                <div class="d-flex justify-content-center">
                    <h6 class="s-text">خلال رحلته/ها في تعلّم اللغة العربية عبر منصة لغتي الأولى.</h6>
                </div>
                <div class="signature-section mt-5">
                    <div class="signature-block">
                        <span class="signature-title"><?php echo e($signature_2); ?></span>
                        <span class="signature-name"><?php echo e($signature_3); ?></span>
                        <img src="<?php echo e(asset('certification/img/seo_signature.png')); ?>" style="height: 50px;" alt="Signature">
                    </div>
                    <div class="signature-block">
                        <img src="<?php echo e(asset('certification/img/signature_v2.png')); ?>" style="height: 90px;" alt="Signature">
                    </div>
                    <div class="signature-block">
                        <span class="signature-title"><?php echo e($date); ?></span>
                        <span class="signature-name"><?php echo e(date('d-m-Y')); ?></span>
                    </div>
                </div>

                <img class="logos" src="<?php echo e(asset('certification/img/logos_group_v2.svg')); ?>?v=1" alt="Logos">




            </div>

        </div>
    </div>
<?php else: ?>
    <div class="page no-page-break position-relative p-0 d-flex justify-content-center">
        <div class="position-absolute">
            <img src="<?php echo e(asset('certification/img/border.svg')); ?>" style="width: 296mm; min-height: 209mm;">
        </div>
        <div class="subpage no-page-break">
            <div class="row mb-5">
                <img src="<?php echo e(asset('certification/img/header.svg')); ?>">
            </div>
            <div class="d-flex flex-column align-items-center gap-1" style="padding-top: 40px">
                <div class="d-flex justify-content-center mb-2 gap-1">
                    <h5 class="p-text"> تُمنح هذه الشهادة إلى </h5>
                    <h5 class="p-text"> : </h5>
                    <h5 class="p-s-text"><?php echo e($certificate->user->name); ?></h5>
                </div>

                <div class="d-flex justify-content-center gap-1 text-center">
                    <h6 class="s-text">تقديرًا لتقدُّمه/ها  في تعلّم اللغة العربية من خلال قراءة قصة : <br>
                        <span class="s-s-text" dir="rtl"><?php echo e($certificate->model->name); ?></span> </h6>
                    </h6>
                </div>
                <div class="d-flex justify-content-center gap-1">
                    <h6 class="s-text"><?php echo e($certificate->model->grade_ar_name); ?></h6>
                </div>
                <div class="d-flex justify-content-center gap-1">
                    <h6 class="s-text"></h6>
                </div>
                <div class="d-flex justify-content-center">
                    <h6 class="s-text">خلال رحلته/ها في تعلّم اللغة العربية عبر منصة لغتي الأولى.</h6>
                </div>
                <div class="signature-section mt-5">
                    <div class="signature-block">
                        <span class="signature-title"><?php echo e($signature_2); ?></span>
                        <span class="signature-name"><?php echo e($signature_3); ?></span>
                        <img src="<?php echo e(asset('certification/img/seo_signature.png')); ?>" style="height: 50px;" alt="Signature">
                    </div>
                    <div class="signature-block">
                        <img src="<?php echo e(asset('certification/img/signature_v2.png')); ?>" style="height: 90px;" alt="Signature">
                    </div>
                    <div class="signature-block">
                        <span class="signature-title"><?php echo e($date); ?></span>
                        <span class="signature-name"><?php echo e(date('d-m-Y')); ?></span>
                    </div>
                </div>

                <img class="logos" src="<?php echo e(asset('certification/img/logos_group_v2.svg')); ?>?v=1" alt="Logos">





            </div>

        </div>
    </div>

<?php endif; ?>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        window.print();
    });
</script>
</body>
</html>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user/motivational_certificate.blade.php ENDPATH**/ ?>
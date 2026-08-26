
<?php $__env->startSection('header'); ?>
    <header class="header">
        <div class="intro-box">
            <h1 class="title">منصة لغتي الأولى</h1>
            <p class="sub-title">منصة مهنية إثرائية لتطوير مهارات اللغة العربية كلغة أولى للطلاب العرب تشمل جميع المهارات. </p>
            <p class="description">تحتوي المنصة على تمارين تفاعلية متنوعة ذات جودة عالية تهدف إلى تطوير مهارات اللغة.</p>


            <div class="btns">
                <a href="/register" class="btn btn-warning">
                    
                    <span>   ابدأ الأن </span>
                </a>
                
                
                
                
            </div>
        </div>
    </header>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Start price -->
    <section class="price-section" id="price">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title text-center">
                        <h2 class="title"> الاسعار </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="price-card">
                        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="/register?package_id=<?php echo e($package->id); ?>" class="price-box">
                                <h3 class="title"><?php echo e($package->name); ?></h3>
                                <h4 class="price"><?php echo e($package->price); ?>$</h4>
                                <div class="price-day">
                                    <div class="day" dir="rtl" style="direction:rtl !important">
                                        <?php echo e($package->days); ?>

                                        <?php if($package->days < 10): ?>
                                            أيام
                                        <?php else: ?>
                                            يوماً
                                        <?php endif; ?>


                                    </div>
                                    <button type="button" class="btn btn-theme"
                                            href="/register?package_id=<?php echo e($package->id); ?>"> اشتراك </button>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End price -->

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.container_2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/welcome.blade.php ENDPATH**/ ?>
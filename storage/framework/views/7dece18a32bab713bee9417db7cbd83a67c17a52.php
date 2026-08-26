<?php $__env->startPush('style'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('user_assets/css/pages/certificates.css')); ?>" />
<?php $__env->stopPush(); ?>
<?php $__env->startSection('page-name', 'certificates'); ?>

<?php $__env->startSection('content'); ?>
    <div class="certificates-container">
        <!-- Header Section -->
        <header class="certificates-header">
            <div class="header-text">
                <h1 class="certificates-title">الشهادات</h1>
                <p class="certificates-subtitle"><?php if($type=='lessons'): ?> شهادات الدروس <?php elseif($type=='stories'): ?> شهادات القصص <?php else: ?> شهادات تحفيزية  <?php endif; ?></p>
            </div>
            <div class="header-actions">
                <button class="view-toggle-btn glass-button-component" id="viewToggleBtn" data-view="grid" aria-label="Switch to table view">
                    <img class="grid-icon" src="<?php echo e(asset('user_assets/images/icons/grid.svg')); ?>" alt="Grid view" width="24" height="24">
                    <img class="table-icon" src="<?php echo e(asset('user_assets/images/icons/list.svg')); ?>" alt="Table view" width="24" height="24" style="display: none;">
                </button>
                <select class="filter-dropdown" id="certificatesFilter">
                    <option value="<?php echo e(route('certificate.index',['type' => 'lessons'])); ?>" <?php if($type=='lessons'): ?> selected <?php endif; ?>>شهادات الدروس</option>
                    <option value="<?php echo e(route('certificate.index',['type' => 'stories'])); ?>" <?php if($type=='stories'): ?> selected <?php endif; ?>> شهادات القصص</option>
                    <option value="<?php echo e(route('certificate.index',['type' => 'motivation'])); ?>" <?php if($type=='motivation'): ?> selected <?php endif; ?>>شهادات تحفيزية</option>
                </select>
            </div>
        </header>

        <?php switch($type):
            case ('lessons'): ?>
            <?php echo $__env->make('user.certificates.components.lessons_certificates',['student_test' => $student_tests], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>

            <?php case ('stories'): ?>
            <?php echo $__env->make('user.certificates.components.stories_certificates',['student_test' => $student_tests], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>

            <?php case ('motivation'): ?>
            <?php echo $__env->make('user.certificates.components.motivation_certificates',['student_test' => $student_tests], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php break; ?>
        <?php endswitch; ?>
    </div>

    <?php echo $__env->make('user.certificates.components.test_answer_dialog', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('user.general.components.loading_dialog', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('user_assets/js/pages/certificates.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/certificates/index.blade.php ENDPATH**/ ?>
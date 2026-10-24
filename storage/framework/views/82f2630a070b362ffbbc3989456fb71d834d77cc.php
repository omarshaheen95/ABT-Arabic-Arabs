
<?php $__env->startSection('pre_style'); ?>
    <link href="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5')); ?>" rel="stylesheet"
          type="text/css"/>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted">
        <a href="<?php echo e(route('manager.import_files.index')); ?>" class="text-muted">
            <?php echo e(t('Import Files')); ?>

        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        <?php echo e($title); ?>

    </li>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <!--begin::Card-->
    <div class="card ">
        <div class="card-header">
            <h3 class="card-label"><?php echo e($title); ?></h3>
        </div>
        <div class="card-body">

            <?php if(!is_null($file->error)): ?>
                <p><?php echo e($file->error); ?></p>
            <?php elseif(!is_null($file->failures)): ?>
                <table class="table table-bordered">
                    <thead>
                    <th>Rows In File</th>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $file->failures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>Row : <?php echo e($key); ?></td>
                        </tr>
                        <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log_error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td><?php echo e($log_error); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            <?php elseif(isset($error)): ?>
                <table class="table table-bordered">
                    <thead>
                    <th>Rows In File</th>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $error; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>Row : <?php echo e($key); ?></td>
                        </tr>
                        <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log_error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td><?php echo e($log_error); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            <?php endif; ?>


        </div>
    </div>
    <!--end::Card-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/import/show.blade.php ENDPATH**/ ?>
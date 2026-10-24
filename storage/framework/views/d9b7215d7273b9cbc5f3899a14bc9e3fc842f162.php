


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?php echo e($title); ?></h3>
                    </div>
                </div>

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
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
                    </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/import_old/show.blade.php ENDPATH**/ ?>
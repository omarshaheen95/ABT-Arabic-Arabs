<?php $__env->startSection('title'); ?>
    <?php echo e(t('Show Activity Log')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('manager.activity-log.index')); ?>">
            <?php echo e(t('Activity Log')); ?>

        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        <?php echo e(t('Show Activity Log')); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .text-overflow-dynamic-container {
            position: relative;
            max-width: 100%;
            padding: 0 !important;
            display: -webkit-flex;
            display: -moz-flex;
            display: flex;
            vertical-align: text-bottom !important;
        }
        .text-overflow-dynamic-ellipsis {
            position: absolute;
            white-space: nowrap;
            overflow-y: visible;
            overflow-x: hidden;
            text-overflow: ellipsis;
            -ms-text-overflow: ellipsis;
            -o-text-overflow: ellipsis;
            max-width: 100%;
            min-width: 0;
            width:100%;
            top: 0;
            left: 0;
        }
        .text-overflow-dynamic-container:after,
        .text-overflow-dynamic-ellipsis:after {
            content: '-';
            display: inline;
            visibility: hidden;
            width: 0;
        }
        .object-display, .array-display {
            text-align: left;
            padding: 8px;
            font-size: 13px;
            line-height: 1.4;
        }
        .object-display .mb-1, .array-display .mb-1 {
            margin-bottom: 4px;
            word-break: break-word;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
            <tr class="" style="background-color: #4ad386">
                <th scope="col" colspan="3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php echo e(t('Causer')); ?>: <?php echo e(optional($activity->causer)->name); ?>

                        </div>
                        <div>
                            <?php echo e(t('Date')); ?>: <?php echo e($activity->created_at); ?>


                        </div>
                    </div>

                </th>

            </tr>
            <tr class="" style="background-color: #9dbaff">

                <th scope="col" colspan="3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php echo e(t('Subject')); ?>: <a href="<?php echo e($activity->action_route); ?>"><?php echo e(class_basename($activity->subject_type)); ?></a>
                        </div>
                        <div>
                            <?php echo e(t('ID')); ?>: <?php echo e($activity->subject_id); ?>

                        </div>

                    </div>

                </th>

            </tr>

            <tr class="text-center">
                <th scope="col" style="background-color: #d1e1ff"><?php echo e(t('Column Name')); ?></th>
                <th scope="col" style="background-color: #ffd376"><?php echo e(t('Old Data')); ?></th>
                <th scope="col" style="background-color: #beffd9"><?php echo e(t('New Data')); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $new; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="text-center">
                    <th scope="row" style="background-color: #d1e1ff"><?php echo e($key); ?></th>
                    <td style="background-color: #ffd376">
                        <?php if(isset($old[$key])): ?>
                            <?php if(is_object($old[$key])): ?>
                                <div class="object-display">
                                    <?php $__currentLoopData = $old[$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $objKey => $objValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="mb-1">
                                            <strong><?php echo e($objKey); ?>:</strong>
                                            <?php if(is_array($objValue) || is_object($objValue)): ?>
                                                <?php echo e(json_encode($objValue, JSON_UNESCAPED_UNICODE)); ?>

                                            <?php else: ?>
                                                <?php echo e($objValue); ?>

                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php elseif(is_array($old[$key])): ?>
                                <div class="array-display">
                                    <?php $__currentLoopData = $old[$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arrKey => $arrValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="mb-1">
                                            <strong>[<?php echo e($arrKey); ?>]:</strong>
                                            <?php if(is_array($arrValue) || is_object($arrValue)): ?>
                                                <?php echo e(json_encode($arrValue, JSON_UNESCAPED_UNICODE)); ?>

                                            <?php else: ?>
                                                <?php echo e($arrValue); ?>

                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <span class="text-overflow-dynamic-container">
                                    <span class="text-overflow-dynamic-ellipsis">
                                        <?php echo e($old[$key]); ?>

                                    </span>
                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-overflow-dynamic-container">
                                <span class="text-overflow-dynamic-ellipsis">
                                    <?php echo e(t('Empty')); ?>

                                </span>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="background-color: #beffd9">
                        <?php if(is_object($value)): ?>
                            <div class="object-display">
                                <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $objKey => $objValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-1">
                                        <strong><?php echo e($objKey); ?>:</strong>
                                        <?php if(is_array($objValue) || is_object($objValue)): ?>
                                            <?php echo e(json_encode($objValue, JSON_UNESCAPED_UNICODE)); ?>

                                        <?php else: ?>
                                            <?php echo e($objValue); ?>

                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php elseif(is_array($value)): ?>
                            <div class="array-display">
                                <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arrKey => $arrValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-1">
                                        <strong>[<?php echo e($arrKey); ?>]:</strong>
                                        <?php if(is_array($arrValue) || is_object($arrValue)): ?>
                                            <?php echo e(json_encode($arrValue, JSON_UNESCAPED_UNICODE)); ?>

                                        <?php else: ?>
                                            <?php echo e($arrValue); ?>

                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <span class="text-overflow-dynamic-container">
                                <span class="text-overflow-dynamic-ellipsis">
                                    <?php echo e($value); ?>

                                </span>
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>

        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/activity-log/edit.blade.php ENDPATH**/ ?>
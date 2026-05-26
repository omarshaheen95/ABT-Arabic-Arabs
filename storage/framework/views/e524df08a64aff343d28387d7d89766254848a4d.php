

<?php $__env->startSection('title',$title); ?>
<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('manager.lesson.training.edit', [$lesson->id])); ?>" target="_blank" class="btn btn-primary btn-elevate btn-icon-sm me-2">
        <i class="la la-edit"></i>
        <?php echo e(t('Edit Questions')); ?>

    </a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-1 mb-2">
            <label class="mb-2"><?php echo e(t('ID')); ?>:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="<?php echo e(t('ID')); ?>">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Question')); ?>:</label>
            <input type="text" name="content" class="form-control direct-search" placeholder="<?php echo e(t('Question')); ?>">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Type')); ?> :</label>
            <select name="type" id="type" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Type')); ?>" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('True&False')); ?></option>
                <option value="2"><?php echo e(t('Choose')); ?></option>
                <option value="3"><?php echo e(t('Match')); ?></option>
                <option value="4"><?php echo e(t('Sort Words')); ?></option>
            </select>
        </div>



    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <?php echo e($title); ?>

    </li>
    <li class="breadcrumb-item">
        <?php echo e($lesson->name); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-row-bordered gy-5" id="datatable">
                <thead>
                <tr class="fw-semibold fs-6 text-gray-800">
                    <th class="text-start"></th>
                    <th class="text-start"><?php echo e(t('Question')); ?></th>
                    <th class="text-start"><?php echo e(t('Type')); ?></th>
                    <th class="text-start"><?php echo e(t('Actions')); ?></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        var DELETE_URL = "<?php echo e(route('manager.lesson.training.delete-question')); ?>";
        var TABLE_URL = "<?php echo e(route('manager.lesson.training.index', $lesson)); ?>";

        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'content', name: 'content'},
            {data: 'type_name', name: 'type_name'},
            {data: 'actions', name: 'actions'}
        ];
    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/lesson/training/index.blade.php ENDPATH**/ ?>
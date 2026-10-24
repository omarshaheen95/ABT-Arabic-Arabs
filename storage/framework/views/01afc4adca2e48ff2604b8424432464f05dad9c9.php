

<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>

    <div class="dropdown with-filter checked-visible">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item d-none checked-visible" href="#!" id="hide_stories"><?php echo e(t('Hide')); ?></a></li>
            <li><a class="dropdown-item d-none checked-visible" href="#!" id="show_stories"><?php echo e(t('Show')); ?></a></li>
        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Grade')); ?> :</label>
            <select name="grade" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Grade')); ?>" data-allow-clear="true">
                <option></option>
                <?php $__currentLoopData = storyGradesSys(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e($grade); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Story')); ?> :</label>
            <select  name="id" id="story_id" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Story')); ?>" data-allow-clear="true">
                <option></option>
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Status')); ?> :</label>
            <select  name="hidden_status" id="hidden_status" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Active')); ?></option>
                <option value="2"><?php echo e(t('Hidden')); ?></option>
            </select>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start"><?php echo e(t('Story')); ?></th>
                <th class="text-start"><?php echo e(t('Grade')); ?></th>
                <th class="text-start"><?php echo e(t('Status')); ?></th>
            </tr>
            </thead>
        </table>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets_v1/js/custom.js')); ?>?v=<?php echo e(time()); ?>"></script>

    <script>
        var TABLE_URL = "<?php echo e(route('school.stories.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'story', name: 'story'},
            {data: 'grade', name: 'grade'},
            {data: 'status', name: 'status'},
        ];


        $('#hide_stories').on('click',function () {
            showLoadingModal()
            $.ajax({
                type: "POST",
                url: '<?php echo e(route("school.stories.hide")); ?>',
                data:{
                    '_token':'<?php echo e(csrf_token()); ?>',
                    stories_ids:getSelectedRows()
                },
                success:function (response) {
                    hideLoadingModal()
                    toastr.success(response.message)
                    table.DataTable().draw(true);
                },
                error:function (error) {
                    hideLoadingModal()
                    toastr.error(error.responseJSON.message)
                }
            })
        });
        $('#show_stories').on('click',function () {
            showLoadingModal()
            $.ajax({
                type: "POST",
                url: '<?php echo e(route("school.stories.show")); ?>',
                data:{
                    '_token':'<?php echo e(csrf_token()); ?>',
                    stories_ids:getSelectedRows()
                },
                success:function (response) {
                    hideLoadingModal()
                    toastr.success(response.message)
                    table.DataTable().draw(true);
                },
                error:function (error) {
                    hideLoadingModal()
                    toastr.error(error.responseJSON.message)
                }
            })
        });

        getStoriesByGrade()

    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('school.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/school/hidden_control/stories.blade.php ENDPATH**/ ?>
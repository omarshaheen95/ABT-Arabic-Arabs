<?php $__env->startSection('style'); ?>
    <style>
        .error {
            color: red;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted">
        <a href="<?php echo e(route('manager.import_files.index')); ?>" class="text-muted">
            <?php echo e(t('Student Data Files')); ?>

        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-3 mb-2">
            <label class="mb-1"><?php echo e(t('Row Num')); ?>:</label>
            <input type="number" min="0" name="row_num" class="form-control direct-search"
                   placeholder="<?php echo e(t('Row Num')); ?>"/>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('actions'); ?>
    <button type="button" class="btn btn-success save_rows run-indicator" id="save-form-data">
        <span class="indicator-label"><?php echo e(t('Save')); ?></span>
        <span class="indicator-progress"><?php echo e(t('Please wait...')); ?> <span
                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
    </button>

    <button type="button" class="btn btn-warning d-none checked-visible" id="delete_rows">
        <span>
            <i class="la la-trash"></i>
            <span><?php echo e(t('Delete')); ?></span>
        </span>
    </button>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('manager.import_files.save_logs')); ?>" method="POST" id="form-data">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" name="student_data_file_id" value="<?php echo e($studentDataFile->id); ?>">
        <input type="hidden" name="school_id" id="<?php echo e($studentDataFile->school_id); ?>">
        <table class="table table-row-bordered table-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start"><?php echo e(t('R N')); ?></th>
                <th class="text-start"><?php echo e(t('Data')); ?></th>
                <th class="text-start"><?php echo e(t('Errors')); ?></th>
            </tr>
            </thead>
        </table>
        
        <div class="row my-5">
            <div class="separator separator-content my-4"></div>
            <div class="col-12 d-flex justify-content-end">
                <button type="button" class="btn btn-primary save_rows run-indicator" id="save_rows">
                    <span class="indicator-label">
        <?php echo e(t('Save')); ?>

    </span>
                    <span class="indicator-progress">
        <?php echo e(t('Please wait...')); ?> <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
    </span>
                </button>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\ImportFileLogDataRequest::class, '#form-data'); ?>


    <script>
        var DELETE_URL = "<?php echo e(route('manager.import_files.delete_logs')); ?>";
        var TABLE_URL = "<?php echo e(route('manager.import_files.show_logs', $studentDataFile->id)); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'row_number', name: 'row_number'},
            {data: 'data', name: 'data'},
            {data: 'errors', name: 'errors'},
        ];

        //add class data to 3rd column
        COLUMN_DEFS = [{className: 'data', targets: [2]}]

        const SAVE_URL = "<?php echo e(route('manager.import_files.save_logs')); ?>";


    </script>
    <script src="<?php echo e(asset('assets_v1/js/custom_datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>



    <script>
        <?php
            function translateMessage($value) {
                return t($value);
            }
        ?>

        $(document).on('click','.generateUserName',function () {
            let id = $(this).data('id')
            var numbers = [0, 1];
            var selected_number = numbers[Math.floor(Math.random() * numbers.length)];
            var name = $('input[name="student['+id+'][name]"]').val().toLowerCase().replace('  ', ' ').split(" ");
            var year = (new Date).getFullYear();
            var number = parseInt(Math.random() * 100);
            var username = null;
            if (name.length >= 2) {
                if (selected_number === 1) {
                    username = name[0] + name[1] + year + number + '@arabic-arabs.com';
                } else {
                    username = name[0] + year + number + '@arabic-arabs.com';
                }
            } else {
                username = name[0] + year + number + '@arabic-arabs.com';
            }
            $('input[name="student['+id+'][email]"]').val(username);
        });



        $(document).on('keyup','.remove_spaces',function () {
            let value = $(this).val()
            if (value){
                value = value.replace(/\u00A0/g, ' ').replace('  ', ' ').trim();
                $(this).val(value)
            }
        })
        $(document).ready(function () {

            //select2
            $('[data-control="select2"]').select2({
                allowClear: $(this).data('allow-clear') || false,
                placeholder: $(this).data('placeholder') || '',
            });

            $(document).on('click', '.save_rows', function () {
                var ele = $('.run-indicator');
                let form = $('#form-data');
                ele.attr("data-kt-indicator", "on");
                ele.attr('disabled', true);
                if (form.valid()) {
                    // Submit the form programmatically
                    form.submit()
                }else{
                    $('.save_rows').removeAttr('disabled');
                    $('.save_rows').removeAttr("data-kt-indicator");
                }
            });



        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/import/logs/show_errors.blade.php ENDPATH**/ ?>
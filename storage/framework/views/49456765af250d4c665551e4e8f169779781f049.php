<!--begin::Javascript-->
<script>var hostUrl = "assets_v1/";</script>

<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="<?php echo e(asset('assets_v1/plugins/global/plugins.bundle.js')); ?>"></script>
<script src="<?php echo e(asset('assets_v1/js/scripts.bundle.js')); ?>"></script>
<!--end::Global Javascript Bundle-->

<!--begin::Vendors Javascript(used for this page only)-->
<script src="<?php echo e(asset('assets_v1/plugins/custom/fullcalendar/fullcalendar.bundle.js')); ?>"></script>
<!--end::Vendors Javascript-->

<script src="<?php echo e(asset('assets_v1/plugins/custom/datatables/datatables.bundle.js')); ?>"></script>
<script src="<?php echo e(asset('assets_v1/js/helpers.js')); ?>?v=<?php echo e(time()); ?>"></script>
<script type="text/javascript">
    var getTeacherBySchoolURL= "<?php echo e(route(getGuard().'.getTeacherBySchool', ":id")); ?>"
    var getSectionBySchoolURL= "<?php echo e(route(getGuard().'.getSectionBySchool', ":id")); ?>"
    var getSectionByTeacherURL= "<?php echo e(route(getGuard().'.getSectionByTeacher', ":id")); ?>"
    var getLessonsByGradeURL= "<?php echo e(route(getGuard().'.getLessonsByGrade')); ?>"
    var getStoriesByGradeURL= "<?php echo e(route(getGuard().'.getStoriesByGrade')); ?>"
    var getStudentsByGradeURL= "<?php echo e(route(getGuard().'.getStudentsByGrade', ":id")); ?>"



    var DELETE_MESSAGE = "<?php echo e(t('Are sure of the deleting process ?')); ?>";
    var DELETE_SUB_MESSAGE = "<?php echo e(t('Deleting the current record is deleting related records')); ?>";
    var CONFIRM_TEXT = "<?php echo e(t('Yes')); ?>";
    var CANCEL_TEXT = "<?php echo e(t('No')); ?>";
    var TOAST_DIRECTION = "<?php echo e(app()->getLocale() == 'ar' ? 'toastr-top-left' : 'toastr-top-right'); ?>";
    var DatatableArabicURL = '<?php echo e(asset('assets_v1/datatable_arabic.json')); ?>'
    var RTL = "<?php echo e(app()->getLocale() == 'ar' ? true : false); ?>";
    var CSRF = $('meta[name="csrf-token"]').attr('content');

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": TOAST_DIRECTION,
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "100",
        "hideDuration": "2000",
        "timeOut": "10000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "rtl": RTL,
    };
    <?php if(Session::has('message')): ?>
    toastr.<?php echo e(Session::get('m-class') ? Session::get('m-class'):'success'); ?>("<?php echo e(Session::get('message')); ?>");
    <?php endif; ?>
</script>
<?php echo $__env->yieldContent('script'); ?>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/layout_parts/script.blade.php ENDPATH**/ ?>
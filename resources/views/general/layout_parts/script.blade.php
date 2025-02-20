<!--begin::Javascript-->
<script>var hostUrl = "assets_v1/";</script>

<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{asset('assets_v1/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('assets_v1/js/scripts.bundle.js')}}"></script>
<!--end::Global Javascript Bundle-->

<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{asset('assets_v1/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
<!--end::Vendors Javascript-->

<script src="{{asset('assets_v1/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script src="{{asset('assets_v1/js/helpers.js')}}?v={{time()}}"></script>
<script type="text/javascript">
    var getTeacherBySchoolURL= "{{route(getGuard().'.getTeacherBySchool', ":id")}}"
    var getSectionBySchoolURL= "{{route(getGuard().'.getSectionBySchool', ":id")}}"
    var getSectionByTeacherURL= "{{route(getGuard().'.getSectionByTeacher', ":id")}}"
    var getLessonsByGradeURL= "{{route(getGuard().'.getLessonsByGrade')}}"
    var getStoriesByGradeURL= "{{route(getGuard().'.getStoriesByGrade')}}"
    var getStudentsByGradeURL= "{{route(getGuard().'.getStudentsByGrade', ":id")}}"



    var DELETE_MESSAGE = "{{t('Are sure of the deleting process ?')}}";
    var DELETE_SUB_MESSAGE = "{{t('Deleting the current record is deleting related records')}}";
    var CONFIRM_TEXT = "{{t('Yes')}}";
    var CANCEL_TEXT = "{{t('No')}}";
    var TOAST_DIRECTION = "{{app()->getLocale() == 'ar' ? 'toastr-top-left' : 'toastr-top-right'}}";
    var DatatableArabicURL = '{{asset('assets_v1/datatable_arabic.json')}}'
    var RTL = "{{app()->getLocale() == 'ar' ? true : false}}";
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
    @if(Session::has('message'))
    toastr.{{Session::get('m-class') ? Session::get('m-class'):'success'}}("{{Session::get('message')}}");
    @endif
</script>
@yield('script')

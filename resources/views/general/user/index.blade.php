@extends('manager.layout.container')

@section('title',$title)


@section('actions')

    <div class="dropdown with-filter">
            @can('add users')
                <a href="{{route(getGuard().'.user.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
                    <i class="la la-plus"></i>
                    {{t('Add User')}}
                </a>
            @endcan

        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
         @if(!guardIs('teacher'))
                @can('export users')
                    <li><a class="dropdown-item" href="#!" onclick="excelExport('{{route(getGuard().'.user.export')}}')">{{t('Export')}}</a></li>
                    <li><a class="dropdown-item" href="#!" onclick="cardsExport(true)">{{t('Cards')}}</a></li>
                @endif


{{--                @can('usage report')--}}
{{--                    <li><a class="dropdown-item" href="#!" onclick="excelExport('{{route(getGuard().'.report.usage_report_excel')}}')">{{t('Export Usage Report')}}</a></li>--}}
{{--                @endcan--}}

                @can('assign users')
                    <li><a class="dropdown-item" href="#!" data-bs-toggle="modal" data-bs-target="#add_users_teachers">{{t('Assigned To Teacher')}}</a></li>
                @endcan
                @can('unassign users')
                    <li><a class="dropdown-item" href="#!" id="unassigned_users_teachers">{{t('Unsigned Teacher')}}</a></li>
                @endcan

                @can('users activation')
                    <li><a class="dropdown-item" href="#!" data-bs-toggle="modal"
                           data-bs-target="#users_activation_modal">{{t('Activation')}}</a></li>
                @endcan
                @can('update users grade')
                    <li><a class="dropdown-item" href="#!" data-bs-toggle="modal"
                           data-bs-target="#users_update_grades_modal">{{t('Update Grades')}}</a></li>
                @endcan

                @can('reset users passwords')
                    <li><a class="dropdown-item" href="#!" data-bs-toggle="modal"
                           data-bs-target="#reset_passwords_modal">{{t('Reset Passwords')}}</a></li>
                @endcan

                @can('delete users')
                    <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows">{{t('Delete')}}</a></li>
                @endif
            @else
                <li><a class="dropdown-item d-none cursor-pointer checked-visible" id="students_assign">{{t('Assign')}}</a></li>

            @endif



        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-1  mb-2">
            <label>{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
        </div>
        <div class="col-lg-2  mb-2">
            <label>{{t('Id Number')}}:</label>
            <input type="text" name="id_number" class="form-control direct-search" placeholder="{{t('Id Number')}}">
        </div>
        <div class="col-lg-3  mb-2">
            <label>{{t('Student Name')}}:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="{{t('Student Name')}}">
        </div>
        <div class="col-lg-3  mb-2">
            <label>{{t('Email')}}:</label>
            <input type="text" name="email" class="form-control direct-search" placeholder="{{t('Email')}}">
        </div>


        <div class="col-lg-3 mb-2">
            <label>{{t('Grade')}} :</label>
            <select name="grade_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                @endforeach
            </select>
        </div>

        @if(guardIs('manager'))
            <div class="col-3 mb-2">
                <label class="">{{t('School')}}:</label>
                <select class="form-select" name="school_id" data-control="select2" data-allow-clear="true"
                        data-placeholder="{{t('Select School')}}">
                    <option></option>
                    @foreach($schools as $school)
                        <option value="{{$school->id}}">{{$school->name}}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if(!guardIs('teacher'))
            <div class="col-3 mb-2">
                <label class="">{{t('Teacher')}}:</label>
                <select class="form-select" name="teacher_id" data-control="select2" data-allow-clear="true"
                        data-placeholder="{{t('Select Teacher')}}">
                    <option></option>
                    @isset($teachers)
                        @foreach($teachers as $teacher)
                            <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                        @endforeach
                    @endisset

                </select>
            </div>
        @endif

        <div class="col-3 mb-2">
            <label class="">{{t('Year')}}:</label>
            <select class="form-select" name="year_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Year')}}">
                <option></option>
                @foreach($years as $year)
                    <option value="{{$year->id}}">{{$year->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-2">
            <label class="">{{t('Package')}}:</label>
            <select class="form-select" name="package_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Package')}}">
                <option></option>
                @foreach($packages as $package)
                    <option value="{{$package->id}}">{{$package->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-2 mb-2">
            <label class="">{{t('Section')}}:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Section')}}">
                <option></option>
                @if(isset($sections))
                    @foreach($sections as $section)
                        <option value="{{$section}}">{{$section}}</option>
                    @endforeach
                @endif

            </select>
        </div>

        <div class="col-2 mb-2">
            <label class="">{{t('Gender')}}:</label>
            <select class="form-select" name="gender" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Gender')}}">
                <option></option>
                <option value="Boy">{{t('Boy')}}</option>
                <option value="Girl">{{'Girl'}}</option>
            </select>
        </div>

       @if(guardIs('manager'))
            <div class="col-2 mb-2">
                <label class="">{{t('Activation')}}:</label>
                <select class="form-select" name="status" data-control="select2" data-allow-clear="true"
                        data-placeholder="{{t('Select Status')}}">
                    <option></option>
                    <option value="active">{{t('Active')}}</option>
                    <option value="expire">{{'Expired'}}</option>
                </select>
            </div>
       @endif

        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>{{ t('Register Date') }}:</label>
            <input id="register_date" class="form-control " placeholder="{{t('Select Register Date')}}">
            <input type="hidden" id="start_register_date" name="start_register_date" value="">
            <input type="hidden" id="end_register_date" name="end_register_date" value="">
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>{{ t('Login Date') }}:</label>
            <input id="login_at" class="form-control " placeholder="{{t('Select Login Date')}}">
            <input type="hidden" id="start_login_at" name="start_login_at" value="">
            <input type="hidden" id="end_login_at" name="end_login_at" value="">
        </div>

        @if(guardIs('manager'))
            <div class="col-3 mb-2">
                <label class="mb-1">{{t('Students Status')}}:</label>
                <select class="form-control form-select reset-no" data-hide-search="true" data-control="select2" data-placeholder="{{t('Select Student Status')}}" name="deleted_at" id="students_status">
                    <option value="1" selected>{{t('Not Deleted Students')}}</option>
                    {{--                @can('show deleted students')--}}
                    <option value="2">{{t('Deleted Students')}}</option>
                    {{--                @endcan--}}
                </select>
            </div>
            <div class="col-3 mb-2">
                <label class="mb-1">{{t('Archived Status')}}:</label>
                <select class="form-control form-select reset-no" data-hide-search="true" data-control="select2" data-placeholder="{{t('Select Archived Status')}}" name="archived" id="archived">
                    <option value="2" selected>{{t('Not Archived Students')}}</option>
                    {{--                @can('show deleted students')--}}
                    <option value="1">{{t('Archived Students')}}</option>
                    {{--                @endcan--}}
                </select>
            </div>
        @endif


    </div>
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item">
        {{$title}}
    </li>
@endpush


@section('content')
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start">{{ t('Student') }}</th>
                <th class="text-start">{{guardIs('teacher')? t('Information'): t('School') }}</th>
                <th class="text-start">{{ t('Dates') }}</th>
                @if(!guardIs('teacher'))
                    <th class="text-start">{{ t('Actions') }}</th>
                @endif
            </tr>
            </thead>
        </table>
    </div>

    @can('assign users')
        <div class="modal fade" tabindex="-1" id="add_users_teachers">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">{{t('Users Teacher')}}</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                             aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body d-flex flex-column">
                        <form id="users_teacher_form">
                            @if(guardIs('manager'))
                                <div class="mb-2">
                                    <label>{{t('School')}} :</label>
                                    <select name="teacher_school_id" class="form-select" data-control="select2"
                                            data-placeholder="{{t('Select School')}}" data-allow-clear="true">
                                        <option></option>
                                        @foreach($schools as $school)
                                            <option value="{{$school->id}}">{{$school->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="mb-2">
                                <label>{{t('Teacher')}} :</label>
                                <select name="users_teacher_id" class="form-select" data-control="select2"
                                        data-placeholder="{{t('Select Teacher')}}" data-allow-clear="true">
                                    <option></option>
                                    @isset($teachers)
                                        @foreach($teachers as $teacher)
                                            <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>

                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{t('Close')}}</button>
                        <button type="button" class="btn btn-primary" id="btn_users_teacher">{{t('Save')}}</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('reset users passwords')
        <div class="modal fade" tabindex="-1" id="reset_passwords_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">{{t('Reset Users Passwords')}}</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                             aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body d-flex flex-column">
                        <form id="reset_passwords_form">
                            @csrf
                            <div class="mb-2">
                                <label>{{t('New Password')}} :</label>
                                <input class="form-control" type="text" name="password">
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{t('Close')}}</button>
                        <button type="button" class="btn btn-primary" id="btn_reset_passwords">{{t('Update')}}</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('update users grade')
        <div class="modal fade" tabindex="-1" id="users_update_grades_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">{{t('Users Grades')}}</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                             aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body d-flex flex-column">
                        <form id="users_grades_form">
                            <div class="mb-2">
                                <label>{{t('Move Grade')}} :</label>
                                <select name="move_grade" class="form-select" data-control="select2"
                                        data-placeholder="{{t('Select Grade')}}" data-allow-clear="true">
                                    <option></option>
                                    <option value="1">1</option>
                                    <option value="-1">-1</option>
                                    <option value="2">2</option>
                                    <option value="-2">-2</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>{{t('Grade')}} :</label>
                                <select name="grade" class="form-select" data-control="select2"
                                        data-placeholder="{{t('Select Grade')}}" data-allow-clear="true">
                                    <option></option>
                                    @foreach($grades as $grade)
                                        <option value="{{$grade->id}}">{{$grade->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>{{t('Alternative Grade')}} :</label>
                                <select name="alternate_grade" class="form-select" data-control="select2"
                                        data-placeholder="{{t('Select Alternative Grade')}}" data-allow-clear="true">
                                    <option></option>
                                    @foreach($grades as $grade)
                                        <option value="{{$grade->id}}">{{$grade->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>{{t('Assigned Year')}} :</label>
                                <select name="assigned_year_id" class="form-select" data-control="select2"
                                        data-placeholder="{{t('Select Year')}}" data-allow-clear="true">
                                    <option></option>
                                    @foreach($years as $year)
                                        <option value="{{$year->id}}">{{$year->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>{{t('Archived Status')}} :</label>
                                <select name="archived" class="form-select" data-control="select2"
                                        data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                                    <option></option>
                                    <option value="1">{{t('Archived')}}</option>
                                    <option value="2">{{t('Not Archived')}}</option>
                                </select>
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{t('Close')}}</button>
                        <button type="button" class="btn btn-primary" id="btn_users_update_grades">{{t('Save')}}</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('users activation')
        <div class="modal fade" tabindex="-1" id="users_activation_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">{{t('Users Activation')}}</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                             aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body d-flex flex-column">
                        <form id="activation_form">
                            <div class="mb-2">
                                <label class="mb-2">{{t('Activation Status')}} :</label>
                                <select name="activation_status" class="form-select" data-control="select2"
                                        data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                                    <option></option>
                                    <option value="1">{{t('Activate')}}</option>
                                    <option value="2">{{t('Deactivate')}}</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="mb-2">{{t('Active To')}} :</label>
                                <input class="form-control form-control-solid" id="active_to_date" name="active_to_date"
                                       value="" placeholder="{{t('Active to')}}"/>
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{t('Close')}}</button>
                        <button type="button" class="btn btn-primary" id="btn_users_activation">{{t('Save')}}</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan


@endsection


@section('script')
    <script>
        var ASSIGNED_STUDENTS = '{{t('Students Assign')}}',
            ASSIGNED_STUDENTS_MESSAGE = '{{t('Do you really want to assign students?')}}',
            UNSIGNED_TEACHER = '{{t('Unsigned Teacher')}}',
            UNSIGNED_TEACHER_MESSAGE = '{{t('Do you want to delete teacher for selected students')}}';



        var USER_ACTIVATION_URL = "{{route(getGuard().'.user.activation')}}";
        var USER_UPDATE_GRADES_URL = "{{route(getGuard().'.user.update_grades')}}";
        var ASSIGNED_TO_TEACHER_URL = "{{route(getGuard().'.user.assigned_to_teacher')}}";
        var STUDENT_CARD_URL = "{{route(getGuard().'.user.cards-export')}}";
        var UNSIGNED_TEACHER_URL = '{{route(getGuard().'.user.unassigned_user_teacher')}}';
        var RESET_PASSWORD_URL = '{{route(getGuard().'.user.reset-passwords')}}';
        var RESTORE_USERS_URL = '{{route(getGuard().'.user.restore',':id')}}';

        var DELETE_URL = "{{ route(getGuard().'.user.destroy') }}";
        var TABLE_URL = "{{ route(getGuard().'.user.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            {data: 'school', name: 'school'},
            {data: 'dates', name: 'dates'},
            @if(!guardIs('teacher'))
            {data: 'actions', name: 'actions'},
            @endif
        ];

        //restore students
        @can('restore deleted users')

        @endcan

        @can('reset users passwords')


        @endcan

    </script>

    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>
    <script src="{{asset('assets_v1/js/general/users.js')}}?v={{time()}}"></script>
    <script src="{{asset('assets_v1/js/custom.js')}}?v={{time()}}"></script>

    <script>
        getAndSetDataOnSelectChange('teacher_school_id','users_teacher_id',getTeacherBySchoolURL)
        getTeacherBySchool()
        getSectionBySchool()
        getSectionByTeacher()
    </script>

@endsection



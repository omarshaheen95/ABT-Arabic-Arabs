@extends('teacher.layout.container')

@section('title',$title)


@section('actions')
    @can('add users')
        <a href="{{route(getGuard().'.user.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            {{t('Add User')}}
        </a>
    @endcan
    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#!" onclick="cardsExport(true)">{{t('Cards')}}</a></li>
            <li><a class="dropdown-item" href="#!"
                   onclick="excelExport('{{route(getGuard().'.user.export')}}')">{{t('Export')}}</a></li>
            <li><a class="dropdown-item d-none checked-visible" href="#!" onclick="$('#update_learning_years_modal').modal('show')" >{{t('Update Learning Years')}}</a></li>
            <li><a class="dropdown-item text-danger d-none checked-visible" id="unassigned_users_teachers">{{t('Unsigned')}}</a></li>

            {{--            <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows">{{t('Delete')}}</a></li>--}}
        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-1  mb-2">
            <label>{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
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
        <div class="col-2 mb-2">
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
                @foreach(teacherSections(Auth::user()->id) as $section)
                    <option value="{{$section}}">{{$section}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-2 mb-2">
            <label class="">{{t('Gender')}}:</label>
            <select class="form-select" name="gender" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Gender')}}">
                <option></option>
                <option value="Boy">{{t('Boy')}}</option>
                <option value="Girl">{{t('Girl')}}</option>
            </select>
        </div>



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
        <div class="col-2 mb-2">
            <label class="">{{t('Activation')}}:</label>
            <select class="form-select" name="status" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Status')}}">
                <option></option>
                <option value="active">{{t('Active')}}</option>
                <option value="expire">{{'Expired'}}</option>
            </select>
        </div>

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
                <th class="text-start">{{ t('Information') }}</th>
                <th class="text-start">{{ t('Dates') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" tabindex="-1" id="update_learning_years_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{t('Update Learning Years')}}</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                    <div class="">
                        <label>{{t('Learning Years')}} :</label>
                        <select id="learning_years" class="form-select" data-control="select2" data-placeholder="{{t('Select Learning Year')}}" data-allow-clear="true">
                            @foreach(range(0,12) as $value)
                                <option value="{{ $value }}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{t('Close')}}</button>
                    <button type="button" class="btn btn-primary" id="update_learning_year" >{{t('Update')}}</button>
                </div>

            </div>
        </div>
    </div>
@endsection


@section('script')

    <script>
        var ASSIGNED_STUDENTS = '{{t('Students Assign')}}',
            ASSIGNED_STUDENTS_MESSAGE = '{{t('Do you really want to assign students?')}}',
            UNSIGNED_TEACHER = '{{t('Unsigned Teacher')}}',
            UNSIGNED_TEACHER_MESSAGE = '{{t('Do you want to delete teacher for selected students')}}';


        var DELETE_URL = '{{route(getGuard().'.user.destroy')}}';
        var TABLE_URL = "{{ route(getGuard().'.my-students') }}";
        var UPDATE_LEARNING_YEARS_URL = '{{route(getGuard().'.user.update_learning_years')}}';
        var UNSIGNED_TEACHER_URL = '{{route(getGuard().'.user.unassigned_user_teacher')}}';
        var STUDENT_CARD_URL = "{{route(getGuard().'.user.cards-export')}}";

        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            {data: 'school', name: 'school'},
            {data: 'dates', name: 'dates'},
            {data: 'actions', name: 'actions'},
        ];


    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>
    <script src="{{asset('assets_v1/js/general/users.js')}}?v={{time()}}"></script>

@endsection



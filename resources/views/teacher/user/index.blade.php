@extends('teacher.layout.container')

@section('title',$title)


@section('actions')

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item d-none cursor-pointer checked-visible" id="students_assign">{{t('Assign')}}</a></li>
        </ul>
    </div>

@endsection
@section('filter')
    <div class="row">
        <div class="col-1  mb-2">
            <label class="mb-2">{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
        </div>
        <div class="col-lg-2  mb-2">
            <label class="mb-2">{{t('Student Name')}}:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="{{t('Student Name')}}">
        </div>
        <div class="col-lg-3  mb-2">
            <label class="mb-2">{{t('Email')}}:</label>
            <input type="text" name="email" class="form-control direct-search" placeholder="{{t('Email')}}">
        </div>
        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Grade')}} :</label>
            <select name="grade_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @foreach($grades as $grade)
                    <option value="{{$grade->id}}">{{$grade->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Package')}}:</label>
            <select class="form-select" name="package_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Package')}}">
                <option></option>
                @foreach($packages as $package)
                    <option value="{{$package->id}}">{{$package->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Section')}}:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Section')}}">
                <option></option>
                @foreach(schoolSections(Auth::user()->school->id) as $section)
                    <option value="{{$section}}">{{$section}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Gender')}}:</label>
            <select class="form-select" name="gender" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Gender')}}">
                <option></option>
                <option value="Boy">{{t('Boy')}}</option>
                <option value="Girl">{{'Girl'}}</option>
            </select>
        </div>



        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2">{{ t('Register Date') }}:</label>
            <input id="register_date" class="form-control " placeholder="{{t('Select Register Date')}}">
            <input type="hidden" id="start_register_date" name="start_register_date" value="">
            <input type="hidden" id="end_register_date" name="end_register_date" value="">
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2">{{ t('Login Date') }}:</label>
            <input id="login_at" class="form-control " placeholder="{{t('Select Login Date')}}">
            <input type="hidden" id="start_login_at" name="start_login_at" value="">
            <input type="hidden" id="end_login_at" name="end_login_at" value="">
        </div>
        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Activation')}}:</label>
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
            </tr>
            </thead>
        </table>
    </div>

@endsection


@section('script')

    <script>
        var TABLE_URL = "{{ route('teacher.students_school.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            {data: 'school', name: 'school'},
            {data: 'dates', name: 'dates'},
        ];

        $('#students_assign').on('click',function () {
            showAlert('{{t('Students Assign')}}','{{t('Do you really want to assign students?')}}','warning','{{t('Yes')}}','{{t('No')}}',(callback)=>{
                if (callback){
                    showLoadingModal()
                    $.ajax({
                        url: "{{route('teacher.student.student_assign')}}",
                        type: 'POST',
                        data: {
                            '_token': '{{csrf_token()}}',
                            user_id: getSelectedRows(),
                        },
                        success: function (response) {
                            table.DataTable().draw(true);
                            toastr.success(response.message);
                            hideLoadingModal()
                        },
                        error: function (error) {
                            toastr.error(error.responseJSON.message);
                            hideLoadingModal()
                        }
                    });
                }
            })
        })
        initializeDateRangePicker('register_date')
        initializeDateRangePicker('login_at')
    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection



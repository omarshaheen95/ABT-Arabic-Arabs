@extends(getGuard().'.layout.container')

@section('title',$title)


@section('actions')

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#!"
                       onclick="excelExport('{{route(getGuard().'.teacher.tracking_export')}}')">{{t('Export')}}</a></li>

        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-1 mb-2">
            <label>{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
        </div>
        <div class="col-3 mb-2">
            <label>{{t('Name')}}:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="{{t('Name')}}">
        </div>

        <div class="col-3 mb-2">
            <label>{{t('Email')}}:</label>
            <input type="text" name="email" class="form-control direct-search" placeholder="{{t('Email')}}">
        </div>

        <div class="col-2 mb-2">
            <label>{{t('Mobile')}}:</label>
            <input type="text" name="mobile" class="form-control" placeholder="{{t('Mobile')}}">
        </div>


        @if(guardIs('manager'))
            <div class="col-lg-3 mb-2">
                <label>{{t('School')}} :</label>
                <select name="school_id" class="form-select" data-control="select2"
                        data-placeholder="{{t('Select School')}}" data-allow-clear="true">
                    <option></option>
                    @foreach($schools as $school)
                        <option value="{{$school->id}}">{{$school->name}}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-lg-2 mb-2">
            <label>{{t('Student Status')}} :</label>
            <select name="student_status" id="student_status" class="form-select" data-control="select2"
                    data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Has students')}}</option>
                <option value="2">{{t('Has no students')}}</option>
                <option value="3">{{t('Has active students')}}</option>
                <option value="4">{{t('Has inactive students')}}</option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label>{{t('Approval')}} :</label>
            <select name="approved" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}"
                    data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Approved')}}</option>
                <option value="2">{{t('Under review')}}</option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label>{{t('Activation')}} :</label>
            <select name="active" id="status" class="form-select" data-control="select2"
                    data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Active')}}</option>
                <option value="2">{{t('Non-Active')}}</option>
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
                <th class="text-start">{{ t('Teacher') }}</th>
                <th class="text-start">{{ t('Tests') }}</th>
                <th class="text-start">{{ t('Tasks') }}</th>
                <th class="text-start">{{ t('Last Login') }}</th>
                <th class="text-start">{{ t('Report') }}</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection


@section('script')

    <script>
        var TABLE_URL = "{{route(getGuard().'.teacher.tracking') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'teacher', name: 'teacher'},
            {data: 'tests', name: 'tests'},
            {data: 'tasks', name: 'tasks'},
            {data: 'last_login', name: 'last_login'},
            {data: 'actions', name: 'actions'}
        ];
    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection



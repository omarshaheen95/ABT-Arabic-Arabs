@extends(getGuard().'.layout.container')

@section('title',$title)

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
        <div class="col-4 mb-2">
            <div class="form-group">
                <label>{{t('Guard')}}:</label>
                <select name="user_guard" class="form-select" data-control="select2"
                        data-placeholder="{{t('Select Guard')}}" data-allow-clear="true">
                    <option></option>
                    @foreach(sysGuards() as $guard)
                        <option value="{{$guard}}" @if($guard=='manager') selected @endif>{{camelCaseText($guard)}}</option>
                    @endforeach
                </select>
            </div>

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
                <th class="text-start">{{ t('Name') }}</th>
                <th class="text-start">{{ t('Email') }}</th>
                <th class="text-start">{{ t('Guard') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>

@endsection


@section('script')

    <script>
        var TABLE_URL = "{{route(getGuard().'.user_role_and_permission.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'guard_name', name: 'guard_name'},
            {data: 'actions', name: 'actions'}
        ];

    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection



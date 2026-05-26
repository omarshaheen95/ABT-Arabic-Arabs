@extends(getGuard().'.layout.container')

@section('title',$title)


@section('actions')
    @can('add roles')
        <a href="{{route(getGuard().'.permission.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            {{t('Add Permission')}}
        </a>
    @endcan

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            @can('delete permissions')
                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!"
                       id="delete_rows">{{t('Delete')}}</a></li>
            @endcan

        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-1 mb-2">
            <label>{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control" placeholder="{{t('ID')}}">
        </div>
        <div class="col-3 mb-2">
            <label>{{t('Name')}}:</label>
            <input type="text" name="name" class="form-control" placeholder="{{t('Name')}}">
        </div>
        <div class="col-3 mb-2">
            <div class="form-group">
                <label>{{t('Guard')}}:</label>
                <select name="guard_name" class="form-select" data-control="select2"
                        data-placeholder="{{t('Select Guard')}}" data-allow-clear="true">
                    <option></option>
                    @foreach(sysGuards() as $guard)
                        <option value="{{$guard}}">{{camelCaseText($guard)}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-4 mb-2">
            <div class="form-group">
                <label>{{t('Group')}}:</label>
                <select name="group" class="form-select" data-control="select2"
                        data-placeholder="{{t('Select Group')}}" data-allow-clear="true">
                    <option></option>
                    @foreach($groups as $group)
                        <option value="{{$group}}">{{camelCaseText($group)}}</option>
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
                <th class="text-start">{{ t('Permission') }}</th>
                <th class="text-start">{{ t('Guard Name') }}</th>
                <th class="text-start">{{ t('Group') }}</th>
                <th class="text-start">{{ t('Roles Count') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>

@endsection


@section('script')

    <script>
        var DELETE_URL = "{{route(getGuard().'.permission.destroy') }}";
        var TABLE_URL = "{{route(getGuard().'.permission.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'guard_name', name: 'guard_name'},
            {data: 'group', name: 'group'},
            {data: 'roles_count', name: 'roles_count'},
            {data: 'actions', name: 'actions'}
        ];
    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection



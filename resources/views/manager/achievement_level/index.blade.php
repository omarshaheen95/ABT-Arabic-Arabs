@extends('manager.layout.container')

@section('title')
    {{t('Achievement Levels')}}
@endsection

@section('actions')
    @can('add achievement levels')
        <a href="{{route('manager.achievement_levels.create')}}" class="btn btn-primary font-weight-bolder">
            <i class="la la-plus"></i>{{t('Add Achievement Level')}}</a>
    @endcan

    <div class="dropdown" id="actions_dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            @can('delete achievement levels')
                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows">{{t('Delete')}}</a></li>
            @endcan
        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-lg-3 mb-2">
            <label>{{t('Name')}}:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="{{t('Name')}}">
        </div>
        <div class="col-lg-3 mb-2">
            <label>{{t('Required Points')}}:</label>
            <input type="number" name="required_points" class="form-control direct-search" placeholder="{{t('Required Points')}}">
        </div>
        <div class="col-lg-3 mb-2">
            <label>{{t('Description')}}:</label>
            <input type="text" name="description" class="form-control direct-search" placeholder="{{t('Description')}}">
        </div>
    </div>
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item">
        {{t('Achievement Levels')}}
    </li>
@endpush

@section('content')
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
                <tr class="fw-semibold fs-6 text-gray-800">
                    <th class="text-start"></th>
                    <th class="text-start">{{t('Name')}}</th>
                    <th class="text-start">{{t('Required Points')}}</th>
                    <th class="text-start">{{t('Badge Icon')}}</th>
                    <th class="text-start">{{t('Description')}}</th>
                    <th class="text-start">{{t('Actions')}}</th>
                </tr>
            </thead>
        </table>
    </div>

@endsection

@section('script')

    <script>
        var DELETE_URL = '{{ route("manager.achievement_levels.destroy")}}';
        var TABLE_URL = "{{route('manager.achievement_levels.index')}}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'required_points', name: 'required_points'},
            {data: 'badge_icon', name: 'badge_icon'},
            {data: 'description', name: 'description'},
            {data: 'actions', name: 'actions'}
        ];
    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection

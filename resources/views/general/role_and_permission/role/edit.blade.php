@extends(getGuard().'.layout.container')

@section('title')
    {{$title}}
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('style')
    <link href="{{ asset('assets_v1/lib/multiselect/multiselect-rtl.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <form
        action="{{ isset($role) ? route(getGuard().'.role.update', $role->id): route(getGuard().'.role.store') }}"
        method="post" class="form" id="form-data" enctype="multipart/form-data">
        @csrf
        @if(isset($role))
            @method('PATCH')
        @endif
        <div class="row">

            <div class="row">
                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label class="form-label">{{t('Role Name')}}</label>
                        <input type="text" name="name" class="form-control" placeholder="{{t('Name')}}"
                               value="{{ isset($role) ? $role->name : old("name") }}" required>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label class="form-label">{{t('Guard')}}</label>
                        <select name="guard_name" class="form-select" data-control="select2"
                                data-placeholder="{{t('Select Guard')}}" data-allow-clear="true" @if(isset($role) && $role->users->count()>0) disabled @endif>
                            <option></option>
                            @foreach(sysGuards() as $guard)
                                <option value="{{$guard}}" {{isset($role) && $guard == $role->guard_name ? 'selected':''}}>{{camelCaseText($guard)}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(isset($role) && $role->users->count()>0)
                        <input type="hidden" name="guard_name" value="{{$role->guard_name }}">
                    @endif
                </div>
                <div class="separator separator-content my-4"></div>
                <div class="col-12 row justify-content-center">
                    <div class="col-5">
                        <label>{{t('Permissions')}}</label>
                        <select name="from[]" id="multiselect" class="form-control" size="8" multiple="multiple">
                            @if(isset($permissions) && isset($role))
                                @foreach($permissions as $permission)
                                    <option value="{{$permission->id}}">{{$permission->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-2 d-flex flex-column justify-content-center align-items-center mt-5" >
                        <button type="button" id="multiselect_rightAll" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-forward"></i></button>
                        <button type="button" id="multiselect_rightSelected" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-chevron-right"></i></button>
                        <button type="button" id="multiselect_leftSelected" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-chevron-left"></i></button>
                        <button type="button" id="multiselect_leftAll" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-backward"></i></button>
                    </div>

                    <div class="col-5">
                        <label>{{t('Selected Permissions')}}</label>
                        <select name="permissions[]" id="multiselect_to"  class="form-control" size="8" multiple="multiple">
                            @if(isset($selected_permissions))
                                @foreach($selected_permissions as $permission)
                                    <option value="{{$permission->id}}">{{$permission->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary mr-2">{{isset($role)?t('Update'):t('Submit')}}</button>
                </div>
            </div>
        </div>

    </form>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}?v={{time()}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\General\RoleAndPermission\RoleRequest::class, '#form-data'); !!}
    <script type="text/javascript" src="{{ asset('assets_v1/lib/multiselect/multiselect.min.js')}}"></script>
    <script>
        let permissions = @json($permissions);
        //Filter permissions on guard name change by guard name
        $('select[name="guard_name"]').change(function () {
            let guard = $(this).val()
            let permissions_by_guard = [];
            let options = ``;
            permissions.forEach(function (item, index) {
                if (guard === item.guard_name){
                    permissions_by_guard.push(item)
                     options += `<option value="${item.id}">${item.name}</option>`

                }
            })
            $('select[name="from[]"]').html(options)
            $('select[name="permissions[]"]').html(null)
        })

        //Init multiselecet For Levels
        $('#multiselect').multiselect({
            search: {
                left: '<input type="text" name="q" class="form-control mb-2" placeholder="Search..." />',
                right: '<input type="text" name="q" class="form-control mb-2" placeholder="Search..." />',
            },
            sort:false
        });
    </script>
@endsection

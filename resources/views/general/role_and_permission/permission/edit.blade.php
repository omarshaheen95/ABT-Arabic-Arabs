@extends(getGuard().'.layout.container')

@section('title')
    {{$title}}
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush

@section('content')
    <form
        action="{{ isset($permission) ? route(getGuard().'.permission.update', $permission->id): route(getGuard().'.permission.store') }}"
        method="post" class="form" id="form-data">
        @csrf
        @if(isset($permission))
            @method('PATCH')
        @endif
        <div class="row">

            <div class="row">
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">{{t('Name')}}</label>
                        <input type="text" name="name" class="form-control" placeholder="{{t('Name')}}"
                               value="{{ isset($permission) ? $permission->name : old("name") }}" required>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">{{t('Group')}}</label>
                        <input type="text" name="group" class="form-control" placeholder="{{t('Group')}}"
                               value="{{ isset($permission) ? $permission->group : old("group") }}" required>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">{{t('Guard')}}</label>
                        <select name="guard_name" class="form-select" data-control="select2"
                                data-placeholder="{{t('Select Guard')}}" data-allow-clear="true" @if(isset($permission)  && $permission->roles->count()>0) disabled @endisset>
                            <option></option>
                            @foreach(sysGuards() as $guard)
                                <option value="{{$guard}}" {{isset($permission) && $guard == $permission->guard_name ? 'selected':''}}>{{camelCaseText($guard)}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(isset($permission) && $permission->roles->count()>0)
                        <input type="hidden" name="guard_name" value="{{$permission->guard_name }}">
                    @endif
                </div>
                <div class="separator separator-content my-4"></div>
                <div class="col-12 row justify-content-center">
                    <div class="col-5">
                        <label>{{t('Roles')}}</label>
                        <select name="from[]" id="multiselect" class="form-control" size="8" multiple="multiple">
                            @if(isset($roles))
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
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
                        <label>{{t('Selected Roles')}}</label>
                        <select name="roles[]" id="multiselect_to" class="form-control" size="8" multiple="multiple">
                            @if(isset($selected_roles))
                                @foreach($selected_roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
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
                            class="btn btn-primary mr-2">{{isset($permission)?t('Update'):t('Submit')}}</button>
                </div>
            </div>
        </div>

    </form>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}?v={{time()}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\General\RoleAndPermission\PermissionRequest::class, '#form-data'); !!}
    <script type="text/javascript" src="{{ asset('assets_v1/lib/multiselect/multiselect.min.js')}}"></script>
    <script>
        let roles = @json($roles);
        //Filter permissions on guard name change by guard name
        $('select[name="guard_name"]').change(function () {
            let guard = $(this).val()
            let options = ``;
            roles.forEach(function (item, index) {
                if (guard === item.guard_name){
                    options += `<option value="${item.id}">${item.name}</option>`

                }
            })
            $('select[name="from[]"]').html(options)
            $('select[name="roles[]"]').html(null)
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

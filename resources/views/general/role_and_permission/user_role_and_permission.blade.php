@extends(getGuard().'.layout.container')
@section('title',$title)

@push('breadcrumb')
    <li class="breadcrumb-item text-muted">
        <a href="{{$index_route}}" class="text-muted">
            {{t(camelCaseText($user_guard))}}
        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        {{t('Manage Roles & Permission')}}
    </li>
@endpush

@section('actions')
    <a onclick="selectAll()" class="btn btn-primary font-weight-bolder">
        <i class="la la-check-circle"></i>{{t('Select All Permissions')}}</a>
    <button onclick="$('#form_data').submit()" class="btn btn-warning font-weight-bolder">
        <i class="la la-check-circle"></i>{{t('Submit')}}</button>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Card-->
            <div class="card m-0 p-0">
                <!--begin::Form-->
                <form class="form" id="form_data"
                      action="{{$update_route}}"
                      method="post">
                     @csrf

                    <input name="guard_id" type="hidden" value="{{$guard_id}}">
                    <input name="user_guard" type="hidden" value="{{$user_guard}}">

                    <div class="card-body m-0 p-0">

                        <h4>
                            {{t('Roles')}}
                        </h4>

                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <select name="roles[]" class="form-select" data-control="select2"
                                        data-placeholder="{{t('Select Roles')}}" data-allow-clear="true" multiple>
                                    <option></option>
                                    @isset($roles)
                                        @foreach($roles as $role)
                                            <option value="{{$role->name}}" {{isset($user_roles)  && $user_roles->whereIn('role_id',$role->id)->first()? 'selected':''}}>{{camelCaseText($role->name)}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            @if(isset($permission) && $permission->roles->count()>0)
                                <input type="hidden" name="guard_name" value="{{$permission->guard_name }}">
                            @endif
                        </div>

                        <div class="separator content-separator my-5"></div>
                        <h2 class="mb-4">
                            {{t('Direct Permissions')}}
                        </h2>
                        <div class="form-group row">
                            @foreach($permissions as $key=>$values)
                                <div class="col-12 d-flex flex-column">
                                    @php
                                     $permissions_ids = $values->pluck('id')->toArray();
                                     $guard_permissions_ids = collect($user_permissions)->pluck('permission_id')->toArray();
                                     $c_status = false;
                                     foreach ($permissions_ids as $value) {
                                        if (in_array($value, $guard_permissions_ids)) {
                                            $c_status= true;
                                        } else {
                                            $c_status= false;
                                            break;
                                        }
                                    }
                                    @endphp
                                    <div class="d-flex my-5 align-items-center">
                                        <h4 class="m-0">{{t(camelCaseText($key))}}</h4>
                                        <div class="form-check form-check-custom form-check-solid form-check-sm ms-2">
                                            <input id="{{$key}}_checkbox" class="form-check-input" type="checkbox" value=""  {{$c_status?'checked':''}}
                                            onclick="checkAllPermissions('{{$key}}_checkbox','{{'class_'.$key}}')"  {{$c_status?'checked':''}}/>
                                        </div>
                                    </div>
                                <div class="card bg-gray-100">
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($values as $permission)
                                                @php
                                                $status =
                                                collect($user_permissions)->where('permission_id',$permission->id)->first();
                                                @endphp
                                                <div class="col-3 form-check form-check-custom form-check-solid form-check-sm p-2">
                                                    <input class="form-check-input {{'class_'.$key}}" type="checkbox" name="permissions[]" value="{{$permission->name}}" id="flexRadioLg" {{$status?'checked':''}}/>
                                                    <label class="form-check-label text-dark" for="flexRadioLg">
                                                        {{t(camelCaseText($permission->name))}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="card-footer mt-4">
                        <div class="row">
                            <div class="col-lg-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mr-2">{{t('Submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>

@endsection
@section('script')
    <script>
        function checkAllPermissions(input_id,m_class) {
            let is_checked = $('#'+input_id).is(':checked')
            $('.'+m_class).each((index,item)=>{
                if (is_checked){ //when checked
                    $(item).prop('checked',true)

                }else {//when not checked
                    $(item).prop('checked',false)
                }
            })
        }
        function selectAll(){
            $('#form_data input:checkbox').prop('checked',true);
        }
    </script>
@endsection

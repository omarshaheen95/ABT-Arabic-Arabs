<div class="row col-12">
    <input type="hidden" name="student[{{$row->id}}][row_num]" value="{{$row->row_num}}">
    @foreach ($inputs_with_values as $input)
        @if($input['key'] == 'Arab' || $input['key'] == 'Citizen' || $input['key'] == 'SEN' || $input['key'] == 'G&T'|| $input['key'] == 'Active')
            <div class="col-sm-6 col-md-4">
                <label class="text-info">{{$input['key']}}</label>
                <select name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                        data-name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                        class="form-control from-input-data form-select"
                        data-control="select2"
                        data-placeholder="{{t('Select '.$input['key'])}}">
                    <option value="" disabled selected>{{t('Select Year')}}</option>
                    <option value="1" {{$input['value'] == 1 ? 'selected':''}}>{{t('Yes')}}</option>
                    <option value="0" {{$input['value'] == 0 && $input['value'] != null ? 'selected':''}}>{{t('No')}}</option>
                </select>
            </div>
        @elseif($input['key'] == 'Gender')
            <div class="col-sm-6 col-md-4">
                <label class="text-info">{{$input['key']}}</label>
                <select name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                        data-name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                        class="form-control from-input-data form-select"
                        data-control="select2"
                        data-placeholder="{{t('Select '.$input['key'])}}">
                    <option value="" disabled selected>{{t('Select Year')}}</option>
                    <option value="Boy" {{$input['value'] == 'Boy' ? 'selected':''}}>{{t('Boy')}}</option>
                    <option value="Girl" {{$input['value'] == 'Girl' ? 'selected':''}}>{{t('Girl')}}</option>
                </select>
            </div>
        @elseif($input['key'] == 'Teacher')
            <div class="col-sm-6 col-md-4">
                <label class="text-info">{{$input['key']}}</label>
                <select name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                        data-name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                        class="form-control from-input-data form-select"
                        data-control="select2"
                        data-placeholder="{{t('Select '.$input['key'])}}">
                   <option></option>
                    @if(isset($teachers))
                        @foreach($teachers as $teacher)
                            <option value="{{$teacher->id}}" @if(isset($input['value']) && $teacher->email==$input['value']) selected @endif>{{$teacher->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        @elseif($input['key'] == 'Name' )
            <div class="col-sm-6 col-md-4">
                <div class="form-group">
                    <label class="text-info">{{t('Name')}}</label>
                    <input type="text"
                           name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                           data-name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                           class="form-control name remove_spaces from-input-data" placeholder="{{t('Name')}}"
                           value="{{$input['value']}}" required>
                </div>
            </div>

        @elseif($input['key'] == 'Grade' || $input['key'] == 'Alternative Grade')
            <div class="col-sm-6 col-md-4">
                <label class="text-info">{{$input['key']}}</label>
                <select name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                        data-name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                        class="form-control from-input-data form-select"
                        data-control="select2"
                        data-placeholder="{{t('Select '.$input['key'])}}">
                    <option></option>
                        @if(isset($grades))
                        @foreach($grades as $grade)
                            <option value="{{$grade->id}}" @if(isset($input['value']) && $input['value']==$grade->id) selected @endif>{{$grade->name}}</option>
                        @endforeach
                        @endif
                </select>
            </div>

            @elseif($input['key'] == 'Email' )
                <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                        <label class="text-info">{{$input['key']}}</label>
                        <div class="input-group mb-5">
                            <input dir="ltr"
                                   name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                                   data-name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                                   type="text"
                                   placeholder="{{t($input['key'])}}"
                                   value="{{$input['value']}}"
                                   class="form-control username from-input-data" aria-describedby="basic-addon1"/>
                            <span class="input-group-text" id="basic-addon1">
                     <a class="p-0 cursor-pointer generateUserName" data-id="{{$row->id}}"><i class="fas fa-refresh"></i></a>
                 </span>
                        </div>
                    </div>
                </div>
        @elseif($input['key'] == 'Password' )
            <div class="col-sm-6 col-md-4">
                <label class="text-info">{{$input['key']}}</label>
                <input required name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                       data-name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                       type="text" value="123456"
                       class="form-control from-input-data from-input-data">
            </div>
        @else
            <div class="col-sm-6 col-md-4">
                <label class="text-info">{{$input['key']}}</label>
                <input required name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                       data-name="student[{{$row->id}}][{{str_replace(' ', '_', strtolower($input['key']))}}]"
                       type="text" value="{{$input['value']}}"
                       class="form-control from-input-data from-input-data">
            </div>
        @endif

    @endforeach
</div>

@extends(getGuard().'.layout.container')

@section('title',$title)


@section('actions')
    @can('add story assignments')
        <a href="{{route(getGuard().'.story_assignment.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            {{t('Add Assignment')}}
        </a>
    @endcan

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            @can('export story assignments')
                <li><a class="dropdown-item" href="#!"
                       onclick="excelExport('{{route(getGuard().'.story_assignment.export')}}')">{{t('Export')}}</a></li>
            @endcan
            @can('delete story assignments')
                    <li><a class="dropdown-item text-danger d-none checked-visible delete_assignment" href="#!">{{t('Delete')}}</a></li>
            @endcan

        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">

        @if(guardIs('manager'))
            <div class="col-3 mb-2">
                <label class="">{{t('School')}}:</label>
                <select class="form-select" name="school_id" data-control="select2" data-allow-clear="true"
                        data-placeholder="{{t('Select School')}}">
                    <option></option>
                    @foreach($schools as $school)
                        <option value="{{$school->id}}">{{$school->name}}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if(!guardIs('teacher'))
            <div class="col-3 mb-2">
                <label class="">{{t('Teacher')}}:</label>
                <select class="form-select" name="teacher_id" data-control="select2" data-allow-clear="true"
                        data-placeholder="{{t('Select Teacher')}}">
                    <option></option>
                    @isset($teachers)
                        @foreach($teachers as $teacher)
                            <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                        @endforeach
                    @endisset

                </select>
            </div>
        @endif

        <div class="col-3 mb-2">
            <label class="">{{t('Section')}}:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Section')}}">
                <option></option>
                @if(isset($sections))
                    @foreach($sections as $section)
                        <option value="{{$section}}">{{$section}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-3 mb-2">
            <label>{{t('Students Grade')}} :</label>
            <select name="students_grade" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-2 mb-2">
            <label>{{t('Story Grade').' ('.t('Level').')'}} :</label>
            <select name="story_grade" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @foreach(storyGradesSys() as $key => $grade)
                    <option value="{{$key}}">{{$grade}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-3 mb-2">
            <label>{{t('Story')}} :</label>
            <select  name="story_id" id="story_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Story')}}" data-allow-clear="true">
                <option></option>
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
                <th class="text-start">{{ t('School') }}</th>
                <th class="text-start">{{ t('Grades') }}</th>
                <th class="text-start">{{ t('Story') }}</th>
                <th class="text-start">{{ t('Date') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" tabindex="-1" id="delete_modal">
        <div class="modal-dialog">
            <form id="delete_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">{{t('Delete Story Assignment')}}</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                             aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <p style="font-weight: normal;font-size: 18px">{{t('Do you want to delete this assignment ?')}}</p>
                        <div class="col-lg-12  d-flex align-items-center p-0">
                            <p class="m-0 p-0"
                               style="font-weight: normal;font-size: 14px">{{t('Delete users story assignments when delete assignment')}}</p>
                            <div class="form-check form-check-custom form-check-solid mx-2">
                                <input id="with_user_assignments" class="form-check-input" type="checkbox" value="1"
                                       name="with_user_assignments"/>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="btn_close" type="button" class="btn btn-light"
                                data-bs-dismiss="modal">{{t('Close')}}</button>
                        <button type="button" class="btn btn-danger" id="btn_delete_assignment">{{t('Delete')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection


@section('script')
    <script src="{{asset('assets_v1/js/custom.js')}}?v={{time()}}"></script>

<script>

        var DELETE_URL = "{{ route(getGuard().'.story_assignment.destroy') }}";
        var TABLE_URL = "{{ route(getGuard().'.story_assignment.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'school', name: 'school'},
            {data: 'level', name: 'level'},
            {data: 'stories', name: 'stories'},
            {data: 'dates', name: 'dates'},
            {data: 'actions', name: 'actions'},
        ];

        initializeDateRangePicker();

        getTeacherBySchool()
        getSectionByTeacher()
        getAndSetDataOnSelectChange('story_grade','story_id',getStoriesByGradeURL)

        //delete lesson assignment------------------------------------------
        let row_id = null;
        $(document).on('click','.delete_assignment',function (e) {
            e.preventDefault()
            if (typeof $(this).attr('data-id') !== 'undefined'){
                row_id = [$(this).attr('data-id')]
            }
            $('#delete_modal').modal('show')
        })
        $(document).on('click','#btn_delete_assignment',function (e) {
            e.preventDefault()
            let formData = getFormData('delete_form')
            let data = getFilterData()
            $.each(formData, function (key, val) {
                data[key] = val;
            });
            if (row_id){
                data['row_id'] = row_id
            }
            $('#delete_modal').modal('hide')
            showLoadingModal()
            $.ajax({
                type: "DELETE",
                url: DELETE_URL,
                data: data,
                success:function (result) {
                    hideLoadingModal()
                    toastr.success(result.message)
                    table.DataTable().draw(false);
                },
                error:function (error) {
                    hideLoadingModal()
                    toastr.error(error.responseJSON.message)
                }
            })
            row_id = null;
            resetForm('delete_form')
        })
        //*------------------------------------------------------------------


    </script>

    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection



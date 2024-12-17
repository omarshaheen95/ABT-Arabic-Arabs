@extends(getGuard().'.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ route(getGuard().'.motivational_certificates.store') }}" method="post">
        @csrf

        <div class="row">

            <input type="hidden" name="model_type" value="{{$cer_type}}">
            @if(guardIs('manager'))
                <div class="form-group col-6 mb-2">
                    <label class="form-label">{{ t('School') }}</label>
                    <select class="form-select" data-control="select2"
                            data-placeholder="{{t('Select School')}}"
                            data-allow-clear="true" name="school_id" id="school_id">
                        <option></option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{$school->name}}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if(guardIn(['manager','school']))
                <div class="form-group col-6 mb-2">
                    <label class="form-label">{{ t('Teacher') }}</label>
                    <select class="form-select" data-control="select2"
                            data-placeholder="{{t('Select Teacher')}}"
                            data-allow-clear="true" name="teacher_id" id="teacher_id">
                        <option></option>
                        @isset($teachers)
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{isset($assignment) && $assignment->teacher_id == $teacher->id?'selected':''}}>{{$teacher->name}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
            @else
                <input type="hidden" id="teacher_id" value="{{\Illuminate\Support\Facades\Auth::guard('teacher')->id()}}">
            @endif

            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Section') }}</label>
                <select class="form-select" name="section[]" id="section"
                        data-control="select2" data-placeholder="{{t('Select Section')}}" data-allow-clear="true"
                        multiple>
                    <option value="all">{{t('All')}}</option>
                    @isset($sections)
                        @foreach($sections as $section)
                            <option value="{{ $section }}"
                                {{isset($assignment) && $assignment->sections && in_array($section,$assignment->sections)?'selected':''}}>{{$section}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>

            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Student Grade') }}</label>
                <select class="form-select" data-control="select2"
                        data-placeholder="{{t('Select Grade')}}"
                        data-allow-clear="true" name="grade_id" id="grade_id">
                    <option></option>
                    @foreach($grades as $grade)
                        <option value="{{$grade->id}}">{{$grade->name}}</option>
                    @endforeach
                </select>
            </div>
            @if($cer_type == 'lesson')
                <div class="form-group col-6 mb-2">
                    <label class="form-label">{{ t('Lesson') }}</label>
                    <select class="form-select assignment_lesson" data-control="select2" multiple
                            data-placeholder="{{t('Select Lesson')}}"
                            data-allow-clear="true"
                            name="lesson_id[]" id="assignment_lesson">
                        <option></option>
                    </select>
                </div>
            @else
                <div class="form-group col-6 mb-2">
                    <label class="form-label">{{ t('Grade') }}</label>
                    <select class="form-select" data-control="select2"
                            data-placeholder="{{t('Select Grade')}}"
                            data-allow-clear="true" name="grade" id="grade">
                        <option></option>
                        @foreach(storyGradesSys() as $key => $grade)
                            <option value="{{ $key }}">{{$grade}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-6 mb-2">
                    <label class="form-label">{{ t('Story') }}</label>
                    <select class="form-select " data-control="select2" multiple
                            data-placeholder="{{t('Select Story')}}"
                            data-allow-clear="true"
                            name="story_id[]" id="">
                        <option></option>
                    </select>
                </div>
            @endif


            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Granted In') }}</label>
                <input type="text" name="granted_in" class="form-control date" placeholder="{{t('Granted In')}}">
            </div>


            <div class="form-group col-12 mb-2">
                <label class="form-label">{{ t('Students') }}</label>
                <select class="form-control assignment_students" data-control="select2"
                        data-placeholder="{{t('Select Students')}}"
                        data-allow-clear="true" name="students[]" id="assignment_students" multiple>
                    <option value="">{{t('All')}}</option>

                </select>
            </div>



            <div class="separator my-4"></div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary">{{ t('Save') }}</button>&nbsp;
            </div>
        </div>
    </form>

@endsection

@section('script')
    <script src="{{asset('assets_v1/js/custom.js')}}?v={{time()}}"></script>

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}?v={{time()}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\General\MotivationalCertificateRequest::class, '#form_information'); !!}
    <script>


        $('input[name="granted_in"]').flatpickr();

        @if($cer_type == 'lesson')
        getAndSetDataOnSelectChange('grade_id', 'lesson_id', getLessonsByGradeURL, 1, [], function (callback) {
            getStudentsData()
        })

        @else
        $('select[name="grade_id"]').change(function () {
            getStudentsData()
        })

        getStoriesByGrade()

        @endif

        onSelectAllClick('assignment_students')
        onSelectAllClick('section')

        function getStudentsData() {
            let grade = $('select[name="grade_id"]').val();
            let teacher = $('#teacher_id').val();
            if (teacher && grade) {
                var students_url = '{{ route(getGuard().".getStudentsByGrade", ":id") }}';
                students_url = students_url.replace(':id', grade);
                $.ajax({
                    type: "get",
                    url: students_url,
                    data: {
                        teacher_id: teacher,
                        section:$('#section').val()
                    }
                }).done(function (student_data) {
                    $('select[name="students[]"]').html(student_data.html);
                    $('select[name="students[]"]').trigger('change');
                });
            }
        }


        getAndSetDataOnSelectChange('school_id','teacher_id',getTeacherBySchoolURL,1,[],function (callback) {
            getStudentsData()
        })

        getAndSetDataOnSelectChange('teacher_id','section[]',getSectionByTeacherURL,1,[],function (callback) {
            getStudentsData()
        })
        $('select[name="section[]"]').change(function () {
            getStudentsData();
        });

    </script>

@endsection

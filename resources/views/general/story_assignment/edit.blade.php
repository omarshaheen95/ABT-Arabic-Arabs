@extends(getGuard().'.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ isset($assignment)?route(getGuard().'.story_assignment.update',$assignment->id):route(getGuard().'.story_assignment.store') }}" method="post">
        @csrf
        @isset($assignment)
            @method('PATCH')
        @endisset

        <div class="row">
            @if(guardIs('manager'))
                <div class="form-group col-6 mb-2">
                    <label class="form-label">{{ t('School') }}</label>
                    <select class="form-select" data-control="select2"
                            data-placeholder="{{t('Select School')}}"
                            data-allow-clear="true" name="school_id" id="school_id">
                        <option></option>
                        @isset($schools)
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}"
                                    {{isset($assignment) && $assignment->teacher->school_id == $school->id?'selected':''}}>{{$school->name}}</option>
                            @endforeach
                        @endisset

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
                    <input type="hidden" name="teacher_id"  id="teacher_id" value="{{auth()->id()}}">
            @endif

            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Students Grade') }}</label>
                <select class="form-select students_grade" data-control="select2"
                        data-placeholder="{{t('Select grade')}}"
                        data-allow-clear="true" name="students_grade" id="students_grade">
                    <option></option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}" {{isset($assignment) && $assignment->students_grade == $grade->id?'selected':''}}>{{ $grade->name }}</option>
                    @endforeach
                </select>
            </div>

             <div class="form-group col-6 mb-2">
                    <label class="form-label">{{ t('Section') }}</label>
                    <select class="form-select" name="sections[]" id="section"
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

                <div class="form-group col-12 mb-2">
                    <label class="form-label">{{ t('Students') }}</label>
                    <select class="form-control assignment_students" data-control="select2"
                            data-placeholder="{{t('Select Students')}}"
                            data-allow-clear="true" name="students[]" id="assignment_students" multiple>
                        @isset($students)
                            @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                    {{isset($assignment) &&  in_array($student->id,$assignment->students_ids)?'selected':''}}>{{$student->name}}</option>
                            @endforeach
                        @endisset

                    </select>
                </div>

            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Story Grade') }}</label>
                <select class="form-select assignment_grade" data-control="select2"
                        data-placeholder="{{t('Select grade')}}"
                        data-allow-clear="true" name="story_grade" id="assignment_grade" @isset($assignment) disabled @endisset>
                    <option></option>
                    @foreach(storyGradesSys() as $key => $grade)
                        <option value="{{ $key }}" {{isset($assignment) && $assignment->story_grade == $key?'selected':''}}>{{ $grade }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Story') }}</label>
                <select class="form-select " data-control="select2" multiple
                        data-placeholder="{{t('Select Story')}}"
                        data-allow-clear="true"
                        name="stories_ids[]" id="" @isset($assignment) disabled @endisset>
                    <option></option>
                    @isset($stories)
                        @foreach($stories as $story)
                            <option value="{{ $story->id }}"
                                {{isset($assignment) && $assignment->stories_ids && in_array($story->id,$assignment->stories_ids)?'selected':''}}>{{$story->name}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>


                <div class="form-group col-6 mb-2">
                    <label class="form-label">{{ t('Deadline') }}</label>
                    <input type="text" name="deadline" class="form-control date" placeholder="{{t('DeadLine')}}" @isset($assignment) value="{{$assignment->deadline}}" @endisset>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">{{ t('Exclude students who have completed the assignment before') }}:</label>
                    <div class="d-flex gap-2 border-secondary" style="border: 1px solid;border-radius: 5px;padding: 9px">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" value="1" name="exclude_students" {{isset($assignment) &&  $assignment->exclude_students?'checked':''}}/>
                            <label class="form-check-label" for="flexRadioDefault">
                                {{t('Yes')}}
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" value="2" name="exclude_students"
                                   @if(isset($assignment))
                                       {{!$assignment->exclude_students?'checked':''}}
                                   @else
                                       checked
                                @endif
                            />
                            <label class="form-check-label" for="flexRadioDefault">
                                {{t('No')}}
                            </label>
                        </div>
                    </div>
                </div>

            <div class="separator my-4"></div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary">{{ t('Save') }}</button>&nbsp;
            </div>
        </div>
    </form>

@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}?v={{time()}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\General\StoryAssignmentRequest::class, '#form_information'); !!}
    <script src="{{asset('assets_v1/js/custom.js')}}?v={{time()}}"></script>

    <script>

        onSelectAllClick('section')

        onSelectAllClick('assignment_students')

        $('input[name="deadline"]').flatpickr();

        function getStudentsData() {
            let teacher_id = $('#teacher_id').val()
            let grade_id = $('select[name="students_grade"]').val()

            if (teacher_id && grade_id) {
                var students_url = '{{ route(getGuard().".getStudentsByGrade", ":id") }}';
                students_url = students_url.replace(':id', grade_id);
                $.ajax({
                    type: "get",
                    url: students_url,
                    data: {
                        teacher_id: teacher_id,
                        section: $('select[name="sections[]"]').val(),
                    }
                }).done(function (student_data) {
                    $('select[name="students[]"]').html(student_data.html);
                });
            }

        }

        getTeacherBySchool()

        getSectionBySchool()

       // getSectionByTeacher()

        getAndSetDataOnSelectChange('teacher_id','sections[]',getSectionByTeacherURL,1,[],function () {
            getStudentsData()
        })


        getAndSetDataOnSelectChange('story_grade','stories_ids[]',getStoriesByGradeURL,1,[],function () {
            getStudentsData()
        })


        $('select[name="sections[]"]').change(function () {
            getStudentsData();
        });



        $('select[name="students_grade"]').change(function () {
            getStudentsData();
        });









    </script>

@endsection

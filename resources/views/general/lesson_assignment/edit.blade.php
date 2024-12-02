@extends(getGuard().'.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ isset($assignment)?route(getGuard().'.lesson_assignment.update',$assignment->id):route(getGuard().'.lesson_assignment.store') }}" method="post">
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
                <label class="form-label">{{ t('Grade') }}</label>
                <select class="form-select assignment_grade" data-control="select2"
                        data-placeholder="{{t('Select grade')}}"
                        data-allow-clear="true" name="grade_id" id="assignment_grade" @isset($assignment) disabled @endisset>
                    <option></option>
                    @foreach($grades as  $grade)
                        <option value="{{ $grade->id }}"
                            {{isset($assignment) && $assignment->grade_id == $grade->id?'selected':''}}>{{ $grade->name }}</option>
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
                <label class="form-label">{{ t('Lesson') }}</label>
                <select class="form-select assignment_lesson" data-control="select2" multiple
                        data-placeholder="{{t('Select Lesson')}}"
                        data-allow-clear="true"
                        name="lessons_ids[]" id="assignment_lesson" @isset($assignment) disabled @endisset>
                    <option></option>
                    @isset($lessons)
                        @foreach($lessons as $lesson)
                            <option value="{{ $lesson->id }}"
                                {{isset($assignment) && $assignment->lessons_ids && in_array($lesson->id,$assignment->lessons_ids)?'selected':''}}>{{$lesson->name}}</option>
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
            <div class="d-flex align-items-center col-6 pt-4 gap-3">

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="test_assignment" {{isset($assignment) &&  $assignment->test_assignment?'checked':''}}/>
                    <label class="form-check-label text-dark">
                        {{ t('Test assignment') }}
                    </label>
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
    {!! JsValidator::formRequest(\App\Http\Requests\General\LessonAssignmentRequest::class, '#form_information'); !!}
    <script src="{{asset('assets_v1/js/custom.js')}}?v={{time()}}"></script>

    <script>

        onSelectAllClick('section')

        $('input[name="deadline"]').flatpickr();

        onSelectAllClick('assignment_students')

        function getStudentsData() {
            let teacher_id = $('select[name="teacher_id"]').val()
            let grade_id = $('select[name="grade_id"]').val()
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

        getAndSetDataOnSelectChange('grade_id','lessons_ids[]',getLessonsByGradeURL,1,function () {
            getStudentsData()
        })
        getAndSetDataOnSelectChange('teacher_id','sections[]',getSectionByTeacherURL,1,function () {
            getStudentsData()
        })


        $('select[name="sections[]"]').change(function () {
            getStudentsData();
        });
    </script>

@endsection

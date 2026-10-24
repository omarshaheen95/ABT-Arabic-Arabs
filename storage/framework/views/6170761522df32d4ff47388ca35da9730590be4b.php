<?php $__env->startSection('title',$title); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <form enctype="multipart/form-data" id="form_information"
          action="<?php echo e(isset($assignment)?route(getGuard().'.story_assignment.update',$assignment->id):route(getGuard().'.story_assignment.store')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <?php if(isset($assignment)): ?>
            <?php echo method_field('PATCH'); ?>
        <?php endif; ?>

        <div class="row">
            <?php if(guardIs('manager')): ?>
                <div class="form-group col-6 mb-2">
                    <label class="form-label"><?php echo e(t('School')); ?></label>
                    <select class="form-select" data-control="select2"
                            data-placeholder="<?php echo e(t('Select School')); ?>"
                            data-allow-clear="true" name="school_id" id="school_id">
                        <option></option>
                        <?php if(isset($schools)): ?>
                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($school->id); ?>"
                                    <?php echo e(isset($assignment) && $assignment->teacher->school_id == $school->id?'selected':''); ?>><?php echo e($school->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                    </select>
                </div>
            <?php endif; ?>

            <?php if(guardIn(['manager','school'])): ?>
                <div class="form-group col-6 mb-2">
                    <label class="form-label"><?php echo e(t('Teacher')); ?></label>
                    <select class="form-select" data-control="select2"
                            data-placeholder="<?php echo e(t('Select Teacher')); ?>"
                            data-allow-clear="true" name="teacher_id" id="teacher_id">
                        <option></option>
                        <?php if(isset($teachers)): ?>
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($teacher->id); ?>"
                                    <?php echo e(isset($assignment) && $assignment->teacher_id == $teacher->id?'selected':''); ?>><?php echo e($teacher->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>
                <?php else: ?>
                    <input type="hidden" name="teacher_id"  id="teacher_id" value="<?php echo e(auth()->id()); ?>">
            <?php endif; ?>
                <div class="form-group col-6 mb-2">
                    <label class="form-label"><?php echo e(t('Academic Year')); ?></label>
                    <select class="form-select assignment_year_id" data-control="select2"
                            data-placeholder="<?php echo e(t('Select Year')); ?>"
                            data-allow-clear="true" name="year_id" id="assignment_year_id" <?php if(isset($assignment)): ?> disabled <?php endif; ?>>
                        <option></option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>"
                                <?php echo e(isset($assignment) && $assignment->year_id == $year->id ?'selected':''); ?>><?php echo e($year->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('Students Grade')); ?></label>
                <select class="form-select students_grade" data-control="select2"
                        data-placeholder="<?php echo e(t('Select grade')); ?>"
                        data-allow-clear="true" name="students_grade" id="students_grade">
                    <option></option>
                    <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($grade->id); ?>" <?php echo e(isset($assignment) && $assignment->students_grade == $grade->id?'selected':''); ?>><?php echo e($grade->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

             <div class="form-group col-6 mb-2">
                    <label class="form-label"><?php echo e(t('Section')); ?></label>
                    <select class="form-select" name="sections[]" id="section"
                            data-control="select2" data-placeholder="<?php echo e(t('Select Section')); ?>" data-allow-clear="true"
                            multiple>
                        <option value="all"><?php echo e(t('All')); ?></option>
                        <?php if(isset($sections)): ?>
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($section); ?>"
                                    <?php echo e(isset($assignment) && $assignment->sections && in_array($section,$assignment->sections)?'selected':''); ?>><?php echo e($section); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group col-12 mb-2">
                    <label class="form-label"><?php echo e(t('Students')); ?></label>
                    <select class="form-control assignment_students" data-control="select2"
                            data-placeholder="<?php echo e(t('Select Students')); ?>"
                            data-allow-clear="true" name="students[]" id="assignment_students" multiple>
                        <?php if(isset($students)): ?>
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($student->id); ?>"
                                    <?php echo e(isset($assignment) &&  in_array($student->id,$assignment->students_ids)?'selected':''); ?>><?php echo e($student->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                    </select>
                </div>

            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('Story Grade')); ?></label>
                <select class="form-select assignment_grade" data-control="select2"
                        data-placeholder="<?php echo e(t('Select grade')); ?>"
                        data-allow-clear="true" name="story_grade" id="assignment_grade" <?php if(isset($assignment)): ?> disabled <?php endif; ?>>
                    <option></option>
                    <?php $__currentLoopData = storyGradesSys(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(isset($assignment) && $assignment->story_grade == $key?'selected':''); ?>><?php echo e($grade); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('Story')); ?></label>
                <select class="form-select " data-control="select2" multiple
                        data-placeholder="<?php echo e(t('Select Story')); ?>"
                        data-allow-clear="true"
                        name="stories_ids[]" id="" <?php if(isset($assignment)): ?> disabled <?php endif; ?>>
                    <option></option>
                    <?php if(isset($stories)): ?>
                        <?php $__currentLoopData = $stories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $story): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($story->id); ?>"
                                <?php echo e(isset($assignment) && $assignment->stories_ids && in_array($story->id,$assignment->stories_ids)?'selected':''); ?>><?php echo e($story->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            </div>


                <div class="form-group col-6 mb-2">
                    <label class="form-label"><?php echo e(t('Deadline')); ?></label>
                    <input type="text" name="deadline" class="form-control date" placeholder="<?php echo e(t('DeadLine')); ?>" <?php if(isset($assignment)): ?> value="<?php echo e($assignment->deadline); ?>" <?php endif; ?>>
                </div>
                <div class="form-group col-6">
                    <label class="form-label"><?php echo e(t('Exclude students who have completed the assignment before')); ?>:</label>
                    <div class="d-flex gap-2 border-secondary" style="border: 1px solid;border-radius: 5px;padding: 9px">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" value="1" name="exclude_students" <?php echo e(isset($assignment) &&  $assignment->exclude_students?'checked':''); ?>/>
                            <label class="form-check-label" for="flexRadioDefault">
                                <?php echo e(t('Yes')); ?>

                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" value="2" name="exclude_students"
                                   <?php if(isset($assignment)): ?>
                                       <?php echo e(!$assignment->exclude_students?'checked':''); ?>

                                   <?php else: ?>
                                       checked
                                <?php endif; ?>
                            />
                            <label class="form-check-label" for="flexRadioDefault">
                                <?php echo e(t('No')); ?>

                            </label>
                        </div>
                    </div>
                </div>

            <div class="separator my-4"></div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary"><?php echo e(t('Save')); ?></button>&nbsp;
            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\General\StoryAssignmentRequest::class, '#form_information'); ?>

    <script src="<?php echo e(asset('assets_v1/js/custom.js')); ?>?v=<?php echo e(time()); ?>"></script>

    <script>

        onSelectAllClick('section')

        onSelectAllClick('assignment_students')

        $('input[name="deadline"]').flatpickr();

        function getStudentsData() {
            let teacher_id = $('#teacher_id').val()
            let grade_id = $('select[name="students_grade"]').val()

            if (teacher_id && grade_id) {
                var students_url = '<?php echo e(route(getGuard().".getStudentsByGrade", ":id")); ?>';
                students_url = students_url.replace(':id', grade_id);
                $.ajax({
                    type: "get",
                    url: students_url,
                    data: {
                        teacher_id: teacher_id,
                        section: $('select[name="sections[]"]').val(),
                        year_id: $('#assignment_year_id').val()
                    }
                }).done(function (student_data) {
                    $('select[name="students[]"]').html(student_data.html);
                });
            }

        }

        getTeacherBySchool()

        getSectionBySchool()

        getSectionByTeacher()

        // getAndSetDataOnSelectChange('teacher_id','sections[]',getSectionByTeacherURL,1,[],function () {
        //     getStudentsData()
        // })


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

<?php $__env->stopSection(); ?>

<?php echo $__env->make(getGuard().'.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/story_assignment/edit.blade.php ENDPATH**/ ?>
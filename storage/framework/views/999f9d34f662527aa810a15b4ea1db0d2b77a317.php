
<?php $__env->startSection('title',$title); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <form enctype="multipart/form-data" id="form_information"
          action="<?php echo e(route('teacher.lesson_assignment.store')); ?>" method="post">
        <?php echo csrf_field(); ?>

        <div class="row">
            <div class="col-lg-4 mb-2">
                <label class="form-label"><?php echo e(t('Grade')); ?> :</label>
                <select name="grade_id" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Grade')); ?>"
                        data-allow-clear="true">
                    <option></option>
                    <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('Section')); ?></label>
                <select class="form-select" name="section[]" id="section"
                        data-control="select2" data-placeholder="<?php echo e(t('Select Section')); ?>" data-allow-clear="true"
                        multiple>
                    <option value="all"><?php echo e(t('All')); ?></option>
                    <?php $__currentLoopData = teacherSections(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($section); ?>"><?php echo e($section); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group col-2 mb-2">
                <label class="form-label"><?php echo e(t('Lesson Type')); ?></label>
                <select name="lesson_type" id="lesson_type" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Lesson Type')); ?>" data-allow-clear="true">
                    <option></option>
                    <?php $__currentLoopData = \App\Models\Lesson::lessonTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($lesson_type); ?>"><?php echo e(t($lesson_type)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group col-12 mb-2">
                <label class="form-label"><?php echo e(t('Lesson')); ?></label>
                <select class="form-select assignment_lesson" data-control="select2" multiple
                        data-placeholder="<?php echo e(t('Select Lesson')); ?>"
                        data-allow-clear="true"
                        name="lesson_id[]" id="assignment_lesson">
                    <option></option>
                </select>
            </div>

            <div class="form-group col-12 mb-2">
                <label class="form-label"><?php echo e(t('Students')); ?></label>
                <select class="form-control assignment_students" data-control="select2"
                        data-placeholder="<?php echo e(t('Select Students')); ?>"
                        data-allow-clear="true" name="students[]" id="assignment_students" multiple>
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('Deadline')); ?></label>
                <input type="text" name="deadline" class="form-control date" placeholder="<?php echo e(t('DeadLine')); ?>">
            </div>
            <div class="form-group col-6">
                <label class="form-label"><?php echo e(t('Exclude students who have completed the assignment before')); ?>:</label>
                <div class="d-flex gap-2 border-secondary" style="border: 1px solid;border-radius: 5px;padding: 9px">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="radio" value="1" name="exclude_students"/>
                        <label class="form-check-label" for="flexRadioDefault">
                            <?php echo e(t('Yes')); ?>

                        </label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="radio" value="2" name="exclude_students" checked/>
                        <label class="form-check-label" for="flexRadioDefault">
                            <?php echo e(t('No')); ?>

                        </label>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center col-6 pt-4 gap-3">







                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="test_assignment" checked/>
                    <label class="form-check-label text-dark">
                        <?php echo e(t('Test assignment')); ?>

                    </label>
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
    <script src="<?php echo e(asset('assets_v1/js/custom.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Teacher\LessonAssignmentRequest::class, '#form_information'); ?>


    <script>

        onSelectAllClick('section')

        $('input[name="deadline"]').flatpickr();


        $('select[name="grade_id"]').change(function () {
            var id = $(this).val();
            var teacher = $('select[name="teacher_id"]').val();
            getStudentsData(teacher, id);
            
            
            
            

            
            
            

        });

        $('select[name="section[]"]').change(function () {
            var grade = $('select[name="grade_id"]').val();
            var teacher = $('select[name="teacher_id"]').val();
            if(grade)
            {
                getStudentsData(teacher, grade);
            }
        });


        onSelectAllClick('assignment_students')

        function getStudentsData(teacher, grade) {
            if (grade) {
                var students_url = '<?php echo e(route("teacher.getStudentsByGrade", ":id")); ?>';
                students_url = students_url.replace(':id', grade);
                $.ajax({
                    type: "get",
                    url: students_url,
                    data: {
                        teacher_id: "<?php echo e(request()->get('teacher_id')); ?>",
                        section:$('#section').val()
                    }
                }).done(function (student_data) {
                    $('select[name="students[]"]').html(student_data.html);
                });
            }

        }

        getLessonsByGrade()

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/lessons_assignments/create.blade.php ENDPATH**/ ?>
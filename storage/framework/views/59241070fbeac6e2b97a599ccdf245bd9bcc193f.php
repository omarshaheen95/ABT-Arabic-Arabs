
<?php $__env->startSection('title',$title); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <form enctype="multipart/form-data" id="form_information"
          action="<?php echo e(route('manager.motivational_certificate.store')); ?>" method="post">
        <?php echo csrf_field(); ?>

        <div class="row">

            <input type="hidden" name="model_type" value="<?php echo e($cer_type); ?>">
            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('School')); ?></label>
                <select class="form-select" data-control="select2"
                        data-placeholder="<?php echo e(t('Select School')); ?>"
                        data-allow-clear="true" name="school_id" id="school_id">
                    <option></option>
                    <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('Teacher')); ?></label>
                <select class="form-select" data-control="select2"
                        data-placeholder="<?php echo e(t('Select Teacher')); ?>"
                        data-allow-clear="true" name="teacher_id" id="teacher_id">
                    <option></option>
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('Section')); ?></label>
                <select class="form-select" name="section[]" id="section"
                        data-control="select2" data-placeholder="<?php echo e(t('Select Section')); ?>" data-allow-clear="true"
                        multiple>
                    <option value="all"><?php echo e(t('All')); ?></option>
                </select>
            </div>

            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('Student Grade')); ?></label>
                <select class="form-select" data-control="select2"
                        data-placeholder="<?php echo e(t('Select Grade')); ?>"
                        data-allow-clear="true" name="grade_id" id="grade">
                    <option></option>
                    <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <?php if($cer_type == 'lesson'): ?>
                <div class="form-group col-6 mb-2">
                    <label class="form-label"><?php echo e(t('Lesson')); ?></label>
                    <select class="form-select assignment_lesson" data-control="select2" multiple
                            data-placeholder="<?php echo e(t('Select Lesson')); ?>"
                            data-allow-clear="true"
                            name="lesson_id[]" id="assignment_lesson">
                        <option></option>
                    </select>
                </div>
            <?php else: ?>
                <div class="form-group col-6 mb-2">
                    <label class="form-label"><?php echo e(t('Grade')); ?></label>
                    <select class="form-select" data-control="select2"
                            data-placeholder="<?php echo e(t('Select Grade')); ?>"
                            data-allow-clear="true" name="grade" id="grade">
                        <option></option>
                        <?php $__currentLoopData = storyGradesSys(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"><?php echo e($grade); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group col-6 mb-2">
                    <label class="form-label"><?php echo e(t('Story')); ?></label>
                    <select class="form-select " data-control="select2" multiple
                            data-placeholder="<?php echo e(t('Select Story')); ?>"
                            data-allow-clear="true"
                            name="story_id[]" id="">
                        <option></option>
                    </select>
                </div>
            <?php endif; ?>


            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('Granted In')); ?></label>
                <input type="text" name="granted_in" class="form-control date" placeholder="<?php echo e(t('Granted In')); ?>">
            </div>


            <div class="form-group col-12 mb-2">
                <label class="form-label"><?php echo e(t('Students')); ?></label>
                <select class="form-control assignment_students" data-control="select2"
                        data-placeholder="<?php echo e(t('Select Students')); ?>"
                        data-allow-clear="true" name="students[]" id="assignment_students" multiple>
                    <option value=""><?php echo e(t('All')); ?></option>

                </select>
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
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\MotivationalCertificateRequest::class, '#form_information'); ?>

    <script>


        $('input[name="granted_in"]').flatpickr();

        <?php if($cer_type == 'lesson'): ?>
        $('select[name="grade_id"]').change(function () {
            // var id = $('select[name="grade"]').val();
            var id = $(this).val();
            var teacher = $('select[name="teacher_id"]').val();
            if(teacher)
            {
                getStudentsData(teacher, id);
            }
        });

        getLessonsByGrade()


        <?php else: ?>

        $('select[name="grade_id"]').change(function () {
            var id = $(this).val();
            var teacher = $('select[name="teacher_id"]').val();
            if(teacher) {
                getStudentsData(teacher, id);
            }
        });

        getStoriesByGrade()

        <?php endif; ?>

        onSelectAllClick('assignment_students')
        onSelectAllClick('section')

        function getStudentsData(teacher, grade) {
            console.log('get students')
            if (grade) {
                var students_url = '<?php echo e(route("manager.getStudentsByGrade", ":id")); ?>';
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

        getTeacherBySchool()

        getSectionByTeacher(null,function (callback) {
            var teacher = $('select[name="teacher_id"]').val();
            var grade = $('select[name="grade_id"]').val();
            if (grade) {
                getStudentsData(teacher, grade)
            }
        })

        $('select[name="section[]"]').change(function () {
            var grade = $('select[name="grade_id"]').val();
            var teacher = $('select[name="teacher_id"]').val();
            if(teacher && grade)
            {
                getStudentsData(teacher, grade);
            }
        });

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/motivational_certificates/edit.blade.php ENDPATH**/ ?>
<?php $__env->startSection('style'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/froala_editor.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/froala_style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/code_view.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/image_manager.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/image.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/table.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/video.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/colors.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
    <style>
        .leftDirection {
            direction: ltr !important;
        }

        .rightDirection {
            direction: rtl !important;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('manager.lesson.index')); ?>"><?php echo e(t('Lessons')); ?></a>
    </li>
    <li class="breadcrumb-item">
        <?php echo e(isset($title) ? $title:''); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('title'); ?>
     <?php echo e(t('Lesson').': '.$lesson->name.' - '.$lesson->grade->name); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('actions'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lesson review')): ?>
    <a href="<?php echo e(route('manager.lesson.review', [$lesson->id, 'test'])); ?>" target="_blank" class="btn btn-primary btn-elevate btn-icon-sm me-2">
        <i class="la la-eye"></i>
        <?php echo e(t('Preview')); ?>

    </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">

        <form enctype="multipart/form-data" id="form_information"
              class="form-data"
              action="<?php echo e(route('manager.lesson.assessment.update', ['id'=>$lesson->id, 'type'=>$lesson->lesson_type])); ?>"
              method="post">
            <?php echo csrf_field(); ?>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="fs-2 mb-2"><?php echo e(t('General Content')); ?> :</label>
                        <textarea class="form-control edit" id='edit' style=""
                                  name="content">
                            <?php if(isset($questions) && count($questions)): ?>
                                <?php echo e($lesson->content); ?>

                            <?php endif; ?>
                        </textarea>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="d-flex align-items-center my-4">
                    <label class="fs-2"><?php echo e(t('Questions')); ?> :</label>

                    <button type="button"
                            class="btn btn-secondary btn-elevate btn-icon-sm add_button ms-auto">
                        <i class="la la-plus"></i>
                        <?php echo e(t('Add Question')); ?>

                    </button>
                </div>
                <div class="separator my-3"></div>

                <div class="questions">
                    <?php if(isset($questions) && count($questions)): ?>

                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group row align-items-end">
                            <div class="col-8">
                                <label class="mb-1"><?php echo e(t('Q')); ?> <?php echo e($loop->index+1); ?>:</label>
                                <input required class="form-control"
                                       name="old_questions[<?php echo e($question->id); ?>]" type="text"
                                       value="<?php echo e($question->content); ?>">
                            </div>
                            <div class="col-1">
                                <label class="mb-1"><?php echo e(t('Mark')); ?> :</label>
                                <input type="number" required class="form-control"
                                       name="old_mark[<?php echo e($question->id); ?>]"
                                       value="<?php echo e($question->mark); ?>"/>
                            </div>
                            <div class="col-lg-2">
                                <div class="d-flex">
                                    <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                    <?php if($question->getFirstMediaUrl('imageQuestion')): ?>
                                        <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                            <a data-id="<?php echo e($question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                <i class="la la-close la-2"></i>
                                            </a>
                                            <a href="<?php echo e($question->getFirstMediaUrl('imageQuestion')); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                <i class="la la-eye la-2"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                </div>
                                <input type="file" name="old_attachment[<?php echo e($question->id); ?>]" class="form-control">
                            </div>
                            <div class="col-1 d-flex align-items-center mt-3">
                                <button type="button"
                                        class="btn btn-danger btn-icon btn-block delete_input"><i
                                        class="fa fa-close"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <?php for($i = 1; $i<=1;$i++): ?>
                        <div class="form-group row align-items-end">
                            <div class="col-8">
                                <label class="mb-1"><?php echo e(t('Q')); ?> <?php echo e($i); ?>:</label>
                                <input required class="form-control" name="questions[<?php echo e($i); ?>]" type="text">
                            </div>
                            <div class="col-1">
                                <label class="mb-1"><?php echo e(t('Mark')); ?> :</label>
                                <input type="number" required class="form-control"
                                       name="mark[<?php echo e($i); ?>]"
                                />
                            </div>
                            <div class="col-lg-2">
                                <div class="d-flex">
                                    <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                </div>
                                <input type="file" name="attachment[<?php echo e($i); ?>]" class="form-control">
                            </div>
                            <div class="col-1 d-flex align-items-center mt-3">
                                <button type="button"
                                        class="btn btn-danger btn-icon btn-block delete_input"><i
                                        class="fa fa-close"></i>
                                </button>
                            </div>
                        </div>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>

            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary mr-2"><?php echo e(t('Save')); ?></button>
                </div>
            </div>

        </form>


        <?php $__env->stopSection(); ?>

        <?php $__env->startSection('script'); ?>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/froala_editor.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/align.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/code_beautifier.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/code_view.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/draggable.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/image.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/image_manager.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/link.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/lists.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/paragraph_format.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/paragraph_style.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/table.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/video.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/url.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/entities.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/colors.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/font_size.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/font_family.min.js')); ?>"></script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/word_paste.min.js')); ?>"></script>
            <script>
                const CSRF = "<?php echo e(csrf_token()); ?>";
                const UPLOAD_ROUTE = "<?php echo e(route('uploadImageLesson')); ?>";
            </script>
            <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/editor.js')); ?>"></script>
            <script src="<?php echo e(asset('assets_v1/js/jquery-validation/dist/jquery.validate.js')); ?>"></script>
            <script src="<?php echo e(asset('assets_v1/js/jquery-validation/dist/additional-methods.js')); ?>"></script>
            <?php if(app()->getLocale()=='ar'): ?>
                <script src="<?php echo e(asset('assets_v1/js/jquery-validation/dist/localization/messages_ar.js')); ?>"></script>
            <?php endif; ?>
            <script>
                $(document).ready(function () {
                    $(document).on('click', '.deleteRecord', (function () {
                        var id = $(this).data("id");
                        var url = '<?php echo e(route('manager.lesson.remove_a_question_attachment', ':id')); ?>';
                        url = url.replace(':id', id);
                        $('#delete_attachment_form').attr('action', url);
                    }));

                 $(document).on('click', '.deleteRecord', (function () {
                                        let parent = $(this).parent()
                                        let id = $(this).data("id");
                                        let url = '<?php echo e(route('manager.lesson.remove_a_question_attachment', ':id')); ?>';
                                        url = url.replace(':id', id);
                                        showAlert("<?php echo e(t('Delete Attachment')); ?>","<?php echo e(t('Are you sure to for deleting process?')); ?>",'warning',
                                        true,true,function (callback) {
                                                if (callback){
                                                    showLoadingModal()
                                                    $.ajax({
                                                        url: url,
                                                        type: 'post',
                                                        data: {'_token':"<?php echo e(csrf_token()); ?>"},
                                                        success: function(response){
                                                            hideLoadingModal()
                                                            parent.remove()
                                                            toastr.success(response.message);
                                                        },
                                                        error(error){
                                                            hideLoadingModal()
                                                            toastr.error(error.responseJSON.message);
                                                        }
                                                    });
                                                }
                                            })
                                    }));

                 validateAndSubmit('form-data')

                    var x = 1; //Initial field counter is 1
                    var y = 1; //Initial field counter is 1

                    var maxField = 10 //Input fields increment limitation
                    var addButton = $('.add_button'); //Add button selector
                    //Once add button is clicked
                    $(addButton).click(function () {
                        var row_id = $(this).attr('data-id');
                        var wrapper_row = $('.questions'); //Input field wrapper
                        x = wrapper_row.children().length;
                        y = wrapper_row.children().length;
                        //Check maximum number of input fields
                        if (x < maxField) {
                            x++; //Increment field counter
                            y++; //Increment field counter.
                            $(wrapper_row).append(
                                "<div class=\"form-group row\">\n" +
                                "<div class=\"col-8\">\n" +
                                "<label class="mb-2"><?php echo e(t('Q')); ?> " + y + ": </label></label>\n" +
                                "<input required type='text' class=\"form-control\" name=\"questions[" + y + "]\">" +
                                "</div>\n" +
                                "<div class=\"col-1\">\n" +
                                "<label class="mb-2"><?php echo e(t('Mark')); ?> :</label>\n" +
                                "<input type=\"number\" required name=\"mark[" + y + "]\" class=\"form-control\">\n" +
                                "</div>\n" +
                                "<div class=\"col-2\">\n" +
                                "<label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>\n" +
                                "<input type=\"file\" name=\"attachment[" + y + "]\" class=\"form-control\">\n" +
                                "</div>\n" +
                                "<div class=\"col-1 d-flex align-items-center mt-3\">" +
                                "<button type=\"button\" class=\"btn btn-danger btn-icon btn-block delete_input\">"+
                                "<i class=\"fa fa-close\"></i>"+
                                "</button>"+
                                "</div>"+
                                "</div>"

                            ); //Add field html
                        }
                    });
                    //Once remove button is clicked
                    $(document).on('click', '.delete_input', function (e) {
                        e.preventDefault();
                        $(this).parent().parent('div').remove(); //Remove field html
                        // x--;
                    });
                });
            </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/lesson/assessment/assessment_listening_speaking.blade.php ENDPATH**/ ?>
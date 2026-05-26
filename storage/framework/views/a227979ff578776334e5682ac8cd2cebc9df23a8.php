

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('manager.lesson.index')); ?>">الدروس</a>
    </li>
    <li class="breadcrumb-item">
        <?php echo e(isset($title) ? $title:''); ?>

    </li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('title'); ?>
    <?php echo e($lesson->name); ?> - <?php echo e($lesson->grade->name); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>

    <div class="row">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <?php if(isset($t_f_questions) && count($t_f_questions)): ?>
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab"  type="button" role="tab"
                       data-bs-target="#kt_tabs_1_1"><?php echo e(t('True or False')); ?> </a>
                </li>
            <?php endif; ?>
            <?php if(isset($c_questions) && count($c_questions)): ?>
                <li class="nav-item ">
                    <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_2" type="button" role="tab"
                    ><?php echo e(t('Choose the Answer')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(isset($m_questions) && count($m_questions)): ?>
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_3" type="button" role="tab"><?php echo e(t('Matching')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(isset($s_questions) && count($s_questions)): ?>
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_4" type="button" role="tab"><?php echo e(t('Sorting Words')); ?></a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="tab-content py-5">
            <?php if(isset($t_f_questions) && count($t_f_questions)): ?>
                <div class="tab-pane fade show active" id="kt_tabs_1_1" role="tabpanel">
                    <form enctype="multipart/form-data" id="true_false_form"
                          class="form-data"
                          action="<?php echo e(route('manager.lesson.training.update', [$lesson->id, 1])); ?>"
                          method="post">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($t_f_questions) && count($t_f_questions)): ?>
                            <?php
                                $i = 1;
                            ?>
                            <?php $__currentLoopData = $t_f_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t_f_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group row mt-2">
                                    <div class="col-lg-7">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                        <input required class="form-control"
                                               name="old_t_f_question[<?php echo e($t_f_question->id); ?>]"
                                               type="text"
                                               value="<?php echo e($t_f_question->content); ?>">
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="mb-2"><?php echo e(t('Correct Answer')); ?> :</label>
                                        <div class="d-flex gap-1">
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input required class="form-check-input" type="radio"
                                                       <?php echo e(optional($t_f_question->trueFalse)->result == 1 ? 'checked':''); ?>

                                                       value="1" name="old_t_f[<?php echo e($t_f_question->id); ?>]" id="flexRadioLg"/>
                                                <label class="form-check-label" for="flexRadioLg">
                                                    <?php echo e(t('True')); ?>

                                                </label>
                                            </div>
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input required
                                                       class="form-check-input"
                                                       <?php echo e(optional($t_f_question->trueFalse)->result == 0 ? 'checked':''); ?>

                                                       type="radio" value="0" name="old_t_f[<?php echo e($t_f_question->id); ?>]">
                                                <label class="form-check-label" for="flexRadioLg">
                                                    <?php echo e(t('False')); ?>

                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                            <?php if($t_f_question->getFirstMediaUrl('t_imageQuestion')): ?>
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="<?php echo e($t_f_question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="<?php echo e($t_f_question->getFirstMediaUrl('t_imageQuestion')); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                        <input type="file" name="old_t_f_q_attachment[<?php echo e($t_f_question->id); ?>]" class="form-control">
                                    </div>
                                </div>
                                <?php
                                    $i ++;
                                ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                        <div class="row my-5">
                            <div class="separator separator-content my-4"></div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit"
                                        class="btn btn-primary mr-2"><?php echo e(t('Save')); ?></button>
                            </div>
                        </div>

                    </form>
                </div>
            <?php endif; ?>

            <?php if(isset($c_questions) && count($c_questions)): ?>
                <div class="tab-pane fade show active" id="kt_tabs_1_2" role="tabpanel">
                    <form enctype="multipart/form-data" id="choose_form"
                          class="form-data"
                          action="<?php echo e(route('manager.lesson.training.update', [$lesson->id, 2])); ?>"
                          method="post">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($c_questions) && count($c_questions)): ?>
                            <?php
                                $i = 1;
                            ?>
                            <?php $__currentLoopData = $c_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group row mt-2">
                                    <div class="col-lg-6">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                        <input required class="form-control"
                                               name="old_c_question[<?php echo e($c_question->id); ?>]" type="text"
                                               value="<?php echo e($c_question->content); ?>">
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2"><?php echo e(t('Correct Answer')); ?> :</label>
                                        <div class="d-flex gap-2">
                                            <?php
                                                $o_counter = 1;
                                            ?>
                                            <?php $__currentLoopData = $c_question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                    <input required <?php echo e($option->result == 1 ? 'checked':''); ?> class="form-check-input" type="radio"
                                                           value="<?php echo e($option->id); ?>"
                                                           name="old_c_q_a[<?php echo e($c_question->id); ?>]">
                                                    <label class="form-check-label" for="flexRadioLg">
                                                        <?php echo e($o_counter); ?>

                                                    </label>
                                                </div>
                                                <?php
                                                    $o_counter ++;
                                                ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                            <?php if($c_question->getFirstMediaUrl('t_imageQuestion')): ?>
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="<?php echo e($c_question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="<?php echo e($c_question->getFirstMediaUrl('t_imageQuestion')); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                        <input type="file" name="old_c_q_attachment[<?php echo e($c_question->id); ?>]" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <?php
                                        $o_counter = 1;
                                    ?>
                                    <?php $__currentLoopData = $c_question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-lg-3">
                                            <label class="mb-2"><?php echo e($o_counter); ?> :</label>
                                            <input required type="text" class="form-control"
                                                   name="old_c_q_option[<?php echo e($c_question->id); ?>][<?php echo e($option->id); ?>]"
                                                   value="<?php echo e($option->content); ?>">
                                        </div>
                                        <?php
                                            $o_counter ++;
                                        ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php
                                    $i ++;
                                ?>
                                <div class="separator separator-dashed my-5"></div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <div class="row my-5">
                            <div class="separator separator-content my-4"></div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit"
                                        class="btn btn-primary mr-2"><?php echo e(t('Save')); ?></button>
                            </div>
                        </div>

                    </form>
                </div>
            <?php endif; ?>

            <?php if(isset($m_questions) && count($m_questions)): ?>
                <div class="tab-pane fade show active" id="kt_tabs_1_3" role="tabpanel">
                    <form enctype="multipart/form-data" id="match_form"
                          class="form-data"
                          action="<?php echo e(route('manager.lesson.training.update', [$lesson->id, 3])); ?>"
                          method="post">
                        <?php echo e(csrf_field()); ?>

                        <?php if(isset($m_questions) && count($m_questions)): ?>
                            <?php
                                $i = 1;
                            ?>
                            <?php $__currentLoopData = $m_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group row">
                                    <div class="col-lg-9">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                        <input required class="form-control"
                                               name="old_m_question[<?php echo e($m_question->id); ?>]" type="text"
                                               value="<?php echo e($m_question->content); ?>">
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                            <?php if($m_question->getFirstMediaUrl('t_imageQuestion')): ?>
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="<?php echo e($m_question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="<?php echo e($m_question->getFirstMediaUrl('t_imageQuestion')); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                        <input type="file" name="old_m_q_attachment[<?php echo e($m_question->id); ?>]" class="form-control">
                                    </div>
                                </div>
                                <?php
                                    $i ++;
                                ?>
                                <?php
                                    $o_counter = 1;
                                ?>
                                <?php $__currentLoopData = $m_question->matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-group row mt-2">
                                        <div class="col-lg-8">
                                            <label class="mb-2"><?php echo e(t('Option')); ?>  <?php echo e($o_counter); ?>:</label>
                                            <input required class="form-control"
                                                   name="old_m_q_option[<?php echo e($match->id); ?>]"
                                                   value="<?php echo e($match->content); ?>" type="text">
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="mb-2"><?php echo e(t('Answer')); ?> :</label>
                                            <input required class="form-control"
                                                   name="old_m_q_answer[<?php echo e($match->id); ?>]"
                                                   value="<?php echo e($match->result); ?>" type="text">
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="d-flex">
                                                <label class="mb-2"><?php echo e(t('Image')); ?> :</label>
                                                <?php if($match->getFirstMediaUrl('t_match')): ?>
                                                    <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                        <a data-id="<?php echo e($match->id); ?>" class="btn btn-icon btn-danger deleteMatchImageRecord" style="height: 20px; width: 20px">
                                                            <i class="la la-close la-2"></i>
                                                        </a>
                                                        <a href="<?php echo e($match->getFirstMediaUrl('t_match')); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                            <i class="la la-eye la-2"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                            <input type="file" name="old_m_q_image[<?php echo e($match->id); ?>]" class="form-control">
                                        </div>

                                    </div>
                                    <?php
                                        $o_counter ++;
                                    ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="separator separator-dashed my-5"></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <div class="row my-5">
                            <div class="separator separator-content my-4"></div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit"
                                        class="btn btn-primary mr-2"><?php echo e(t('Save')); ?></button>
                            </div>
                        </div>

                    </form>
                </div>
            <?php endif; ?>

            <?php if(isset($s_questions) && count($s_questions)): ?>
                <div class="tab-pane fade show active" id="kt_tabs_1_4" role="tabpanel">
                    <form enctype="multipart/form-data" id="sort_form"
                          class="form-data"
                          action="<?php echo e(route('manager.lesson.training.update', [$lesson->id, 4])); ?>"
                          method="post">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($s_questions) && count($s_questions)): ?>
                            <?php
                                $i = 1;
                            ?>
                            <?php $__currentLoopData = $s_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group row mb-2">
                                    <div class="col-lg-9">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                        <input required class="form-control"
                                               name="old_s_question[<?php echo e($s_question->id); ?>]" type="text"
                                               value="<?php echo e($s_question->content); ?>">
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                            <?php if($s_question->getFirstMediaUrl('t_imageQuestion')): ?>
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="<?php echo e($s_question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="<?php echo e($s_question->getFirstMediaUrl('t_imageQuestion')); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                        <input type="file" name="old_s_q_attachment[<?php echo e($s_question->id); ?>]" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row" id="row-<?php echo e($s_question->id); ?>">
                                    <?php
                                        $o_counter = 1;
                                    ?>
                                    <?php $__currentLoopData = $s_question->sortWords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sort_word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-4 mb-3 option">
                                            <label class="mb-2"><?php echo e(t('Option')); ?>  <?php echo e($o_counter); ?> : <a href="#"
                                                                                         data-id="<?php echo e($sort_word->id); ?>"
                                                                                         data-bs-toggle="modal"
                                                                                         data-target="#deleteSortWord"
                                                                                         class="text-danger delete_old_input"><?php echo e(t('Delete')); ?> </a></label>
                                            <input required class="form-control"
                                                   name="old_s_q_option[<?php echo e($s_question->id); ?>][<?php echo e($sort_word->id); ?>]"
                                                   value="<?php echo e($sort_word->content); ?>" type="text">
                                        </div>

                                        <?php
                                            $o_counter ++;
                                        ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-1 d-flex align-items-end mt-3">
                                        <button type="button" data-id="<?php echo e($s_question->id); ?>"
                                                id="add_label_<?php echo e($s_question->id); ?>"
                                                class="btn btn-danger btn-icon btn-block add_button"><i
                                                class="fa fa-plus"></i>
                                        </button>
                                    </div>

                                    <div class="separator separator-dashed my-5"></div>
                                </div>
                                <?php
                                    $i ++;
                                ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <div class="row my-5">
                            <div class="separator separator-content my-4"></div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit"
                                        class="btn btn-primary mr-2"><?php echo e(t('Save')); ?></button>
                            </div>
                        </div>

                    </form>
                </div>
            <?php endif; ?>

        </div>

        <?php $__env->stopSection(); ?>

        <?php $__env->startSection('script'); ?>
            <script src="<?php echo e(asset('assets_v1/js/jquery-validation/dist/jquery.validate.js')); ?>"></script>
            <script src="<?php echo e(asset('assets_v1/js/jquery-validation/dist/additional-methods.js')); ?>"></script>
            <?php if(app()->getLocale()=='ar'): ?>
                <script src="<?php echo e(asset('assets_v1/js/jquery-validation/dist/localization/messages_ar.js')); ?>"></script>
            <?php endif; ?>
            <script>
                $(document).ready(function () {

                    $(document).on('click', '.deleteRecord', (function () {
                        let parent = $(this).parent()
                        let id = $(this).data("id");
                        let url = '<?php echo e(route('manager.lesson.remove_t_question_attachment', ':id')); ?>';
                        url = url.replace(':id', id);
                        showAlert("<?php echo e(t('Delete Record')); ?>","<?php echo e(t('Are you sure to for deleting process?')); ?>",'warning',
                            true,true,function (callback) {
                                if (callback){
                                    showLoadingModal()
                                    $.ajax({
                                        url: url,
                                        type: 'post',
                                        data: {'_token':"<?php echo e(csrf_token()); ?>"},
                                        success: function(response){
                                            hideLoadingModal()
                                            parent.children('a').each(function (index,ele) {
                                                ele.remove()
                                            })
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

                    $(document).on('click', '.delete_old_input', (function () {
                        var id = $(this).data("id");
                        var url = '<?php echo e(route('manager.lesson.remove_t_sort_word', ':id')); ?>';
                        url = url.replace(':id', id);
                        let parent = $(this).parent()
                        showAlert("<?php echo e(t('Delete Input')); ?>","<?php echo e(t('Are you sure to for deleting process?')); ?>",'warning',
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

                    $(document).on('click', '.deleteMatchImageRecord', (function () {
                        let parent = $(this).parent()
                        var id = $(this).data("id");
                        var url = '<?php echo e(route('manager.lesson.remove_t_match_image', ':id')); ?>';
                        url = url.replace(':id', id);
                        showAlert("<?php echo e(t('Delete Image')); ?>","<?php echo e(t('Are you sure to for deleting process?')); ?>",'warning',
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


                    validateAndSubmit('form-data') //validate any form and submit data



                    var x = 1; //Initial field counter is 1
                    var y = 1; //Initial field counter is 1

                    var maxField = 10 //Input fields increment limitation
                    var addButton = $('.add_button'); //Add button selector
                    //Once add button is clicked
                    $(addButton).click(function () {
                        var row_id = $(this).attr('data-id');
                        var wrapper_row = $('#row-' + row_id); //Input field wrapper
                        x = $('#row-' + row_id).children('.option').length;
                        y = $('#row-' + row_id).children('.option').length;
                        //Check maximum number of input fields
                        if (x < maxField) {
                            x++; //Increment field counter
                            y++; //Increment field counter.
                            $(this).parent().before(
                                "<div class=\"col-lg-4 mt-3 option\">\n" +
                                "<label class="mb-2"><?php echo e(t('Option')); ?>  " + y + " : <a href='#' class='text-danger delete_input'><?php echo e(t('Delete')); ?> </a></label>\n" +
                                "<input required class=\"form-control\" name=\"s_q_option[" + row_id + "]["+y+"]\" type=\"text\">\n")
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

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/lesson/training/edit_specific_question.blade.php ENDPATH**/ ?>
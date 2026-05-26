

<?php $__env->startSection('title'); ?>
    <?php echo e(t('Lesson').': '.$lesson->name.' - '.$lesson->grade->name); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('manager.lesson.index')); ?>">الصف <?php echo e($lesson->grade_name); ?></a>
    </li>
    <li class="breadcrumb-item">
        <?php echo e(isset($title) ? $title:''); ?>

    </li>

<?php $__env->stopPush(); ?>
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
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <?php if(isset($data_count['true_false']) && $data_count['true_false'] > 0): ?>
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab"  type="button" role="tab"
                       data-bs-target="#kt_tabs_1_1"><?php echo e(t('True or False')); ?> </a>
                </li>
            <?php endif; ?>
            <?php if(isset($data_count['choose']) && $data_count['choose'] > 0): ?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_2" type="button" role="tab"
                    ><?php echo e(t('Choose the Answer')); ?></a>
                </li>
            <?php endif; ?>
            <?php if(isset($data_count['match']) && $data_count['match'] > 0): ?>
               <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_3" type="button" role="tab"><?php echo e(t('Matching')); ?></a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="tab-content pt-5">
            <?php if(isset($data_count['true_false']) && $data_count['true_false'] > 0): ?>
                <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                    <form enctype="multipart/form-data" id="true_false_form"
                          class="form-data"
                          action="<?php echo e(route('manager.lesson.assessment.update', [$lesson->id, 1])); ?>"
                          method="post">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($t_f_questions) && count($t_f_questions)): ?>
                            <?php
                                $i = 1;
                            ?>
                            <?php $__currentLoopData = $t_f_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t_f_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    if ($lesson->level <= 3)
                                    {
                                        if ($i <= 3)
                                        {
                                            $mark = 6;
                                        }else{
                                            $mark = 5;
                                        }
                                    }else{
                                        if ($i <= 1)
                                        {
                                            $mark = 6;
                                        }else{
                                            $mark = 5;
                                        }
                                    }
                                ?>
                                <div class="form-group row mt-2">
                                    <div class="col-lg-7">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                        <input required class="form-control"
                                               name="old_t_f_question[<?php echo e($t_f_question->id); ?>]"
                                               type="text"
                                               value="<?php echo e($t_f_question->content); ?>">
                                        <input type="hidden" name="mark[<?php echo e($t_f_question->id); ?>]"
                                               value="<?php echo e($mark); ?>"/>
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
                                            <?php if($t_f_question->getFirstMediaUrl('imageQuestion')): ?>
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="<?php echo e($t_f_question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="<?php echo e($t_f_question->getFirstMediaUrl('imageQuestion')); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
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
                        <?php else: ?>
                            <?php for($i = 1; $i<=$data_count['true_false'];$i++): ?>
                                <?php
                                    if ($lesson->level <= 3)
                                    {
                                        if ($i <= 3)
                                        {
                                            $mark = 6;
                                        }else{
                                            $mark = 5;
                                        }
                                    }else{
                                        if ($i <= 1)
                                        {
                                            $mark = 6;
                                        }else{
                                            $mark = 5;
                                        }
                                    }
                                ?>
                                <div class="form-group row">
                                    <div class="col-lg-7">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                        <input required class="form-control" name="t_f_question[<?php echo e($i); ?>]"
                                               type="text">
                                        <input type="hidden" name="mark[<?php echo e($i); ?>]" value="<?php echo e($mark); ?>"/>

                                    </div>
                                    <div class="col-lg-2">
                                        <label class="mb-2"><?php echo e(t('Correct Answer')); ?> :</label>
                                        <div class="d-flex gap-1">
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input required class="form-check-input" type="radio" checked
                                                       value="1" name="t_f[<?php echo e($i); ?>]" id="flexRadioLg"/>
                                                <label class="form-check-label" for="flexRadioLg">
                                                    <?php echo e(t('True')); ?>

                                                </label>
                                            </div>
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input required
                                                       class="form-check-input"

                                                       type="radio" value="0" name="t_f[<?php echo e($i); ?>]">
                                                <label class="form-check-label" for="flexRadioLg">
                                                    <?php echo e(t('False')); ?>

                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                        <input type="file" name="t_f_q_attachment[<?php echo e($i); ?>]"
                                               class="form-control">
                                    </div>
                                </div>
                            <?php endfor; ?>
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

            <?php if(isset($data_count['choose']) && $data_count['choose'] > 0): ?>
                    <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                        <form enctype="multipart/form-data" id="choose_form"
                              class="form-data"
                              action="<?php echo e(route('manager.lesson.assessment.update', [$lesson->id, 2])); ?>"
                              method="post">
                            <?php echo csrf_field(); ?>
                            <?php if(isset($c_questions) && count($c_questions)): ?>
                                <?php
                                    $i = 1;
                                ?>
                                <?php $__currentLoopData = $c_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        if ($lesson->level <= 3)
                                        {
                                                $mark = 6;
                                        }else{
                                                $mark = 8;
                                        }
                                    ?>
                                    <div class="form-group row mt-2">
                                        <div class="col-lg-6">
                                            <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                            <input required class="form-control"
                                                   name="old_c_question[<?php echo e($c_question->id); ?>]" type="text"
                                                   value="<?php echo e($c_question->content); ?>">
                                            <input type="hidden" name="mark[<?php echo e($c_question->id); ?>]"
                                                   value="<?php echo e($mark); ?>"/>
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
                                                <?php if($c_question->getFirstMediaUrl('imageQuestion')): ?>
                                                    <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                        <a data-id="<?php echo e($c_question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                            <i class="la la-close la-2"></i>
                                                        </a>
                                                        <a href="<?php echo e($c_question->getFirstMediaUrl('imageQuestion')); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
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

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <?php for($i = 1; $i<=$data_count['choose'];$i++): ?>
                                    <?php
                                        if ($lesson->level <= 3)
                                        {
                                                $mark = 6;
                                        }else{
                                                $mark = 8;
                                        }
                                    ?>
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                            <input required class="form-control" name="c_question[<?php echo e($i); ?>]"
                                                   type="text">
                                            <input type="hidden" name="mark[<?php echo e($i); ?>]" value="<?php echo e($mark); ?>"/>

                                        </div>
                                        <div class="col-lg-3">
                                            <label class="mb-2"><?php echo e(t('Correct Answer')); ?> :</label>
                                            <div class="d-flex gap-3">
                                                <?php $__currentLoopData = range(1,4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($item !=4 || $item == 4 && $lesson->grade->grade_number >= 3): ?>
                                                        <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                            <input required  class="form-check-input" type="radio"
                                                                   value="<?php echo e($item); ?>"
                                                                   name="c_q_a[<?php echo e($i); ?>]">
                                                            <label class="form-check-label" for="flexRadioLg">
                                                                <?php echo e($item); ?>

                                                            </label>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <label class="mb-2"><?php echo e(t('Attachment')); ?>  :</label>
                                            <input type="file" name="c_q_attachment[<?php echo e($i); ?>]"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <?php $__currentLoopData = range(1,4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($item !=4 || $item == 4 && $lesson->grade->grade_number >= 3): ?>
                                                <div class="col-lg-3">
                                                    <label class="mb-2"><?php echo e($item); ?> :</label>
                                                    <input required type="text" class="form-control"
                                                           name="c_q_option[<?php echo e($i); ?>][<?php echo e($item); ?>]">
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <div class="separator separator-dashed my-5"></div>
                                <?php endfor; ?>
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

            <?php if(isset($data_count['match']) && $data_count['match'] > 0): ?>
                <div class="tab-pane" id="kt_tabs_1_3" role="tabpanel">
                    <form enctype="multipart/form-data" id="match_form"
                          class="form-data"
                          action="<?php echo e(route('manager.lesson.assessment.update', [$lesson->id, 3])); ?>"
                          method="post">
                        <?php echo csrf_field(); ?>
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
                                        <input type="hidden" name="mark[<?php echo e($m_question->id); ?>]" value="9">
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                            <?php if($m_question->getFirstMediaUrl('imageQuestion')): ?>
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="<?php echo e($m_question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="<?php echo e($m_question->getFirstMediaUrl('imageQuestion')); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
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
                                                <?php if($match->getFirstMediaUrl('match')): ?>
                                                    <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                        <a data-id="<?php echo e($match->id); ?>" class="btn btn-icon btn-danger deleteMatchImageRecord" style="height: 20px; width: 20px">
                                                            <i class="la la-close la-2"></i>
                                                        </a>
                                                        <a href="<?php echo e($match->getFirstMediaUrl('match')); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <?php for($i=0; $i<$data_count['match']; $i++): ?>
                                <div class="form-group row">
                                    <div class="col-lg-9">
                                        <label class="text-info">س <?php echo e($i + 1); ?>:</label>
                                        <input required class="form-control"
                                               name="m_question[<?php echo e($i); ?>]"
                                               type="text">
                                        <input type="hidden" name="mark[<?php echo e($i); ?>]" value="9">

                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2">مرفق :</label>
                                        <input type="file" name="m_q_attachment[<?php echo e($i); ?>]"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <label class="mb-2">خيار 1:</label>
                                        <input required class="form-control"
                                               name="m_q_option[<?php echo e($i); ?>][]"
                                               type="text">
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="mb-2">الإجابة:</label>
                                        <input required class="form-control"
                                               name="m_q_answer[<?php echo e($i); ?>][]"
                                               type="text">
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="mb-2">صورة:</label>
                                        <input class="form-control" name="m_q_image[<?php echo e($i); ?>][]"
                                               type="file">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <label class="mb-2">خيار 2:</label>
                                        <input required class="form-control"
                                               name="m_q_option[<?php echo e($i); ?>][]"
                                               type="text">
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="mb-2">الإجابة:</label>
                                        <input required class="form-control"
                                               name="m_q_answer[<?php echo e($i); ?>][]"
                                               type="text">
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="mb-2">صورة:</label>
                                        <input class="form-control" name="m_q_image[<?php echo e($i); ?>][]"
                                               type="file">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <?php $__currentLoopData = range(1,3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="form-group row mt-2">
                                                <div class="col-lg-8">
                                                    <label class="mb-2"><?php echo e(t('Option')); ?>  <?php echo e($item); ?>:</label>
                                                    <input required class="form-control" name="m_q_option[<?php echo e($i); ?>][<?php echo e($item); ?>]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label class="mb-2"><?php echo e(t('Answer')); ?> :</label>
                                                    <input required class="form-control" name="m_q_answer[<?php echo e($i); ?>][<?php echo e($item); ?>]"
                                                           type="text">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label class="mb-2"><?php echo e(t('Image')); ?> :</label>
                                                    <input class="form-control" name="m_q_image[<?php echo e($i); ?>][<?php echo e($item); ?>]"
                                                           type="file">
                                                </div>
                                            </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </div>
                            <?php endfor; ?>
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
                        let id = $(this).data("id");
                        let url = '<?php echo e(route('manager.lesson.remove_a_question_attachment', ':id')); ?>'.replace(':id', id);
                        let parent = $(this).parent()
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

                    $(document).on('click', '.deleteMatchImageRecord', (function () {
                        let id = $(this).data("id");
                        let url = '<?php echo e(route('manager.lesson.remove_a_match_image', ':id')); ?>'.replace(':id', id);
                        let parent = $(this).parent()
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

                });
            </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/lesson/assessment/new_skills_assessment.blade.php ENDPATH**/ ?>
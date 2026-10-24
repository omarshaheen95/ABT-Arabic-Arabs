<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('manager.story.index')); ?>"><?php echo e(t('Stories')); ?></a>
    </li>
    <li class="breadcrumb-item">
        <?php echo e(t('Story Assessment')); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('title'); ?>
    <?php echo e(t('Story Assessment')); ?> : <?php echo e($story->name); ?>- <?php echo e($story->grade); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <?php if($true_false_count): ?>
                <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab"  type="button" role="tab"
                           data-bs-target="#kt_tabs_1_1"><?php echo e(t('True or False')); ?> </a>
                    </li>
            <?php endif; ?>
            <?php if($chose_count): ?>
                <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_2" type="button" role="tab"
                           ><?php echo e(t('Choose the Answer')); ?></a>
                    </li>
            <?php endif; ?>
            <?php if($match_count): ?>
                <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_3" type="button" role="tab"><?php echo e(t('Matching')); ?></a>
                    </li>
            <?php endif; ?>
            <?php if($sort_count): ?>
                <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kt_tabs_1_4" type="button" role="tab"><?php echo e(t('Sorting Words')); ?></a>
                    </li>
            <?php endif; ?>
        </ul>
        <div class="tab-content">
            <?php if($true_false_count): ?>
                <div class="tab-pane fade show active" id="kt_tabs_1_1" role="tabpanel">
                    <form enctype="multipart/form-data" id="true_false_form"
                          class="form-data"
                          action="<?php echo e(count($t_f_questions) > 0 ? route('manager.story.assessment.update', [$story->id, 1]):route('manager.story.storeAssessment', [$story->id, 1])); ?>"
                          method="post">
                       <?php echo csrf_field(); ?>
                        <?php if(count($t_f_questions)): ?>
                            <?php
                                $i = 1;
                            ?>
                            <?php $__currentLoopData = $t_f_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t_f_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group row mt-2">
                                    <div class="col-lg-7">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                        <input required class="form-control"
                                               name="t_f_question[<?php echo e($t_f_question->id); ?>]"
                                               type="text"
                                               value="<?php echo e($t_f_question->content); ?>">

                                    </div>
                                    <div class="col-lg-2">
                                        <label class="mb-2"><?php echo e(t('Correct Answer')); ?> :</label>
                                        <div class="d-flex gap-1">
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input required class="form-check-input" type="radio"
                                                       <?php echo e(optional($t_f_question->trueFalse)->result == 1 ? 'checked':''); ?>

                                                       value="1" name="t_f[<?php echo e($t_f_question->id); ?>]" id="flexRadioLg"/>
                                                <label class="form-check-label" for="flexRadioLg">
                                                    <?php echo e(t('True')); ?>

                                                </label>
                                            </div>
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input required
                                                       class="form-check-input"
                                                       <?php echo e(optional($t_f_question->trueFalse)->result == 0 ? 'checked':''); ?>

                                                       type="radio" value="0" name="t_f[<?php echo e($t_f_question->id); ?>]">
                                                <label class="form-check-label" for="flexRadioLg">
                                                    <?php echo e(t('False')); ?>

                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                            <?php if($t_f_question->attachment): ?>
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="<?php echo e($t_f_question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="<?php echo e(asset($t_f_question->attachment)); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                        <input type="file" name="t_f_q_attachment[<?php echo e($t_f_question->id); ?>]" class="form-control">
                                    </div>
                                </div>
                                <?php
                                    $i ++;
                                ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <?php for($i = 1; $i<=$true_false_count;$i++): ?>
                                <div class="form-group row">
                                    <div class="col-lg-7">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                        <input required class="form-control" name="t_f_question[<?php echo e($i); ?>]"
                                               type="text">

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

            <?php if($chose_count): ?>
                <div class="tab-pane fade" id="kt_tabs_1_2" role="tabpanel">
                    <form enctype="multipart/form-data" id="choose_form"
                          class="form-data"
                          action="<?php echo e(count($c_questions) > 0 ? route('manager.story.assessment.update', [$story->id, 2]):route('manager.story.storeAssessment', [$story->id, 2])); ?>"
                          method="post">
                        <?php echo csrf_field(); ?>
                        <?php if(count($c_questions)): ?>
                            <?php $__currentLoopData = $c_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group row mt-2">
                                    <div class="col-lg-6">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($loop->index+1); ?>:</label>
                                        <input required class="form-control"
                                               name="c_question[<?php echo e($c_question->id); ?>]" type="text"
                                               value="<?php echo e($c_question->content); ?>">
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2"><?php echo e(t('Correct Answer')); ?> :</label>
                                        <div class="d-flex gap-2">
                                            <?php $__currentLoopData = $c_question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                    <input required <?php echo e($option->result == 1 ? 'checked':''); ?> class="form-check-input" type="radio"
                                                           value="<?php echo e($option->id); ?>"
                                                           name="c_q_a[<?php echo e($c_question->id); ?>]">
                                                    <label class="form-check-label" for="flexRadioLg">
                                                        <?php echo e($loop->index+1); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                            <?php if($c_question->attachment): ?>
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="<?php echo e($c_question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="<?php echo e(asset($c_question->attachment)); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                        <input type="file" name="c_q_attachment[<?php echo e($c_question->id); ?>]" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <?php
                                        $o_counter = 1;
                                    ?>
                                    <?php $__currentLoopData = $c_question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-lg-3">
                                            <label class="mb-2"><?php echo e($o_counter); ?> :</label>
                                            <input required type="text" class="form-control"
                                                   name="c_q_option[<?php echo e($c_question->id); ?>][<?php echo e($option->id); ?>]"
                                                   value="<?php echo e($option->content); ?>">
                                        </div>
                                        <?php
                                            $o_counter ++;
                                        ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div class="separator separator-dashed my-5"></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <?php for($i = 1; $i<=$chose_count;$i++): ?>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                        <input required class="form-control" name="c_question[<?php echo e($i); ?>]"
                                               type="text">
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2"><?php echo e(t('Correct Answer')); ?> :</label>
                                        <div class="d-flex gap-3">
                                            <?php $__currentLoopData = range(1,3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                    <input required  class="form-check-input" type="radio"
                                                           value="<?php echo e($item); ?>"
                                                           name="c_q_a[<?php echo e($i); ?>]">
                                                    <label class="form-check-label" for="flexRadioLg">
                                                        <?php echo e($item); ?>

                                                    </label>
                                                </div>
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
                                    <?php $__currentLoopData = range(1,3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-lg-3">
                                            <label class="mb-2"><?php echo e($item); ?> :</label>
                                            <input required type="text" class="form-control"
                                                   name="c_q_option[<?php echo e($i); ?>][<?php echo e($item); ?>]">
                                        </div>
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

            <?php if($match_count): ?>
                <div class="tab-pane" id="kt_tabs_1_3" role="tabpanel">
                    <form enctype="multipart/form-data" id="match_form"
                          class="form-data"
                          action="<?php echo e(count($m_questions) ? route('manager.story.assessment.update', [$story->id, 3]):route('manager.story.storeAssessment', [$story->id, 3])); ?>"
                          method="post">
                        <?php echo csrf_field(); ?>

                        <?php if(count($m_questions)): ?>

                            <?php $__currentLoopData = $m_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group row">
                                    <div class="col-lg-9">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($loop->index+1); ?>:</label>
                                        <input required class="form-control"
                                               name="m_question[<?php echo e($m_question->id); ?>]" type="text"
                                               value="<?php echo e($m_question->content); ?>">
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                            <?php if($m_question->attachment): ?>
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="<?php echo e($m_question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="<?php echo e(asset($m_question->attachment)); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                        <input type="file" name="m_q_attachment[<?php echo e($m_question->id); ?>]" class="form-control">
                                    </div>
                                </div>

                                <?php $__currentLoopData = $m_question->matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-group row mt-2">
                                        <div class="col-lg-8">
                                            <label class="mb-2"><?php echo e(t('Option')); ?>  <?php echo e($loop->index+1); ?>:</label>
                                            <input required class="form-control"
                                                   name="m_q_option[<?php echo e($match->id); ?>]"
                                                   value="<?php echo e($match->content); ?>" type="text">
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="mb-2"><?php echo e(t('Answer')); ?> :</label>
                                            <input required class="form-control"
                                                   name="m_q_answer[<?php echo e($match->id); ?>]"
                                                   value="<?php echo e($match->result); ?>" type="text">
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="d-flex">
                                                <label class="mb-2"><?php echo e(t('Image')); ?> :</label>
                                                <?php if($match->image): ?>
                                                    <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                        <a data-id="<?php echo e($match->id); ?>" class="btn btn-icon btn-danger deleteMatchImageRecord" style="height: 20px; width: 20px">
                                                            <i class="la la-close la-2"></i>
                                                        </a>
                                                        <a href="<?php echo e(asset($match->image)); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                            <i class="la la-eye la-2"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                            <input type="file" name="m_q_image[<?php echo e($match->id); ?>]" class="form-control">
                                        </div>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="separator separator-dashed my-5"></div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <?php for($i=1;$i<=$match_count;$i++): ?>
                                <div class="form-group row">
                                    <div class="col-lg-9">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                        <input required class="form-control" name="m_question[<?php echo e($i); ?>]"
                                               type="text">
                                        <input type="hidden" name="mark[<?php echo e($i); ?>]" value="<?php echo e($mark); ?>">

                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2"><?php echo e(t('Attachment')); ?>  :</label>
                                        <input type="file" name="m_q_attachment[<?php echo e($i); ?>]"
                                               class="form-control">
                                    </div>
                                </div>
                                <?php for($y=1;$y<=$match_option;$y++): ?>
                                    <div class="form-group row mt-2">
                                        <div class="col-lg-8">
                                            <label class="mb-2"><?php echo e(t('Option')); ?>  <?php echo e($y); ?>:</label>
                                            <input required class="form-control" name="m_q_option[<?php echo e($i); ?>][<?php echo e($y); ?>]"
                                                   type="text">
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="mb-2"><?php echo e(t('Answer')); ?> :</label>
                                            <input required class="form-control" name="m_q_answer[<?php echo e($i); ?>][<?php echo e($y); ?>]"
                                                   type="text">
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="mb-2"><?php echo e(t('Image')); ?> :</label>
                                            <input class="form-control" name="m_q_image[<?php echo e($i); ?>][<?php echo e($y); ?>]"
                                                   type="file">
                                        </div>
                                    </div>
                                <?php endfor; ?>
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

            <?php if($sort_count): ?>
                <div class="tab-pane" id="kt_tabs_1_4" role="tabpanel">
                    <form enctype="multipart/form-data" id="sort_form"
                          class="form-data"
                          action="<?php echo e(count($s_questions) > 0 ? route('manager.story.assessment.update', [$story->id, 4]):route('manager.story.storeAssessment', [$story->id, 4])); ?>"
                          method="post">
                        <?php echo csrf_field(); ?>
                        <?php if(count($s_questions)): ?>

                            <?php $__currentLoopData = $s_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <div class="form-group row mb-2">
                                    <div class="col-lg-9">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($loop->index+1); ?>:</label>
                                        <input required class="form-control"
                                               name="s_question[<?php echo e($s_question->id); ?>]" type="text"
                                               value="<?php echo e($s_question->content); ?>">
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="d-flex">
                                            <label class="mb-2"><?php echo e(t('Attachment')); ?> :</label>
                                            <?php if($s_question->attachment): ?>
                                                <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                                    <a data-id="<?php echo e($s_question->id); ?>" class="btn btn-icon btn-danger deleteRecord" style="height: 20px; width: 20px">
                                                        <i class="la la-close la-2"></i>
                                                    </a>
                                                    <a href="<?php echo e(asset($s_question->attachment)); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                                        <i class="la la-eye la-2"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                        <input type="file" name="s_q_attachment[<?php echo e($s_question->id); ?>]" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row text-success" id="row-<?php echo e($s_question->id); ?>">
                                    <?php
                                        $o_counter = 1;
                                    ?>
                                    <?php $__currentLoopData = $s_question->sort_words; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sort_word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-4 mb-3 option">
                                            <label class="mb-2"><?php echo e(t('Option')); ?>  <?php echo e($loop->index+1); ?> :
                                                <a href="#"
                                                   data-id="<?php echo e($sort_word->id); ?>"
                                                   class="text-danger delete_old_input"><?php echo e(t('Delete')); ?> </a></label>
                                            <input required class="form-control"
                                                   name="s_q_option[<?php echo e($s_question->id); ?>][<?php echo e($sort_word->id); ?>]"
                                                   value="<?php echo e($sort_word->content); ?>" type="text">
                                        </div>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-1 d-flex align-items-center mt-5">
                                        <button type="button" data-id="<?php echo e($s_question->id); ?>"
                                                id="add_label_<?php echo e($s_question->id); ?>"
                                                class="btn btn-danger btn-sm btn-icon btn-block add_button"><i
                                                class="fa fa-plus"></i>
                                        </button>
                                    </div>

                                    <div class="separator separator-dashed my-5"></div>
                                </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>

                            <?php for($i=1;$i<=$sort_count;$i++): ?>
                                <div class="form-group row mb-2">
                                    <div class="col-lg-9">
                                        <label class="text-dark fw-bold"><?php echo e(t('Q')); ?>  <?php echo e($i); ?>:</label>
                                        <input required class="form-control" name="s_question[<?php echo e($i); ?>]"
                                               type="text">

                                    </div>
                                    <div class="col-lg-3">
                                        <label class="mb-2"><?php echo e(t('Attachment')); ?>  :</label>
                                        <input type="file" name="s_q_attachment[<?php echo e($i); ?>]"
                                               class="form-control">
                                    </div>

                                </div>
                                <div class="form-group row" id="row-<?php echo e($i); ?>">
                                    <?php $__currentLoopData = range(1,2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-lg-4 mt-3 option">
                                            <label class="mb-2"><?php echo e(t('Option').$item); ?>:</label>
                                            <input required class="form-control option" name="s_q_option[<?php echo e($i); ?>][<?php echo e($item); ?>]" type="text">
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-1 d-flex align-items-center mt-3">
                                        <button type="button" data-id="<?php echo e($i); ?>"
                                                id="add_label_<?php echo e($i); ?>"
                                                class="btn btn-danger btn-icon btn-block add_button"><i
                                                class="fa fa-plus"></i>
                                        </button>
                                    </div>

                                    <div class="separator separator-dashed my-5"></div>
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

        <div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="deleteModel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo e(t('Confirm Delete')); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form method="post" action="" id="delete_attachment_form">
                        <?php echo e(csrf_field()); ?>

                        <div class="modal-body">
                            <h5><?php echo e(t('Are You Sure To Delete The Selected Record ?')); ?></h5>
                            <br/>
                            <p><?php echo e(t('Deleting The Record Will Delete All Records Related To It')); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal"><?php echo e(t('Cancel')); ?></button>
                            <button type="submit" class="btn btn-warning"><?php echo e(t('Delete')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteSortWord" tabindex="-1" role="dialog" aria-labelledby="deleteSortWord"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="exampleModalLabel"><?php echo e(t('Confirm Delete')); ?> <?php echo e(t('Sort Answer')); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form method="post" action="" id="delete_sort_word_form">
                        <?php echo e(csrf_field()); ?>

                        <div class="modal-body">
                            <h5><?php echo e(t('Are You Sure To Delete The Selected Record ?')); ?></h5>
                            <br/>
                            <p><?php echo e(t('Deleting The Record Will Delete All Records Related To It')); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal"><?php echo e(t('Cancel')); ?></button>
                            <button type="submit" class="btn btn-warning"><?php echo e(t('Delete')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteMatchImageModel" tabindex="-1" role="dialog" aria-labelledby="deleteMatchImageModel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo e(t('Confirm Delete')); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form method="post" action="" id="delete_match_attachment_form">
                        <?php echo e(csrf_field()); ?>

                        <div class="modal-body">
                            <h5><?php echo e(t('Are You Sure To Delete The Selected Record ?')); ?></h5>
                            <br />
                            <p><?php echo e(t('Deleting The Record Will Delete All Records Related To It')); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(t('Cancel')); ?></button>
                            <button type="submit" class="btn btn-warning"><?php echo e(t('Delete')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
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
                        let url = '<?php echo e(route('manager.story.remove_attachment', ':id')); ?>'.replace(':id', id);
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
                    $(document).on('click', '.delete_old_input', (function () {
                        let id = $(this).data("id");
                        let url = '<?php echo e(route('manager.story.remove_sort_word', ':id')); ?>'.replace(':id', id);
                        let parent = $(this).parent().parent()
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
                        let id = $(this).data("id");
                        let url = '<?php echo e(route('manager.story.remove_match_attachment', ':id')); ?>'.replace(':id', id);
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
                                `<div class="col-lg-4 mb-3 option">
                                <label class="mb-2"><?php echo e(t('Option')); ?>${y} : <a href='#' class='text-danger delete_input'><?php echo e(t('Delete')); ?> </a></label>
                                <input required class="form-control" name="s_q_option[${row_id}][${y}]" type="text">`)
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

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/story/assessment.blade.php ENDPATH**/ ?>
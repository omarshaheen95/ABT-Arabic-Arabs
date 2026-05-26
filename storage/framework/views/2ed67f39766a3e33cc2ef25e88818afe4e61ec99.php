
<?php $__env->startSection('title'); ?>
    <?php echo e(t('Text Translation')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted">
        <?php echo e(t('Text Translation')); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <input type="text" class="form-control mb-2" id="searchInput" onkeyup="searchDiv()"
           placeholder="<?php echo e(t('Search')); ?>">
    <ul class="nav nav-tabs nav-fill" role="tablist">
        <?php $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="nav-item">
                <a class="nav-link <?php if($loop->first): ?> active <?php endif; ?>" data-bs-toggle="tab"
                   href="#kt_tabs_<?php echo e($key); ?>"><?php echo e($key); ?></a>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>

    <div class="tab-content" id="tabContent">
        <?php $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l_key => $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="tab-pane <?php if($loop->first): ?> active <?php endif; ?>" id="kt_tabs_<?php echo e($l_key); ?>" role="tabpanel">
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <?php $__currentLoopData = $folder['files']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n_key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(str_replace('.php', '', basename($file)) !== 'validation'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if($loop->first): ?> active <?php endif; ?>" data-bs-toggle="tab"
                                   href="#kt_tabs_<?php echo e($l_key); ?>_<?php echo e($n_key); ?>"><?php echo e(basename($file)); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <div class="tab-content">
                    <?php $__currentLoopData = $folder['files']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n_key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(str_replace('.php', '', basename($file)) !== 'validation'): ?>
                            <?php
                                $translations = \Illuminate\Support\Facades\File::getRequire($file);
                            ?>
                            <div class="tab-pane <?php if($loop->first): ?> active <?php endif; ?>"
                                 id="kt_tabs_<?php echo e($l_key); ?>_<?php echo e($n_key); ?>" role="tabpanel">
                                <form class="kt-form kt-form--label-right"
                                      action="<?php echo e(route('manager.text_translation.update', [$l_key, basename($file)])); ?>"
                                      method="post">
                                    <?php echo csrf_field(); ?>
                                    <div class="kt-portlet__body">
                                        <div class="form-group row">
                                            <?php if(is_array($translations)): ?>
                                                <?php $__currentLoopData = $translations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t_key => $v_key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-lg-4 mt-4 trans-div">
                                                        <label class="mb-2"><?php echo e($t_key); ?>:</label>
                                                        <textarea name="<?php echo e($t_key); ?>"
                                                                  class="form-control"><?php echo e($v_key); ?></textarea>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row my-5">
                                        <div class="separator separator-content my-4"></div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary mr-2"><?php echo e(t('Submit')); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        function searchDiv() {
            var input, filter, tabs, transDivs, transDiv, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value;
            tabs = document.getElementById("tabContent");
            transDivs = tabs.getElementsByClassName("trans-div");
            for (i = 0; i < transDivs.length; i++) {
                transDiv = transDivs[i].getElementsByTagName("textarea")[0];
                if (transDiv) {
                    txtValue = transDiv.textContent || transDiv.innerText;
                    if (txtValue.indexOf(filter) > -1) {
                        transDivs[i].style.display = "";
                    } else {
                        transDivs[i].style.display = "none";
                    }
                }
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/translation_text/index.blade.php ENDPATH**/ ?>
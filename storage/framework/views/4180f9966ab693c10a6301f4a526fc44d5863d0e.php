<?php $__env->startSection('title'); ?>
    <?php echo e($title??'Unknown'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted"><?php echo e(t('settings')); ?></li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <form class="form" id="form_data" action="<?php echo e(route('manager.settings.updateSettings')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <!-- Group settings by their group -->
                    <?php
                        $groupedSettings = $settings->groupBy('group');
                    ?>

                    <?php $__currentLoopData = $groupedSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $groupSettings): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-8">
                            <h3 class="fs-4 fw-bold mb-4 text-capitalize"><?php echo e(t('' . $group)); ?></h3>

                            <?php $__currentLoopData = $groupSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row mb-5">
                                    <div class="col-lg-4">
                                        <label class="fs-6 fw-semibold form-label"><?php echo e($setting->name); ?></label>
                                    </div>
                                    <div class="col-lg-8">
                                        <?php switch($setting->type):
                                            case ('text'): ?>
                                                <input type="text" class="form-control"
                                                       name="settings[<?php echo e($setting->key); ?>]"
                                                       value="<?php echo e($setting->value); ?>"
                                                       placeholder="<?php echo e($setting->name); ?>">
                                                <?php break; ?>

                                            <?php case ('password'): ?>
                                                <input type="password" class="form-control"
                                                       name="settings[<?php echo e($setting->key); ?>]"
                                                       value="<?php echo e($setting->value); ?>"
                                                       placeholder="<?php echo e($setting->name); ?>">
                                                <?php break; ?>

                                            <?php case ('number'): ?>
                                                <input type="number" class="form-control"
                                                       name="settings[<?php echo e($setting->key); ?>]"
                                                       value="<?php echo e($setting->value); ?>"
                                                       placeholder="<?php echo e($setting->name); ?>">
                                                <?php break; ?>

                                            <?php case ('textarea'): ?>
                                                <textarea class="form-control"
                                                          name="settings[<?php echo e($setting->key); ?>]"
                                                          rows="4"
                                                          placeholder="<?php echo e($setting->name); ?>"><?php echo e($setting->value); ?></textarea>
                                                <?php break; ?>

                                            <?php case ('file'): ?>
                                                <div class="d-flex align-items-center">
                                                    <div class="input-group">
                                                        <input type="file" class="form-control"
                                                               name="settings[<?php echo e($setting->key); ?>]">
                                                    </div>

                                                    <?php if($setting->value): ?>
                                                        <div class="ms-3">
                                                            <?php if(Str::contains($setting->value, ['.jpg', '.jpeg', '.png', '.gif', '.svg'])): ?>
                                                                <a href="<?php echo e(asset($setting->value)); ?>" target="_blank">
                                                                    <img src="<?php echo e(asset($setting->value)); ?>" alt="<?php echo e($setting->name); ?>"
                                                                         class="img-thumbnail" style="max-height: 50px;">
                                                                </a>
                                                            <?php else: ?>
                                                                <a href="<?php echo e(asset($setting->value)); ?>" target="_blank" class="btn btn-sm btn-light-primary">
                                                                    <i class="ki-duotone ki-eye fs-5">
                                                                        <i class="path1"></i>
                                                                        <i class="path2"></i>
                                                                        <i class="path3"></i>
                                                                    </i>
                                                                    <?php echo e(t('view_file')); ?>

                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php break; ?>

                                            <?php case ('select'): ?>
                                                <select class="form-control form-select"
                                                        name="settings[<?php echo e($setting->key); ?>]"
                                                        data-control="select2"
                                                        data-placeholder="<?php echo e(t('select')); ?>">
                                                    <option></option>
                                                    <?php $__currentLoopData = json_decode($setting->options ?? '[]', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionKey => $optionValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($optionKey); ?>" <?php echo e($setting->value == $optionKey ? 'selected' : ''); ?>>
                                                            <?php echo e($optionValue); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <?php break; ?>

                                            <?php case ('multi_select'): ?>
                                                <select class="form-control form-select"
                                                        name="settings[<?php echo e($setting->key); ?>][]"
                                                        data-control="select2"
                                                        data-placeholder="<?php echo e(t('select')); ?>"
                                                        multiple>
                                                    <?php $__currentLoopData = json_decode($setting->options ?? '[]', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionKey => $optionValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($optionKey); ?>"
                                                            <?php echo e(in_array($optionKey, json_decode($setting->value ?? '[]', true) ?: []) ? 'selected' : ''); ?>>
                                                            <?php echo e($optionValue); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <?php break; ?>

                                            <?php case ('radio'): ?>
                                                <div class="d-flex flex-wrap gap-5 mt-2">
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio"
                                                               name="settings[<?php echo e($setting->key); ?>]"
                                                               value="1"
                                                               id="<?php echo e($setting->key); ?>_enabled"
                                                            <?php echo e($setting->value == '1' ? 'checked' : ''); ?>>
                                                        <label class="form-check-label" for="<?php echo e($setting->key); ?>_enabled">
                                                            <?php echo e(t('enabled')); ?>

                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio"
                                                               name="settings[<?php echo e($setting->key); ?>]"
                                                               value="0"
                                                               id="<?php echo e($setting->key); ?>_disabled"
                                                            <?php echo e($setting->value == '0' ? 'checked' : ''); ?>>
                                                        <label class="form-check-label" for="<?php echo e($setting->key); ?>_disabled">
                                                            <?php echo e(t('disabled')); ?>

                                                        </label>
                                                    </div>
                                                </div>
                                                <?php break; ?>

                                            <?php case ('checkbox'): ?>
                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="settings[<?php echo e($setting->key); ?>]"
                                                           value="1"
                                                           id="<?php echo e($setting->key); ?>_checkbox"
                                                        <?php echo e($setting->value == '1' ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="<?php echo e($setting->key); ?>_checkbox">
                                                        <?php echo e(t('enabled')); ?>

                                                    </label>
                                                </div>
                                                <?php break; ?>

                                            <?php case ('color'): ?>
                                                <div class="input-group">
                                                    <input type="color" class="form-control form-control-color w-100px"
                                                           name="settings[<?php echo e($setting->key); ?>]"
                                                           value="<?php echo e($setting->value); ?>"
                                                           title="<?php echo e(t('choose_color')); ?>">
                                                </div>
                                                <?php break; ?>

                                            <?php case ('date'): ?>
                                                <input type="date" class="form-control"
                                                       name="settings[<?php echo e($setting->key); ?>]"
                                                       value="<?php echo e($setting->value); ?>">
                                                <?php break; ?>

                                            <?php default: ?>
                                                <input type="text" class="form-control"
                                                       name="settings[<?php echo e($setting->key); ?>]"
                                                       value="<?php echo e($setting->value); ?>"
                                                       placeholder="<?php echo e($setting->name); ?>">
                                        <?php endswitch; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if(!$loop->last): ?>
                                <div class="separator separator-dashed my-8"></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="reset" class="btn btn-secondary me-3"><?php echo e(t('Cancel')); ?></button>
                            <button type="submit" class="btn btn-primary"><?php echo e(t('Save Changes')); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            // Initialize any select2 dropdowns
            $('select[data-control="select2"]').select2({
                minimumResultsForSearch: 10
            });

            // Show filename on file input change
            $('input[type="file"]').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                if (fileName) {
                    $(this).next('.custom-file-label').text(fileName);
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/setting/general.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title'); ?>
    <?php echo e(isset($achievementLevel) ? t('Edit Achievement Level') : t('Add Achievement Level')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('manager.achievement_levels.index')); ?>">
            <?php echo e(t('Achievement Levels')); ?>

        </a>
    </li>

    <li class="breadcrumb-item text-muted">
        <?php echo e(isset($achievementLevel) ? t('Edit Achievement Level') : t('Add Achievement Level')); ?>

    </li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">

                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="<?php echo e(isset($achievementLevel) ? route('manager.achievement_levels.update', $achievementLevel->id): route('manager.achievement_levels.store')); ?>"
                      method="post">
                    <?php echo csrf_field(); ?>
                    <?php if(isset($achievementLevel)): ?>
                        <input type="hidden" name="_method" value="patch">
                    <?php endif; ?>

                    <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label for="name" class="form-label"><?php echo e(t('Name')); ?></label>
                                    <input type="text" id="" name="name" class="form-control"
                                           placeholder="<?php echo e(t('Name')); ?>"
                                           value="<?php echo e(isset($achievementLevel) ? $achievementLevel->name : old("name")); ?>"
                                           required>
                                </div>
                            </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="required_points" class="form-label"><?php echo e(t('Required Points')); ?> <span class="text-danger">*</span></label>
                                <input type="number" id="required_points" name="required_points" class="form-control"
                                       placeholder="<?php echo e(t('Enter required points')); ?>"
                                       value="<?php echo e(isset($achievementLevel) ? $achievementLevel->required_points : old('required_points')); ?>"
                                       min="0" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="badge_icon" class="form-label">
                                    <?php echo e(t('Badge Icon')); ?>

                                    <?php if(!isset($achievementLevel)): ?>
                                        <span class="text-danger">*</span>
                                    <?php endif; ?>
                                </label>
                                <input type="file" id="badge_icon" name="badge_icon" class="form-control"
                                       accept="image/*" <?php echo e(!isset($achievementLevel) ? 'required' : ''); ?>>
                                <small class="form-text text-muted"><?php echo e(t('Accepted formats: jpeg, png, jpg, gif, svg. Max size: 2MB')); ?></small>

                                <?php if(isset($achievementLevel) && $achievementLevel->badge_icon): ?>
                                    <div class="mt-2">
                                        <img src="<?php echo e(asset($achievementLevel->badge_icon)); ?>" alt="Current Badge" style="width: 60px; height: 60px;">
                                        <small class="d-block text-muted"><?php echo e(t('Current badge icon')); ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="description" class="form-label"><?php echo e(t('Description')); ?></label>
                                <textarea id="description" name="description" class="form-control" rows="4"
                                          placeholder="<?php echo e(t('Enter achievement level description')); ?>"><?php echo e(isset($achievementLevel) ? $achievementLevel->description : old('description')); ?></textarea>
                                <small class="form-text text-muted"><?php echo e(t('Maximum 1000 characters')); ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="row my-5">
                        <div class="separator separator-content my-4"></div>
                        <div class="col-12 d-flex justify-content-end">
                            <a href="<?php echo e(route('manager.achievement_levels.index')); ?>" class="btn btn-secondary me-3"><?php echo e(t('Cancel')); ?></a>
                            <button type="submit" class="btn btn-primary">
                                <?php echo e(isset($achievementLevel) ? t('Update') : t('Create')); ?>

                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\AchievementLevelRequest::class, '#form_information'); ?>


    <script>
        // Preview image on file select
        document.getElementById('badge_icon').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Remove existing preview if any
                    const existingPreview = document.querySelector('.image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }

                    // Create new preview
                    const preview = document.createElement('div');
                    preview.className = 'image-preview mt-2';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="width: 60px; height: 60px;">
                        <small class="d-block text-muted"><?php echo e(t('Preview')); ?></small>
                    `;

                    // Insert after the file input
                    document.getElementById('badge_icon').parentNode.appendChild(preview);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/achievement_level/edit.blade.php ENDPATH**/ ?>
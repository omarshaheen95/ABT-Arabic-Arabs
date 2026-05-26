<div class="row col-12">
    <input type="hidden" name="student[<?php echo e($row->id); ?>][row_num]" value="<?php echo e($row->row_num); ?>">
    <?php $__currentLoopData = $inputs_with_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $input): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($input['key'] == 'Arab' || $input['key'] == 'Citizen' || $input['key'] == 'SEN' || $input['key'] == 'G&T'|| $input['key'] == 'Active'): ?>
            <div class="col-sm-6 col-md-4">
                <label class="text-info"><?php echo e($input['key']); ?></label>
                <select name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                        data-name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                        class="form-control from-input-data form-select"
                        data-control="select2"
                        data-placeholder="<?php echo e(t('Select '.$input['key'])); ?>">
                    <option value="" disabled selected><?php echo e(t('Select Year')); ?></option>
                    <option value="1" <?php echo e($input['value'] == 1 ? 'selected':''); ?>><?php echo e(t('Yes')); ?></option>
                    <option value="0" <?php echo e($input['value'] == 0 && $input['value'] != null ? 'selected':''); ?>><?php echo e(t('No')); ?></option>
                </select>
            </div>
        <?php elseif($input['key'] == 'Gender'): ?>
            <div class="col-sm-6 col-md-4">
                <label class="text-info"><?php echo e($input['key']); ?></label>
                <select name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                        data-name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                        class="form-control from-input-data form-select"
                        data-control="select2"
                        data-placeholder="<?php echo e(t('Select '.$input['key'])); ?>">
                    <option value="" disabled selected><?php echo e(t('Select Year')); ?></option>
                    <option value="Boy" <?php echo e($input['value'] == 'Boy' ? 'selected':''); ?>><?php echo e(t('Boy')); ?></option>
                    <option value="Girl" <?php echo e($input['value'] == 'Girl' ? 'selected':''); ?>><?php echo e(t('Girl')); ?></option>
                </select>
            </div>
        <?php elseif($input['key'] == 'Teacher'): ?>
            <div class="col-sm-6 col-md-4">
                <label class="text-info"><?php echo e($input['key']); ?></label>
                <select name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                        data-name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                        class="form-control from-input-data form-select"
                        data-control="select2"
                        data-placeholder="<?php echo e(t('Select '.$input['key'])); ?>">
                   <option></option>
                    <?php if(isset($teachers)): ?>
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($teacher->id); ?>" <?php if(isset($input['value']) && $teacher->email==$input['value']): ?> selected <?php endif; ?>><?php echo e($teacher->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            </div>
        <?php elseif($input['key'] == 'Name' ): ?>
            <div class="col-sm-6 col-md-4">
                <div class="form-group">
                    <label class="text-info"><?php echo e(t('Name')); ?></label>
                    <input type="text"
                           name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                           data-name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                           class="form-control name remove_spaces from-input-data" placeholder="<?php echo e(t('Name')); ?>"
                           value="<?php echo e($input['value']); ?>" required>
                </div>
            </div>

        <?php elseif($input['key'] == 'Grade' || $input['key'] == 'Alternative Grade'): ?>
            <div class="col-sm-6 col-md-4">
                <label class="text-info"><?php echo e($input['key']); ?></label>
                <select name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                        data-name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                        class="form-control from-input-data form-select"
                        data-control="select2"
                        data-placeholder="<?php echo e(t('Select '.$input['key'])); ?>">
                    <option></option>
                        <?php if(isset($grades)): ?>
                        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($grade->id); ?>" <?php if(isset($input['value']) && $input['value']==$grade->id): ?> selected <?php endif; ?>><?php echo e($grade->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                </select>
            </div>

            <?php elseif($input['key'] == 'Email' ): ?>
                <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                        <label class="text-info"><?php echo e($input['key']); ?></label>
                        <div class="input-group mb-5">
                            <input dir="ltr"
                                   name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                                   data-name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                                   type="text"
                                   placeholder="<?php echo e(t($input['key'])); ?>"
                                   value="<?php echo e($input['value']); ?>"
                                   class="form-control username from-input-data" aria-describedby="basic-addon1"/>
                            <span class="input-group-text" id="basic-addon1">
                     <a class="p-0 cursor-pointer generateUserName" data-id="<?php echo e($row->id); ?>"><i class="fas fa-refresh"></i></a>
                 </span>
                        </div>
                    </div>
                </div>
        <?php elseif($input['key'] == 'Password' ): ?>
            <div class="col-sm-6 col-md-4">
                <label class="text-info"><?php echo e($input['key']); ?></label>
                <input required name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                       data-name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                       type="text" value="123456"
                       class="form-control from-input-data from-input-data">
            </div>
        <?php else: ?>
            <div class="col-sm-6 col-md-4">
                <label class="text-info"><?php echo e($input['key']); ?></label>
                <input required name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                       data-name="student[<?php echo e($row->id); ?>][<?php echo e(str_replace(' ', '_', strtolower($input['key']))); ?>]"
                       type="text" value="<?php echo e($input['value']); ?>"
                       class="form-control from-input-data from-input-data">
            </div>
        <?php endif; ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/import/logs/student_data_form.blade.php ENDPATH**/ ?>
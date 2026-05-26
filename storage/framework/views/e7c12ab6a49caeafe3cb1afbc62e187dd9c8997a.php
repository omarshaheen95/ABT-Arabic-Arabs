<div class="dropdown">
    <button class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php if(isset($menu_title)): ?>
            <?php echo e(t($menu_title)); ?>

        <?php else: ?>
            <?php echo e(t('Actions')); ?>

        <?php endif; ?>
        <i class="ki-duotone ki-down fs-5 ms-1"></i>
    </button>
    <ul class="dropdown-menu">
        <?php if(isset($actions)): ?>
            <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($action): ?>

                    

                    <?php if(isset($action['permission'])): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($action['permission'])): ?>
                            <?php if($action['key'] == 'delete'): ?>
                                <li><button type="button" class="dropdown-item text-danger delete_row <?php if(isset($action['class'])): ?> <?php echo e($action['class']); ?> <?php endif; ?>" data-id="<?php echo e($action['route']); ?>" ><?php echo e($action['name']); ?></button></li>
                            <?php elseif($action['key'] == 'event'): ?>
                                <li><button type="button" class="dropdown-item <?php if(isset($action['class'])): ?> <?php echo e($action['class']); ?> <?php endif; ?>" data-id="<?php echo e($action['route']); ?>" ><?php echo e($action['name']); ?></button></li>
                            <?php else: ?>
                                <li><a class="dropdown-item <?php if(isset($action['class'])): ?> <?php echo e($action['class']); ?> <?php endif; ?>" <?php if($action['key']=='login'||$action['key']=='blank'): ?> target="_blank" <?php endif; ?> <?php if(isset($action['onclick'])): ?> onclick="<?php echo e($action['onclick']); ?>" <?php endif; ?> href="<?php echo e($action['route']); ?>"><?php echo e($action['name']); ?></a></li>
                            <?php endif; ?>
                        <?php endif; ?>

                    <?php else: ?>
                        <?php if($action['key'] == 'delete'): ?>
                            <li><button type="button" class="dropdown-item text-danger delete_row <?php if(isset($action['class'])): ?> <?php echo e($action['class']); ?> <?php endif; ?>" data-id="<?php echo e($action['route']); ?>" ><?php echo e($action['name']); ?></button></li>
                        <?php elseif($action['key'] == 'event'): ?>
                            <li><button type="button" class="dropdown-item <?php if(isset($action['class'])): ?> <?php echo e($action['class']); ?> <?php endif; ?>" data-id="<?php echo e($action['route']); ?>" ><?php echo e($action['name']); ?></button></li>
                        <?php else: ?>
                            <li><a class="dropdown-item <?php if(isset($action['class'])): ?> <?php echo e($action['class']); ?> <?php endif; ?>" <?php if($action['key']=='login'||$action['key']=='blank'): ?> target="_blank" <?php endif; ?> <?php if(isset($action['onclick'])): ?> onclick="<?php echo e($action['onclick']); ?>" <?php endif; ?> href="<?php echo e($action['route']); ?>"><?php echo e($action['name']); ?></a></li>

                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </ul>
</div>













































<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/action_menu.blade.php ENDPATH**/ ?>
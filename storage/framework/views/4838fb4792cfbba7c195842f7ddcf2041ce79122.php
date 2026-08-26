<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user_assets/css/pages/ranking.css')); ?>?v=2" />
<?php $__env->stopPush(); ?>
<?php $__env->startSection('page-name', 'ranking'); ?>

<?php $__env->startSection('content'); ?>
    <section class="ranking-content">
        <div class="competition-banner">
            <div class="ranking-badges-grid">
                <?php $__currentLoopData = $achievementLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $currentIndex = $achievementLevels->search(function($item) use ($currentAchievementLevel) {
                        return $currentAchievementLevel && $item->id == $currentAchievementLevel->id;
                    });
                    $isActive = $currentAchievementLevel && $currentAchievementLevel->id == $level->id;
                    $isCompleted = $currentIndex !== false && $index < $currentIndex;
                    $isLocked = $currentIndex !== false && $index > $currentIndex;
                ?>
                <div class="ranking-badge <?php echo e($isActive ? 'active' : ''); ?> <?php echo e($isCompleted ? 'completed' : ''); ?> <?php echo e($isLocked ? 'locked' : ''); ?>" role="listitem">
                    <img
                        src="<?php echo e($level->badge_icon ? asset($level->badge_icon) : asset('user_assets/images/illustrations/badge.svg')); ?>"
                        alt="<?php echo e($level->name); ?>"
                    />
                    <div class="badge-tooltip">
                        <div class="tooltip-content">
                            <h4 class="tooltip-level-name"><?php echo e($level->name); ?></h4>
                            <p class="tooltip-points"><?php echo e($level->required_points); ?> XP</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="banner-info">
                <h1 class="competition-title"><?php echo e($currentAchievementLevel ? $currentAchievementLevel->name : t('No Level')); ?></h1>
                <p class="competition-description">يتأهل أفضل ثلاثة إلى المستوى التالي</p>

            </div>
        </div>
        <div class="ranking-container">
            <div class="participants-list">
                <?php $__empty_1 = true; $__currentLoopData = $usersInSameLevel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $userLevel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isCurrentUser = $userLevel->user_id == auth()->id();
                        $rank = $index + 1;
                        $medalIcons = [
                            1 => 'first.svg',
                            2 => 'second.svg',
                            3 => 'third.svg'
                        ];
                    ?>

                    <article class="participant-card <?php echo e($isCurrentUser ? 'participant-card--current-user' : ''); ?>">
                        <div class="participant-info">
                            <?php if($rank <= 3): ?>
                            <div class="medal-container">
                                <img class="medal-icon" src="<?php echo e(asset('user_assets/images/illustrations/' . $medalIcons[$rank])); ?>" alt="Rank <?php echo e($rank); ?>" />
                            </div>
                            <?php endif; ?>

                            <?php if($isCurrentUser): ?>
                                <div class="user-avatar <?php echo e(in_array($rank,range(1,3))?'rank-'.$rank:'rank-other'); ?>" style="background-image: url('<?php echo e($userLevel->user->image ? asset($userLevel->user->image) : asset('user_assets/images/illustrations/avatar.svg')); ?>')">
                                </div>
                                <h3 class="participant-name-current"><?php echo e($userLevel->user->name ?? 'غير معروف'); ?></h3>
                            <?php else: ?>
                                <div class="user-avatar <?php echo e(in_array($rank,range(1,3))?'rank-'.$rank:'rank-other'); ?>" style="background-image: url('<?php echo e($userLevel->user->image ? asset($userLevel->user->image) : asset('user_assets/images/illustrations/avatar.svg')); ?>')"></div>
                                <h3 class="participant-name<?php echo e($rank==1?'-first':''); ?>"><?php echo e($userLevel->user->name ?? 'غير معروف'); ?></h3>
                            <?php endif; ?>
                        </div>
                        <span class="<?php echo e($isCurrentUser ? 'participant-score-current' : 'participant-score'); ?>"><?php echo e($userLevel->points); ?> XP</span>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>لا يوجد طلاب في هذا المستوى حتى الآن.</p>
                <?php endif; ?>
            </div>

        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('user_assets/js/pages/ranking.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/ranking/index.blade.php ENDPATH**/ ?>
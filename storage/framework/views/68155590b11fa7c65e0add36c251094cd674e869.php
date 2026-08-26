<?php if(isset($paginator) && $paginator->hasPages()): ?>
<div class="pagination-container">
    
    <?php if($paginator->onFirstPage()): ?>
        <button class="pagination-btn disabled" disabled>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
    <?php else: ?>
        <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="pagination-btn">
           <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </a>
    <?php endif; ?>

    
    <?php $__currentLoopData = $paginator->getUrlRange(1, $paginator->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($page == $paginator->currentPage()): ?>
            <button class="pagination-btn active"><?php echo e($page); ?></button>
        <?php else: ?>
            <a href="<?php echo e($url); ?>" class="pagination-btn"><?php echo e($page); ?></a>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <?php if($paginator->hasMorePages()): ?>
        <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="pagination-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </a>
    <?php else: ?>
        <button class="pagination-btn disabled" disabled>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
    <?php endif; ?>
</div>
<?php endif; ?>


<?php $__env->startPush('style'); ?>
    <style>
        /* Pagination Styles */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 32px;
            padding: 16px 0;
        }

        .pagination-btn {
            min-width: 40px;
            height: 40px;
            padding: 8px 12px;
            border: 2px solid #C8F5DC;
            background: white;
            border-radius: 12px;
            font-family: var(--font-family);
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-border-radius: 12px;
            -moz-border-radius: 12px;
            -ms-border-radius: 12px;
            -o-border-radius: 12px;
        }

        .pagination-btn:hover:not(.disabled):not(.active) {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(19, 137, 68, 0.2);
        }

        .pagination-btn.active {
            background: linear-gradient(89.73deg, #138944 -1.48%, #34D399 95.8%);
            border-color: transparent;
            color: white;
            pointer-events: none;
        }

        .pagination-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination-btn svg {
            width: 20px;
            height: 20px;
        }

        .pagination-ellipsis {
            padding: 8px 4px;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 600;
            user-select: none;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/general/components/pagination.blade.php ENDPATH**/ ?>
<?php if($paginator->hasPages()): ?>
    <ul class="zPagination-one pt-77">
        
        <?php if($paginator->onFirstPage()): ?>
            <li>
                <button class="z-link disabled"><i class="fa-solid fa-angles-left"></i></button>
            </li>
        <?php else: ?>
            <li>
                <a class="z-link" href="<?php echo e($paginator->previousPageUrl()); ?>"><i class="fa-solid fa-angles-left"></i></a>
            </li>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php if(is_string($element)): ?>
                <li>
                    <button class="z-link border-0 disabled"><?php echo e($element); ?></button>
                </li>
            <?php endif; ?>

            
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <li>
                            <button class="z-link active"><?php echo e($page); ?></button>
                        </li>
                    <?php else: ?>
                        <li><a class="z-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <li>
                <a class="z-link" href="<?php echo e($paginator->nextPageUrl()); ?>"><i class="fa-solid fa-angles-right"></i></a>
            </li>
        <?php else: ?>
            <li>
                <button class="z-link disabled"><i class="fa-solid fa-angles-right"></i></button>
            </li>
        <?php endif; ?>
    </ul>
<?php endif; ?>

<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\pagination\custom.blade.php ENDPATH**/ ?>
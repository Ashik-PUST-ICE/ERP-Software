<?php
    $totalPages = ceil($total / $perPage);
    $start = max(1, $page - 2); // show 2 pages before current
    $end = min($totalPages, $page + 2); // show 2 pages after current
    $useLinks = isset($paginationUrl) && $paginationUrl;
    $baseUrl = $useLinks ? $paginationUrl : '#';
?>

<?php if($totalPages > 1): ?>
    <div class="d-flex justify-content-center mt-20 tablePagi">
        <div class="dataTables_paginate paging_simple_numbers">
            
            <?php if($useLinks): ?>
                <a class="paginate_button previous <?php echo e($page == 1 ? 'disabled' : ''); ?>"
                   href="<?php echo e($page > 1 ? $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=' . ($page - 1) : '#'); ?>"
                   <?php if($page == 1): ?> aria-disabled="true" <?php endif; ?> role="link">
                    <i class="fa-solid fa-angles-left"></i>
                </a>
            <?php else: ?>
                <a class="paginate_button previous <?php echo e($page == 1 ? 'disabled' : 'ajax-page'); ?>"
                   data-page="<?php echo e($page > 1 ? $page - 1 : ''); ?>" role="link">
                    <i class="fa-solid fa-angles-left"></i>
                </a>
            <?php endif; ?>

            
            <?php if($start > 1): ?>
                <?php if($useLinks): ?>
                    <a class="paginate_button" href="<?php echo e($baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=1'); ?>" role="link">1</a>
                <?php else: ?>
                    <a class="paginate_button ajax-page" data-page="1" role="link">1</a>
                <?php endif; ?>
                <?php if($start > 2): ?>
                    <span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>
                <?php endif; ?>
            <?php endif; ?>

            
            <?php for($p = $start; $p <= $end; $p++): ?>
                <?php if($page == $p): ?>
                    <span>
                    <a class="paginate_button current" aria-current="page" role="link" <?php if($useLinks): ?> href="<?php echo e($baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=' . $p); ?>" <?php else: ?> data-page="<?php echo e($p); ?>" <?php endif; ?>><?php echo e($p); ?></a>
                </span>
                <?php else: ?>
                    <?php if($useLinks): ?>
                        <a class="paginate_button" href="<?php echo e($baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=' . $p); ?>" role="link"><?php echo e($p); ?></a>
                    <?php else: ?>
                        <a class="paginate_button ajax-page" data-page="<?php echo e($p); ?>" role="link"><?php echo e($p); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endfor; ?>

            
            <?php if($end < $totalPages): ?>
                <?php if($end < $totalPages - 1): ?>
                    <span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>
                <?php endif; ?>
                <?php if($useLinks): ?>
                    <a class="paginate_button" href="<?php echo e($baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=' . $totalPages); ?>" role="link"><?php echo e($totalPages); ?></a>
                <?php else: ?>
                    <a class="paginate_button ajax-page" data-page="<?php echo e($totalPages); ?>" role="link"><?php echo e($totalPages); ?></a>
                <?php endif; ?>
            <?php endif; ?>

            
            <?php if($useLinks): ?>
                <a class="paginate_button next <?php echo e($page == $totalPages ? 'disabled' : ''); ?>"
                   href="<?php echo e($page < $totalPages ? $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=' . ($page + 1) : '#'); ?>"
                   <?php if($page == $totalPages): ?> aria-disabled="true" <?php endif; ?> role="link">
                    <i class="fa-solid fa-angles-right"></i>
                </a>
            <?php else: ?>
                <a class="paginate_button next <?php echo e($page == $totalPages ? 'disabled' : 'ajax-page'); ?>"
                   data-page="<?php echo e($page < $totalPages ? $page + 1 : ''); ?>" role="link">
                    <i class="fa-solid fa-angles-right"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\pagination\common-pagination.blade.php ENDPATH**/ ?>
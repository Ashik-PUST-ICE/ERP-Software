<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title><?php echo e(__('Email Template')); ?></title>
    <meta name="description" content="Email Template.">
</head>

<body>
<div>
    <table cellspacing="0">
        <tr>
            <td>
                <table>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?php echo e(env('APP_URL')); ?>" title="<?php echo e(__('logo')); ?>" target="_blank">
                                <img src="<?php echo e(getFileUrl(getOption('app_logo'))); ?>"/>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo e($message); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php if(getOption('app_copyright') !=null): ?>
                    <p> <strong><?php echo e(getOption('app_copyright')); ?></strong> </p>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
</div>
</body>

</html>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\mail\single-email-notify.blade.php ENDPATH**/ ?>
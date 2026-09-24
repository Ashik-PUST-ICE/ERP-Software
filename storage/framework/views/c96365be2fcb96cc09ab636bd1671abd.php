<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('Payroll')); ?> - <?php echo e($month); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
            color: #1b1c17;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            color: #837775;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        .card {
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .card-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .card-icon i {
            color: #fff !important;
            font-size: 14px;
        }
        .card-info h2 {
            font-size: 16px;
            font-weight: 700;
            color: #1b1c17;
            margin-bottom: 2px;
        }
        .card-info h3 {
            font-size: 11px;
            color: #837775;
            font-weight: 400;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
        }
        .badge-paid {
            background: #d1e7dd;
            color: #0f5132;
        }
        .badge-unpaid {
            background: #fff3cd;
            color: #664d03;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo e(__('Payroll')); ?></h1>
        <p><?php echo e(__('Month')); ?>: <?php echo e($month); ?></p>
    </div>

    <div class="cards">
        <div class="card">
            <div class="card-icon" style="background: #4778c7;">
                <i class="fa-solid fa-money-bill"></i>
            </div>
            <div class="card-info">
                <h2><?php echo e(showPrice($summary['total_basic'])); ?></h2>
                <h3><?php echo e(__('Total Basic')); ?></h3>
            </div>
        </div>
        <div class="card">
            <div class="card-icon" style="background: #0FA958;">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div class="card-info">
                <h2><?php echo e(showPrice($summary['total_allowances'])); ?></h2>
                <h3><?php echo e(__('Total Allowances')); ?></h3>
            </div>
        </div>
        <div class="card">
            <div class="card-icon" style="background: #FF6B35;">
                <i class="fa-solid fa-circle-minus"></i>
            </div>
            <div class="card-info">
                <h2><?php echo e(showPrice($summary['total_deductions'])); ?></h2>
                <h3><?php echo e(__('Total Deductions')); ?></h3>
            </div>
        </div>
        <div class="card">
            <div class="card-icon" style="background: #FFC402;">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <div class="card-info">
                <h2><?php echo e(showPrice($summary['total_net'])); ?></h2>
                <h3><?php echo e(__('Net Payroll')); ?></h3>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo e(__('Employee')); ?></th>
                <th><?php echo e(__('Department')); ?></th>
                <th><?php echo e(__('Basic')); ?></th>
                <th><?php echo e(__('Allowances')); ?></th>
                <th><?php echo e(__('Deductions')); ?></th>
                <th><?php echo e(__('Net Salary')); ?></th>
                <th><?php echo e(__('Status')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $payrolls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payroll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($payroll->employee->first_name); ?> <?php echo e($payroll->employee->last_name); ?></td>
                    <td><?php echo e($payroll->employee->department->name ?? 'N/A'); ?></td>
                    <td><?php echo e(showPrice($payroll->basic_salary)); ?></td>
                    <td><?php echo e(showPrice($payroll->allowances)); ?></td>
                    <td><?php echo e(showPrice($payroll->deductions)); ?></td>
                    <td><?php echo e(showPrice($payroll->net_salary)); ?></td>
                    <td>
                        <?php if($payroll->payment_status == PAYMENT_STATUS_PAID): ?>
                            <span class="badge badge-paid"><?php echo e(__('Paid')); ?></span>
                        <?php else: ?>
                            <span class="badge badge-unpaid"><?php echo e(__('Unpaid')); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer">
        <p><?php echo e(__('Generated')); ?>: <?php echo e(now()->format('Y-m-d H:i')); ?></p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\hrm\payroll\print.blade.php ENDPATH**/ ?>
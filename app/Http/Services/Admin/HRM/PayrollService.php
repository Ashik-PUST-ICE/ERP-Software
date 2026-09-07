<?php

namespace App\Http\Services\Admin\HRM;

use App\Models\HRM\HrmEmployee;
use App\Models\HRM\Payroll;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    use ResponseTrait;

    public function generate($month)
    {
        DB::beginTransaction();
        try {
            $employees = HrmEmployee::where('status', EMPLOYEE_STATUS_ACTIVE)->get();
            $generated = 0;

            foreach ($employees as $employee) {
                $exists = Payroll::where('payroll_month', $month)->where('employee_id', $employee->id)->exists();
                if (!$exists) {
                    $allowances = $employee->basic_salary * 0.20; // 20% allowance
                    $deductions = $employee->basic_salary * 0.05; // 5% deduction
                    Payroll::create([
                        'payroll_month' => $month,
                        'employee_id' => $employee->id,
                        'basic_salary' => $employee->basic_salary,
                        'allowances' => $allowances,
                        'deductions' => $deductions,
                        'net_salary' => $employee->basic_salary + $allowances - $deductions,
                        'payment_status' => PAYMENT_STATUS_PENDING,
                    ]);
                    $generated++;
                }
            }
            DB::commit();
            return $this->success([], "Payroll generated for {$generated} employees for {$month}.");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function markPaid($id, $payment_method)
    {
        try {
            $payroll = Payroll::findOrFail($id);
            $payroll->update([
                'payment_status' => PAYMENT_STATUS_PAID,
                'payment_date' => now()->toDateString(),
                'payment_method' => $payment_method ?? 'Bank Transfer',
            ]);
            return $this->success([], 'Payroll marked as paid.');
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function bulkPay($month)
    {
        try {
            Payroll::where('payroll_month', $month)
                ->where('payment_status', PAYMENT_STATUS_PENDING)
                ->update([
                    'payment_status' => PAYMENT_STATUS_PAID,
                    'payment_date' => now()->toDateString(),
                    'payment_method' => 'Bank Transfer',
                ]);
            return $this->success([], 'All pending payrolls for ' . $month . ' marked as paid.');
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

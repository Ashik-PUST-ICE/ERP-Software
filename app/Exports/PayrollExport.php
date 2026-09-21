<?php

namespace App\Exports;

use App\Models\HRM\Payroll;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayrollExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private readonly string $month) {}

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Payroll::with('employee.department')
            ->where('payroll_month', $this->month)
            ->orderBy('id', 'DESC');
    }

    public function headings(): array
    {
        return ['SL', 'Employee', 'Department', 'Basic Salary', 'Allowances', 'Deductions', 'Net Salary', 'Payment Status', 'Payment Date', 'Payment Method'];
    }

    public function map($payroll): array
    {
        return [
            $payroll->id,
            $payroll->employee->first_name . ' ' . $payroll->employee->last_name,
            $payroll->employee->department->name ?? 'N/A',
            $payroll->basic_salary,
            $payroll->allowances,
            $payroll->deductions,
            $payroll->net_salary,
            $payroll->payment_status == PAYMENT_STATUS_PAID ? 'Paid' : 'Unpaid',
            $payroll->payment_date ? $payroll->payment_date->format('Y-m-d') : '-',
            $payroll->payment_method ?? '-',
        ];
    }
}

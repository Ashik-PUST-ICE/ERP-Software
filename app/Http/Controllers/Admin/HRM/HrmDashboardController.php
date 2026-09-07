<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Attendance;
use App\Models\HRM\Department;
use App\Models\HRM\HrmEmployee;
use App\Models\HRM\LeaveRequest;
use App\Models\HRM\Payroll;

class HrmDashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = HrmEmployee::where('status', EMPLOYEE_STATUS_ACTIVE)->count();
        $todayPresent = Attendance::whereDate('date', today())->where('status', ATTENDANCE_STATUS_PRESENT)->count();
        $todayLate = Attendance::whereDate('date', today())->where('status', ATTENDANCE_STATUS_LATE)->count();
        $todayAbsent = $totalEmployees - Attendance::whereDate('date', today())->count();
        $pendingLeaves = LeaveRequest::where('status', LEAVE_STATUS_PENDING)->count();
        $totalDepartments = Department::where('status', EMPLOYEE_STATUS_ACTIVE)->count();

        $currentMonth = now()->format('Y-m');
        $monthlyPayroll = Payroll::where('payroll_month', $currentMonth)->sum('net_salary');

        $recentEmployees = HrmEmployee::with(['department', 'designation'])
            ->latest()
            ->take(5)
            ->get();

        $pendingLeaveList = LeaveRequest::with('employee')
            ->where('status', LEAVE_STATUS_PENDING)
            ->latest()
            ->take(5)
            ->get();

        $departmentStats = Department::withCount(['employees' => function ($q) {
            $q->where('status', EMPLOYEE_STATUS_ACTIVE);
        }])->where('status', EMPLOYEE_STATUS_ACTIVE)->get();

        $data = compact(
            'totalEmployees', 'todayPresent', 'todayLate', 'todayAbsent',
            'pendingLeaves', 'totalDepartments', 'monthlyPayroll',
            'recentEmployees', 'pendingLeaveList', 'departmentStats'
        );
        $data['title'] = __('HRM Dashboard');
        $data['activeHrm'] = 'active';
        $data['showHrmMenu'] = 'show';
        $data['showHRMMenu'] = 'show';
        $data['activeHrmDashboard'] = 'active';

        return view('admin.hrm.dashboard', $data);
    }
}

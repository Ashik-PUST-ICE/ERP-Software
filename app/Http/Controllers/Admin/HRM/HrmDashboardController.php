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

    public function data()
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
            ->get()
            ->map(function ($emp) {
                return [
                    'id' => $emp->id,
                    'full_name' => trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? '')),
                    'employee_code' => $emp->employee_code,
                    'department' => $emp->department->name ?? 'N/A',
                    'designation' => $emp->designation->name ?? 'N/A',
                    'status' => $emp->status,
                ];
            });

        $pendingLeaveList = LeaveRequest::with('employee')
            ->where('status', LEAVE_STATUS_PENDING)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($leave) {
                return [
                    'id' => $leave->id,
                    'employee' => trim(($leave->employee->first_name ?? '') . ' ' . ($leave->employee->last_name ?? '')),
                    'leave_type' => ucfirst($leave->leave_type),
                    'duration' => $leave->start_date . ' to ' . $leave->end_date . ' (' . $leave->days_count . ' Days)',
                    'status' => $leave->status,
                    'action' => '<a href="' . route('admin.hrm.leaves.approve', $leave->id) . '" class="zBadge zBadge-complete">Approve</a>',
                ];
            });

        $departmentStats = Department::withCount(['employees' => function ($q) {
            $q->where('status', EMPLOYEE_STATUS_ACTIVE);
        }])->where('status', EMPLOYEE_STATUS_ACTIVE)
            ->orderByDesc('employees_count')
            ->take(3)
            ->get()
            ->map(function ($dept) {
                return [
                    'name' => $dept->name,
                    'employees_count' => $dept->employees_count,
                ];
            });

        return response()->json([
            'totalEmployees' => $totalEmployees,
            'todayPresent' => $todayPresent,
            'todayLate' => $todayLate,
            'todayAbsent' => $todayAbsent,
            'pendingLeaves' => $pendingLeaves,
            'totalDepartments' => $totalDepartments,
            'monthlyPayroll' => showPrice($monthlyPayroll),
            'recentEmployees' => $recentEmployees,
            'pendingLeaveList' => $pendingLeaveList,
            'departmentStats' => $departmentStats,
            'notCheckedIn' => $totalEmployees - $todayPresent,
        ]);
    }
}

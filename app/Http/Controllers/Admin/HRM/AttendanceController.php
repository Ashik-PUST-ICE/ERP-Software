<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HRM\AttendanceRequest;
use App\Http\Requests\Admin\HRM\BulkAttendanceRequest;
use App\Http\Services\Admin\HRM\AttendanceService;
use App\Models\HRM\Attendance;
use App\Models\HRM\HrmEmployee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public $attendanceService;

    public function __construct()
    {
        $this->attendanceService = new AttendanceService;
    }

    public function index(Request $request)
    {
        $date = $request->get('date', today()->toDateString());
        $month = $request->get('month', now()->format('Y-m'));

        $employees = HrmEmployee::with(['attendances' => function ($q) use ($date) {
            $q->whereDate('date', $date);
        }, 'department'])->where('status', EMPLOYEE_STATUS_ACTIVE)->get();

        $stats = [
            'present' => Attendance::whereDate('date', $date)->where('status', ATTENDANCE_STATUS_PRESENT)->count(),
            'late' => Attendance::whereDate('date', $date)->where('status', ATTENDANCE_STATUS_LATE)->count(),
            'absent' => HrmEmployee::where('status', EMPLOYEE_STATUS_ACTIVE)->count() - Attendance::whereDate('date', $date)->count(),
            'on_leave' => Attendance::whereDate('date', $date)->where('status', ATTENDANCE_STATUS_ON_LEAVE)->count(),
        ];

        $data['title'] = __('Attendance');
        $data['activeHrm'] = 'active';
        $data['activeAttendance'] = 'active';
        $data['activeAttendances'] = 'active';
        $data['showHrmMenu'] = 'show';
        $data['showHRMMenu'] = 'show';
        $data['employees'] = $employees;
        $data['date'] = $date;
        $data['stats'] = $stats;
        $data['month'] = $month;

        return view('admin.hrm.attendance.index', $data);
    }

    public function markAttendance(AttendanceRequest $request)
    {
        $response = $this->attendanceService->markAttendance($request);
        if ($response->getData()->success) {
            return back()->with('success', $response->getData()->message);
        }
        return back()->with('error', $response->getData()->message);
    }

    public function bulkMark(BulkAttendanceRequest $request)
    {
        $response = $this->attendanceService->bulkMark($request);
        if ($response->getData()->success) {
            return back()->with('success', $response->getData()->message);
        }
        return back()->with('error', $response->getData()->message);
    }
}

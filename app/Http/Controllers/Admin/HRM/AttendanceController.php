<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HRM\AttendanceRequest;
use App\Http\Requests\Admin\HRM\BulkAttendanceRequest;
use App\Http\Services\Admin\HRM\AttendanceService;
use App\Models\HRM\Attendance;
use App\Models\HRM\Department;
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
        if ($request->ajax()) {
            $date = $request->get('date', today()->toDateString());
            
            $attendances = Attendance::with(['employee.department', 'employee.designation'])
                ->whereDate('date', $date)
                ->orderBy('id', 'DESC');

            if ($request->filled('status')) {
                $attendances->where('status', $request->status);
            }
            if ($request->filled('department_id')) {
                $attendances->whereHas('employee', function ($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            }

            return datatables($attendances)
                ->addIndexColumn()
                ->addColumn('sl', function ($data) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('employee_code', function ($data) {
                    return $data->employee->employee_code ?? 'N/A';
                })
                ->addColumn('full_name', function ($data) {
                    return $data->employee->full_name ?? 'N/A';
                })
                ->addColumn('department', function ($data) {
                    return $data->employee->department->name ?? 'N/A';
                })
                ->addColumn('check_in', function ($data) {
                    return $data->check_in ?? '—';
                })
                ->addColumn('check_out', function ($data) {
                    return $data->check_out ?? '—';
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == ATTENDANCE_STATUS_PRESENT) {
                        return '<div class="zBadge zBadge-complete">' . __("Present") . '</div>';
                    } elseif ($data->status == ATTENDANCE_STATUS_LATE) {
                        return '<div class="zBadge zBadge-warning">' . __("Late") . '</div>';
                    } elseif ($data->status == ATTENDANCE_STATUS_ABSENT) {
                        return '<div class="zBadge zBadge-deactive">' . __("Absent") . '</div>';
                    } elseif ($data->status == ATTENDANCE_STATUS_ON_LEAVE) {
                        return '<div class="zBadge zBadge-warning">' . __("On Leave") . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-warning">' . ucfirst($data->status) . '</div>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return '<button type="button" class="primary-btn btn-sm" onclick="markAttendance(' . $data->employee_id . ', \'' . ($data->date ? \Carbon\Carbon::parse($data->date)->format('Y-m-d') : '') . '\')">' . __("Mark") . '</button>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

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
        $data['departments'] = \App\Models\HRM\Department::where('status', STATUS_ACTIVE)->get();

        return view('admin.hrm.attendance.index', $data);
    }

    public function markAttendance(AttendanceRequest $request)
    {
        $response = $this->attendanceService->markAttendance($request);
        if ($response->getData()->status) {
            return back()->with('success', $response->getData()->message);
        }
        return back()->with('error', $response->getData()->message);
    }

    public function bulkMark(BulkAttendanceRequest $request)
    {
        $response = $this->attendanceService->bulkMark($request);
        if ($response->getData()->status) {
            return back()->with('success', $response->getData()->message);
        }
        return back()->with('error', $response->getData()->message);
    }
}

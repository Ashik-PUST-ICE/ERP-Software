<?php

namespace App\Http\Services\Admin\HRM;

use App\Models\HRM\Attendance;
use App\Models\HRM\HrmEmployee;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    use ResponseTrait;

    public function markAttendance($request)
    {
        try {
            Attendance::updateOrCreate(
                ['employee_id' => $request->employee_id, 'date' => $request->date],
                [
                    'check_in' => $request->check_in,
                    'check_out' => $request->check_out,
                    'status' => $request->status,
                    'notes' => $request->notes,
                ]
            );
            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function bulkMark($request)
    {
        DB::beginTransaction();
        try {
            $employees = HrmEmployee::where('status', EMPLOYEE_STATUS_ACTIVE)->get();
            foreach ($employees as $employee) {
                Attendance::updateOrCreate(
                    ['employee_id' => $employee->id, 'date' => $request->date],
                    ['status' => $request->status, 'check_in' => $request->status != ATTENDANCE_STATUS_ABSENT ? '09:00' : null]
                );
            }
            DB::commit();
            return $this->success([], 'Bulk attendance marked for ' . count($employees) . ' employees.');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }
}

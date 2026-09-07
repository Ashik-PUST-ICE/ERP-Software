<?php

namespace App\Http\Services\Admin\HRM;

use App\Models\HRM\LeaveRequest;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class LeaveService
{
    use ResponseTrait;

    public function store($request)
    {
        try {
            $data = $request->validated();
            $data['days_count'] = (int) now()->parse($data['start_date'])
                ->diffInDays(now()->parse($data['end_date'])) + 1;
            
            LeaveRequest::create($data);
            return $this->success([], 'Leave request submitted successfully.');
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $leave = LeaveRequest::findOrFail($id);
            $leave->update(['status' => LEAVE_STATUS_APPROVED, 'approved_by' => Auth::id()]);
            return $this->success([], 'Leave approved successfully.');
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function reject($id, $admin_note)
    {
        try {
            $leave = LeaveRequest::findOrFail($id);
            $leave->update([
                'status' => LEAVE_STATUS_REJECTED,
                'approved_by' => Auth::id(),
                'admin_note' => $admin_note,
            ]);
            return $this->success([], 'Leave rejected successfully.');
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

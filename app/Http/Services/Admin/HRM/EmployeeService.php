<?php

namespace App\Http\Services\Admin\HRM;

use App\Models\HRM\HrmEmployee;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            
            if ($request->id) {
                $employee = HrmEmployee::findOrFail($request->id);
                $employee->update($data);
                $msg = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                $data['employee_code'] = 'EMP-' . str_pad(HrmEmployee::count() + 1, 4, '0', STR_PAD_LEFT);
                HrmEmployee::create($data);
                $msg = getMessage(CREATED_SUCCESSFULLY);
            }
            DB::commit();
            return $this->success([], $msg);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $employee = HrmEmployee::findOrFail($id);
            $employee->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

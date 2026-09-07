<?php

namespace App\Http\Services\Admin\HRM;

use App\Models\HRM\Department;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class DepartmentService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                $department = Department::findOrFail($request->id);
                $department->update($request->validated());
                $msg = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                Department::create($request->validated());
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
            $department = Department::findOrFail($id);
            if ($department->employees()->exists()) {
                return $this->error([], 'Cannot delete department with active employees.');
            }
            $department->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

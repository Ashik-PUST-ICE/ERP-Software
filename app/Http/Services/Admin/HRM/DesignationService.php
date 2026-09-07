<?php

namespace App\Http\Services\Admin\HRM;

use App\Models\HRM\Designation;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class DesignationService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                $designation = Designation::findOrFail($request->id);
                $designation->update($request->validated());
                $msg = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                Designation::create($request->validated());
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
            $designation = Designation::findOrFail($id);
            if ($designation->employees()->exists()) {
                return $this->error([], 'Cannot delete designation with assigned employees.');
            }
            $designation->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

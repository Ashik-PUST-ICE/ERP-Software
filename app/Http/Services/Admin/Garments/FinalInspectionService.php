<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\FinalInspection;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class FinalInspectionService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                FinalInspection::findOrFail($request->id)->update($request->validated());
                $message = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                FinalInspection::create($request->validated());
                $message = getMessage(CREATED_SUCCESSFULLY);
            }

            DB::commit();
            return $this->success([], $message);
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->error([], $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            FinalInspection::findOrFail($id)->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], $exception->getMessage());
        }
    }
}

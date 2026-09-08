<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\Cutting;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class CuttingService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                Cutting::findOrFail($request->id)->update($request->validated());
                $message = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                Cutting::create($request->validated());
                $message = getMessage(CREATED_SUCCESSFULLY);
            }
            DB::commit();
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Cutting::findOrFail($id)->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

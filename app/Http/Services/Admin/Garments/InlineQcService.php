<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\InlineQc;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class InlineQcService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                InlineQc::findOrFail($request->id)->update($request->validated());
                $message = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                InlineQc::create($request->validated());
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
            InlineQc::findOrFail($id)->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], $exception->getMessage());
        }
    }
}

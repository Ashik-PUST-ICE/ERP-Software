<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\TnaTask;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class TnaTaskService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                $task = TnaTask::findOrFail($request->id);
                $task->update($request->validated());
                $message = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                TnaTask::create($request->validated());
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
            TnaTask::findOrFail($id)->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

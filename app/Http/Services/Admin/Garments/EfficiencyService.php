<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\Efficiency;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class EfficiencyService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['efficiency_percentage'] = (float) $data['target_output'] > 0
                ? ((float) $data['actual_output'] / (float) $data['target_output']) * 100
                : 0;
            if ($request->id) {
                Efficiency::findOrFail($request->id)->update($data);
                $message = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                Efficiency::create($data);
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
            Efficiency::findOrFail($id)->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\Costing;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class CostingService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $components = [
                'fabric_cost', 'trims_cost', 'accessories_cost', 'cm_cost',
                'washing_cost', 'printing_cost', 'embroidery_cost', 'overhead_cost', 'other_cost',
            ];
            $data['total_cost'] = collect($components)->sum(fn ($field) => (float) ($data[$field] ?? 0));
            $data['profit_amount'] = (float) $data['fob_price'] - $data['total_cost'];
            $data['profit_margin'] = (float) $data['fob_price'] > 0
                ? ($data['profit_amount'] / (float) $data['fob_price']) * 100
                : 0;

            if ($request->id) {
                $costing = Costing::findOrFail($request->id);
                $costing->update($data);
                $message = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                Costing::create($data);
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
            Costing::findOrFail($id)->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

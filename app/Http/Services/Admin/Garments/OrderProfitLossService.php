<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\OrderProfitLoss;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderProfitLossService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['total_cost'] = collect(['material_cost', 'production_cost', 'salary_cost', 'overhead_cost', 'other_cost'])->sum(fn ($field) => (float) $data[$field]);
            $data['profit_amount'] = (float) $data['sales_revenue'] - $data['total_cost'];
            $data['profit_margin'] = (float) $data['sales_revenue'] > 0 ? ($data['profit_amount'] / $data['sales_revenue']) * 100 : 0;
            $profitLoss = $request->id ? OrderProfitLoss::findOrFail($request->id) : new OrderProfitLoss();
            $profitLoss->fill($data);
            $profitLoss->save();
            DB::commit();
            return $this->success([], getMessage($request->id ? UPDATED_SUCCESSFULLY : CREATED_SUCCESSFULLY));
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->error([], $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            OrderProfitLoss::findOrFail($id)->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], $exception->getMessage());
        }
    }
}

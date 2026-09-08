<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\GarmentOrder;
use App\Models\Garments\PackingList;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class PackingListService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $packing = $request->id ? PackingList::findOrFail($request->id) : new PackingList();
            $order = GarmentOrder::findOrFail($request->order_id);
            $existing = (int) PackingList::where('order_id', $request->order_id)
                ->when($packing->exists, fn ($query) => $query->where('id', '<>', $packing->id))
                ->sum('quantity');

            if ($existing + (int) $request->quantity > $order->quantity) {
                DB::rollBack();
                return $this->error([], __('Total packed quantity cannot exceed the order quantity.'));
            }

            $packing->fill($request->validated());
            $packing->save();
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
            PackingList::findOrFail($id)->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], $exception->getMessage());
        }
    }
}

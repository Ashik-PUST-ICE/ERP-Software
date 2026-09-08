<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\FinishingEntry;
use App\Models\Garments\GarmentOrder;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class FinishingEntryService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $entry = $request->id ? FinishingEntry::findOrFail($request->id) : new FinishingEntry();
            $order = GarmentOrder::findOrFail($request->order_id);
            $existing = (int) FinishingEntry::where('order_id', $request->order_id)
                ->when($entry->exists, fn ($query) => $query->where('id', '<>', $entry->id))
                ->sum('received_quantity');

            if ($existing + (int) $request->received_quantity > $order->quantity) {
                DB::rollBack();
                return $this->error([], __('Total finishing quantity cannot exceed the order quantity.'));
            }

            $entry->fill($request->validated());
            $entry->save();
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
            FinishingEntry::findOrFail($id)->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], $exception->getMessage());
        }
    }
}

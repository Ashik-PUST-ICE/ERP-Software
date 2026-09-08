<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\Grn;
use App\Models\Garments\Material;
use App\Models\Garments\StockMovement;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class GrnService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['accepted_quantity'] = (float) $data['received_quantity'] - (float) $data['rejected_quantity'];

            if ($request->id) {
                $grn = Grn::findOrFail($request->id);
                $oldAccepted = (float) $grn->accepted_quantity;
                $oldMaterial = Material::findOrFail($grn->material_id);
                $oldMaterial->decrement('current_stock', $oldAccepted);
                $grn->update($data);
                $material = Material::findOrFail($data['material_id']);
            } else {
                $grn = Grn::create($data);
                $material = Material::findOrFail($grn->material_id);
            }

            $material->increment('current_stock', $data['accepted_quantity']);
            StockMovement::create(['material_id' => $material->id, 'warehouse_id' => $material->warehouse_id, 'movement_type' => $request->id ? 'grn_adjustment' : 'grn', 'quantity' => $data['accepted_quantity'], 'balance_after' => $material->fresh()->current_stock, 'reference_type' => 'GRN', 'reference_id' => $grn->id]);
            DB::commit();
            return $this->success([], getMessage($request->id ? UPDATED_SUCCESSFULLY : CREATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $grn = Grn::findOrFail($id);
            Material::findOrFail($grn->material_id)->decrement('current_stock', $grn->accepted_quantity);
            $grn->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\Material;
use App\Models\Garments\StoreIssue;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreIssueService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['net_quantity'] = (float) $data['issued_quantity'] - (float) $data['returned_quantity'];
            if ($request->id) {
                $issue = StoreIssue::findOrFail($request->id);
                $oldMaterial = Material::findOrFail($issue->material_id);
                $oldMaterial->increment('current_stock', $issue->net_quantity);
                $issue->update($data);
            } else {
                $issue = StoreIssue::create($data);
            }

            $material = Material::findOrFail($data['material_id']);
            if ((float) $material->current_stock < (float) $data['net_quantity']) {
                throw new Exception(__('Insufficient material stock for this issue.'));
            }

            $material->decrement('current_stock', $data['net_quantity']);
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
            $issue = StoreIssue::findOrFail($id);
            Material::findOrFail($issue->material_id)->increment('current_stock', $issue->net_quantity);
            $issue->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }
}

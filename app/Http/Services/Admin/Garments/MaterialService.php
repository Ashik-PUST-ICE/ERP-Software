<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\Material;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class MaterialService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                $material = Material::findOrFail($request->id);
                $material->update($request->validated());
                $message = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                Material::create($request->validated());
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
            Material::findOrFail($id)->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

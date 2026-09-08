<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\ShipmentDocument;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class ShipmentDocumentService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $document = $request->id ? ShipmentDocument::findOrFail($request->id) : new ShipmentDocument();
            $document->fill($request->validated());
            $document->save();
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
            ShipmentDocument::findOrFail($id)->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], $exception->getMessage());
        }
    }
}

<?php

namespace App\Http\Services\Admin\Garments;

use App\Models\Garments\Style;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class StyleService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                $style = Style::findOrFail($request->id);
                $style->update($request->validated());
                $message = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                Style::create($request->validated());
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
            $style = Style::findOrFail($id);
            if ($style->orders()->exists()) {
                return $this->error([], __('Cannot delete a style linked to orders.'));
            }

            $style->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

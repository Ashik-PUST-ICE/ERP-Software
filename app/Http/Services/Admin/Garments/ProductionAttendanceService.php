<?php
namespace App\Http\Services\Admin\Garments;
use App\Models\Garments\ProductionAttendance;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
class ProductionAttendanceService
{
    use ResponseTrait;
    public function store($request) { DB::beginTransaction(); try { $attendance = $request->id ? ProductionAttendance::findOrFail($request->id) : new ProductionAttendance(); $attendance->fill($request->validated()); $attendance->save(); DB::commit(); return $this->success([],getMessage($request->id?UPDATED_SUCCESSFULLY:CREATED_SUCCESSFULLY)); } catch(Exception $exception) { DB::rollBack(); return $this->error([],$exception->getMessage()); } }
    public function destroy($id) { try { ProductionAttendance::findOrFail($id)->delete(); return $this->success([],getMessage(DELETED_SUCCESSFULLY)); } catch(Exception $exception) { return $this->error([],$exception->getMessage()); } }
}

<?php
namespace App\Http\Services\Admin\Garments;
use App\Models\Garments\Incentive;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
class IncentiveService
{
    use ResponseTrait;
    public function store($request) { DB::beginTransaction(); try { $data = $request->validated(); $data['incentive_amount'] = (float) $data['production_quantity'] * (float) $data['piece_rate']; $incentive = $request->id ? Incentive::findOrFail($request->id) : new Incentive(); $incentive->fill($data); $incentive->save(); DB::commit(); return $this->success([], getMessage($request->id ? UPDATED_SUCCESSFULLY : CREATED_SUCCESSFULLY)); } catch (Exception $exception) { DB::rollBack(); return $this->error([], $exception->getMessage()); } }
    public function destroy($id) { try { Incentive::findOrFail($id)->delete(); return $this->success([], getMessage(DELETED_SUCCESSFULLY)); } catch (Exception $exception) { return $this->error([], $exception->getMessage()); } }
}

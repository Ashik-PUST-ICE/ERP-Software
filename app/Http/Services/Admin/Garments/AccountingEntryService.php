<?php
namespace App\Http\Services\Admin\Garments;
use App\Models\Garments\AccountingEntry;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
class AccountingEntryService
{
    use ResponseTrait;
    public function store($request) { DB::beginTransaction(); try { $entry = $request->id ? AccountingEntry::findOrFail($request->id) : new AccountingEntry(); $entry->fill($request->validated()); $entry->save(); DB::commit(); return $this->success([], getMessage($request->id ? UPDATED_SUCCESSFULLY : CREATED_SUCCESSFULLY)); } catch (Exception $exception) { DB::rollBack(); return $this->error([], $exception->getMessage()); } }
    public function destroy($id) { try { AccountingEntry::findOrFail($id)->delete(); return $this->success([], getMessage(DELETED_SUCCESSFULLY)); } catch (Exception $exception) { return $this->error([], $exception->getMessage()); } }
}

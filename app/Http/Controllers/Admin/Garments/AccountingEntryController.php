<?php
namespace App\Http\Controllers\Admin\Garments;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\AccountingEntryRequest;
use App\Http\Services\Admin\Garments\AccountingEntryService;
use App\Models\Garments\AccountingEntry;
use App\Models\Garments\GarmentOrder;
use Illuminate\Http\Request;
class AccountingEntryController extends Controller
{
    public function __construct(public AccountingEntryService $entryService) {}
    public function index(Request $request) { if ($request->ajax()) { return datatables(AccountingEntry::with('order')->latest('entry_date'))->addIndexColumn()->addColumn('sl',function(){static $count=0;return ++$count;})->addColumn('order_number',fn($row)=>e($row->order?->order_number??'N/A'))->addColumn('type_display',fn($row)=>__(garmentAccountingTypes()[$row->entry_type]??$row->entry_type))->addColumn('status',function($row){[$label,$class]=garmentAccountingStatuses()[$row->status]??['Unknown','zBadge-warning'];return '<div class="zBadge '.$class.'">'.__($label).'</div>'; })->addColumn('action',fn($row)=>'<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\''.route('admin.garments.accounting.edit',$row->id).'\', \'#edit-accounting-modal\')">'.__('Edit').'</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\''.route('admin.garments.accounting.destroy',$row->id).'\', \'garmentAccountingDataTable\')">'.__('Delete').'</a></li></ul></div></div>')->rawColumns(['status','action'])->make(true); } return view('admin.garments.accounting.index',['title'=>__('Accounting Integration'),'orders'=>GarmentOrder::latest()->get(),'activeGarments'=>'active','activeGarmentAccounting'=>'active','showGarmentsMenu'=>'show']); }
    public function store(AccountingEntryRequest $request){return $this->entryService->store($request);} public function edit($id){$entry=AccountingEntry::findOrFail($id);$orders=GarmentOrder::latest()->get();return view('admin.garments.accounting.form',compact('entry','orders'));} public function update(AccountingEntryRequest $request,$id){$request->merge(['id'=>$id]);return $this->entryService->store($request);} public function destroy($id){return $this->entryService->destroy($id);}
}

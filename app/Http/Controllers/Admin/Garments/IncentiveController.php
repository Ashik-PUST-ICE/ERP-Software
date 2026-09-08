<?php
namespace App\Http\Controllers\Admin\Garments;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\IncentiveRequest;
use App\Http\Services\Admin\Garments\IncentiveService;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\Incentive;
use Illuminate\Http\Request;
class IncentiveController extends Controller
{
    public function __construct(public IncentiveService $incentiveService) {}
    public function index(Request $request) { if ($request->ajax()) { return datatables(Incentive::with('order')->latest('production_date'))->addIndexColumn()->addColumn('sl',function(){static $count=0;return ++$count;})->addColumn('order_number',fn($row)=>e($row->order?->order_number??'N/A'))->addColumn('amount_display',fn($row)=>number_format((float)$row->incentive_amount,2))->addColumn('status',function($row){[$label,$class]=garmentIncentiveStatuses()[$row->status]??['Unknown','zBadge-warning'];return '<div class="zBadge '.$class.'">'.__($label).'</div>'; })->addColumn('action',fn($row)=>'<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\''.route('admin.garments.incentives.edit',$row->id).'\', \'#edit-incentive-modal\')">'.__('Edit').'</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\''.route('admin.garments.incentives.destroy',$row->id).'\', \'garmentIncentiveDataTable\')">'.__('Delete').'</a></li></ul></div></div>')->rawColumns(['status','action'])->make(true); } return view('admin.garments.incentives.index',['title'=>__('Piece-rate / Incentive Calculation'),'orders'=>GarmentOrder::latest()->get(),'activeGarments'=>'active','activeGarmentIncentive'=>'active','showGarmentsMenu'=>'show']); }
    public function store(IncentiveRequest $request){return $this->incentiveService->store($request);} public function edit($id){$incentive=Incentive::findOrFail($id);$orders=GarmentOrder::latest()->get();return view('admin.garments.incentives.form',compact('incentive','orders'));} public function update(IncentiveRequest $request,$id){$request->merge(['id'=>$id]);return $this->incentiveService->store($request);} public function destroy($id){return $this->incentiveService->destroy($id);}
}

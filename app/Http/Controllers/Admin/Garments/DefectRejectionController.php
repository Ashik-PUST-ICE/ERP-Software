<?php
namespace App\Http\Controllers\Admin\Garments;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\DefectRejectionRequest;
use App\Http\Services\Admin\Garments\DefectRejectionService;
use App\Models\Garments\DefectRejection;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\InlineQc;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
class DefectRejectionController extends Controller
{
 use ResponseTrait;
 public function __construct(public DefectRejectionService $defectService){}
 public function index(Request $request){if($request->ajax()){$rows=DefectRejection::with('order')->orderByDesc('reported_date')->orderByDesc('id');return datatables($rows)->addIndexColumn()->addColumn('sl',function($row){static $count=0;return ++$count;})->addColumn('order_number',fn($row)=>e($row->order?->order_number??'N/A'))->addColumn('quantity_display',fn($row)=>$row->defect_quantity.' / '.$row->rejected_quantity)->addColumn('date_display',fn($row)=>$row->reported_date?->format('d M Y')??'N/A')->addColumn('status',function($row){[$label,$class]=garmentDefectStatuses()[$row->status]??['Unknown','zBadge-warning'];return '<div class="zBadge '.$class.'">'.__($label).'</div>'; })->addColumn('action',function($row){return '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\''.route('admin.garments.defects.edit',$row->id).'\', \'#edit-defect-modal\')">'.__('Edit').'</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\''.route('admin.garments.defects.destroy',$row->id).'\', \'garmentDefectDataTable\')">'.__('Delete').'</a></li></ul></div></div>'; })->rawColumns(['status','action'])->make(true);}return view('admin.garments.defects.index',['title'=>__('Defect & Rejection Tracking'),'orders'=>GarmentOrder::orderByDesc('id')->get(),'qcs'=>InlineQc::orderByDesc('inspection_date')->get(),'activeGarments'=>'active','activeGarmentDefects'=>'active','showGarmentsMenu'=>'show']);}
 public function store(DefectRejectionRequest $request){return $this->defectService->store($request);} public function edit($id){$defect=DefectRejection::findOrFail($id);$orders=GarmentOrder::orderByDesc('id')->get();$qcs=InlineQc::orderByDesc('inspection_date')->get();return view('admin.garments.defects.form',compact('defect','orders','qcs'));} public function update(DefectRejectionRequest $request,$id){$request->merge(['id'=>$id]);return $this->defectService->store($request);} public function destroy($id){return $this->defectService->destroy($id);}
}

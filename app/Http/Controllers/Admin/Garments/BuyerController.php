<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\BuyerRequest;
use App\Http\Services\Admin\Garments\BuyerService;
use App\Models\Garments\Buyer;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    use ResponseTrait;

    public function __construct(public BuyerService $buyerService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $buyers = Buyer::query()->orderByDesc('id');

            return datatables($buyers)
                ->addIndexColumn()
                ->addColumn('sl', function ($buyer) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('status', function ($buyer) {
                    return $buyer->status == STATUS_ACTIVE
                        ? '<div class="zBadge zBadge-complete">' . __('Active') . '</div>'
                        : '<div class="zBadge zBadge-deactive">' . __('Deactivate') . '</div>';
                })
                ->addColumn('action', function ($buyer) {
                    return '<div class="inline-flex">
                        <div class="dropdown options-area">
                            <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.buyers.edit', $buyer->id) . '\', \'#edit-modal\')">' . __('Edit') . '</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.buyers.destroy', $buyer->id) . '\', \'buyerDataTable\')">' . __('Delete') . '</a></li>
                            </ul>
                        </div>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.garments.buyers.index', [
            'title' => __('Buyers'),
            'activeGarments' => 'active',
            'activeGarmentBuyers' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(BuyerRequest $request)
    {
        return $this->buyerService->store($request);
    }

    public function edit($id)
    {
        $buyer = Buyer::findOrFail($id);
        return view('admin.garments.buyers.form', compact('buyer'));
    }

    public function update(BuyerRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->buyerService->store($request);
    }

    public function destroy($id)
    {
        return $this->buyerService->destroy($id);
    }
}

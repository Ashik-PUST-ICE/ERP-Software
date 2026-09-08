<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\StyleRequest;
use App\Http\Services\Admin\Garments\StyleService;
use App\Models\Garments\Style;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class StyleController extends Controller
{
    use ResponseTrait;

    public function __construct(public StyleService $styleService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $styles = Style::query()->withCount('orders')->orderByDesc('id');

            return datatables($styles)
                ->addIndexColumn()
                ->addColumn('sl', function ($style) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('status', function ($style) {
                    return $style->status == STATUS_ACTIVE
                        ? '<div class="zBadge zBadge-complete">' . __('Active') . '</div>'
                        : '<div class="zBadge zBadge-deactive">' . __('Deactivate') . '</div>';
                })
                ->addColumn('action', function ($style) {
                    return '<div class="inline-flex"><div class="dropdown options-area">
                        <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.styles.edit', $style->id) . '\', \'#edit-style-modal\')">' . __('Edit') . '</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.styles.destroy', $style->id) . '\', \'garmentStyleDataTable\')">' . __('Delete') . '</a></li>
                        </ul>
                    </div></div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.garments.styles.index', [
            'title' => __('Styles'),
            'activeGarments' => 'active',
            'activeGarmentStyles' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(StyleRequest $request)
    {
        return $this->styleService->store($request);
    }

    public function edit($id)
    {
        $style = Style::findOrFail($id);
        return view('admin.garments.styles.form', compact('style'));
    }

    public function update(StyleRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->styleService->store($request);
    }

    public function destroy($id)
    {
        return $this->styleService->destroy($id);
    }
}

<?php
namespace App\Http\Controllers\Admin\Garments;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\MerchandiserTask;
use App\Models\Garments\BuyerCommunication;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class MerchandiserManagementController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        return view('admin.garments.merchandiser.management', [
            'title' => __('Merchandiser Assignments & Activities'),
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function orders()
    {
        return view('admin.garments.merchandiser.orders', [
            'title' => __('Order Assignments'),
            'orders' => GarmentOrder::with(['style', 'buyer'])->latest()->get(),
            'users' => User::where('status', STATUS_ACTIVE)->orderBy('name')->get(),
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function tasks()
    {
        return view('admin.garments.merchandiser.tasks', [
            'title' => __('Merchandiser Tasks'),
            'orders' => GarmentOrder::with(['style', 'buyer'])->latest()->get(),
            'users' => User::where('status', STATUS_ACTIVE)->orderBy('name')->get(),
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function communications()
    {
        return view('admin.garments.merchandiser.communications', [
            'title' => __('Buyer Communications'),
            'orders' => GarmentOrder::with(['style', 'buyer'])->latest()->get(),
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function data(Request $request)
    {
        $section = $request->query('section');

        if ($section === 'tasks') {
            $tasks = MerchandiserTask::with(['order.buyer', 'order.style'])->latest();

            return DataTables::of($tasks)
                ->addIndexColumn()
                ->addColumn('sl', function () {
                    static $count = 0;

                    return ++$count;
                })
                ->addColumn('order', fn ($data) => $data->order?->order_number ?: __('Not assigned'))
                ->addColumn('due_date', fn ($data) => $data->due_date?->format('d M Y') ?: __('No due date'))
                ->addColumn('status', function ($data) {
                    if ((int) $data->status === 3) {
                        return '<div class="zBadge zBadge-complete">' . __('Done') . '</div>';
                    }
                    if ((int) $data->status === 2) {
                        return '<div class="zBadge zBadge-primary">' . __('Active') . '</div>';
                    }

                    return '<div class="zBadge zBadge-warning">' . __('Pending') . '</div>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex"><div class="dropdown options-area">'
                        . '<a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>'
                        . '<ul class="dropdown-menu dropdown-menu-end"><li>'
                        . '<a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.merchandiser.task.destroy', $data->id) . '\', \'merchandiserTaskDataTable\')">' . __('Delete') . '</a>'
                        . '</li></ul></div></div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        if ($section === 'communications') {
            $communications = BuyerCommunication::with(['order.buyer'])->latest('communicated_at');

            return DataTables::of($communications)
                ->addIndexColumn()
                ->addColumn('sl', function () {
                    static $count = 0;

                    return ++$count;
                })
                ->addColumn('order', fn ($data) => $data->order?->order_number ?: __('Not assigned'))
                ->addColumn('channel', fn ($data) => ucfirst((string) $data->channel))
                ->addColumn('subject', fn ($data) => e($data->subject ?: Str::limit((string) $data->notes, 60)))
                ->addColumn('date', fn ($data) => $data->communicated_at?->format('d M Y, H:i') ?: __('Not set'))
                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex"><div class="dropdown options-area">'
                        . '<a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>'
                        . '<ul class="dropdown-menu dropdown-menu-end"><li>'
                        . '<a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.merchandiser.communication.destroy', $data->id) . '\', \'merchandiserCommunicationDataTable\')">' . __('Delete') . '</a>'
                        . '</li></ul></div></div>';
                })
                ->rawColumns(['subject', 'action'])
                ->make(true);
        }

        if ($section === 'cards') {
            return response()->json([
                'cards' => [
                    'orders' => GarmentOrder::count(),
                    'tasks' => MerchandiserTask::count(),
                    'communications' => BuyerCommunication::count(),
                ],
            ]);
        }

        if ($request->ajax() && $section === 'orders') {
            $orders = GarmentOrder::with(['buyer', 'style', 'merchandisers'])->latest();

            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('sl', function () {
                    static $count = 0;

                    return ++$count;
                })
                ->addColumn('buyer', fn ($data) => $data->buyer?->company_name ?: __('Not assigned'))
                ->addColumn('style', fn ($data) => $data->style?->style_code ?: __('Not assigned'))
                ->addColumn('primary', function ($data) {
                    $primary = $data->merchandisers->firstWhere('pivot.is_primary', true)?->name;

                    return $primary
                        ? '<span class="d-inline-flex align-items-center gap-1 fw-600 text-dark"><i class="fa-solid fa-star text-warning me-1"></i>' . e($primary) . '</span>'
                        : '<span class="text-muted small">Not designated</span>';
                })
                ->addColumn('team', function ($data) {
                    if ($data->merchandisers->isEmpty()) {
                        return '<span class="text-muted small">No merchandisers assigned</span>';
                    }

                    $badges = $data->merchandisers->map(fn ($merchandiser) => '<span class="zBadge ' . ($merchandiser->pivot->is_primary ? 'zBadge-primary' : 'zBadge-outline') . '" style="font-size:11px;">' . e($merchandiser->name) . '</span>')->implode('');

                    return '<div class="d-flex flex-wrap gap-1">' . $badges . '</div>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex"><div class="dropdown options-area">'
                        . '<a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>'
                        . '<ul class="dropdown-menu dropdown-menu-end"><li>'
                        . '<a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.merchandiser.assign.create', ['order_id' => $data->id]) . '\', \'#edit-modal\')">' . __('Manage') . '</a>'
                        . '</li></ul></div></div>';
                })
                ->rawColumns(['primary', 'team', 'action'])
                ->make(true);
        }

        return response()->json([
            'cards' => [
                'orders' => GarmentOrder::count(),
                'tasks' => MerchandiserTask::count(),
                'communications' => BuyerCommunication::count(),
            ],
        ]);
    }

    public function editAssign(Request $request)
    {
        $selectedOrderId = $request->query('order_id');
        $selectedOrder = $selectedOrderId ? GarmentOrder::with('merchandisers')->find($selectedOrderId) : null;

        return view('admin.garments.merchandiser.edit-assign', [
            'orders' => GarmentOrder::with(['style', 'buyer'])->latest()->get(),
            'users' => User::where('status', STATUS_ACTIVE)->orderBy('name')->get(),
            'selectedOrder' => $selectedOrder,
        ]);
    }

    public function assign(Request $request)
    {
        $data = $request->validate(['order_id'=>'required|exists:garment_orders,id','user_ids'=>'required|array','user_ids.*'=>'exists:users,id','primary_user_id'=>'nullable|exists:users,id']);
        try {
            $order = GarmentOrder::findOrFail($data['order_id']);
            $sync = collect($data['user_ids'])->mapWithKeys(fn ($id) => [$id => ['is_primary' => (int) $id === (int) ($data['primary_user_id'] ?? 0)]])->all();
            $order->merchandisers()->sync($sync);

            return $this->success([], __('Merchandisers assigned successfully.'));
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function task(Request $request)
    {
        $data = $request->validate(['order_id'=>'required|exists:garment_orders,id','user_id'=>'required|exists:users,id','title'=>'required|string|max:160','due_date'=>'nullable|date','priority'=>'required|in:1,2,3','notes'=>'nullable|string']);
        try {
            MerchandiserTask::create($data);

            return $this->success([], __('Task created successfully.'));
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function communication(Request $request)
    {
        $data = $request->validate(['order_id'=>'required|exists:garment_orders,id','channel'=>'required|in:email,phone,meeting,whatsapp','communicated_at'=>'required|date','subject'=>'nullable|string','notes'=>'required|string']);
        try {
            $data['user_id'] = auth()->id();
            BuyerCommunication::create($data);

            return $this->success([], __('Communication logged successfully.'));
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function destroyTask($id)
    {
        try {
            MerchandiserTask::findOrFail($id)->delete();

            return $this->success([], __('Task deleted successfully.'));
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function destroyCommunication($id)
    {
        try {
            BuyerCommunication::findOrFail($id)->delete();

            return $this->success([], __('Communication deleted successfully.'));
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}

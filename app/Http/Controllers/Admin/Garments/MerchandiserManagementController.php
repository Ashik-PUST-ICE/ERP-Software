<?php
namespace App\Http\Controllers\Admin\Garments;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\MerchandiserTask;
use App\Models\Garments\BuyerCommunication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MerchandiserManagementController extends Controller
{
    public function index()
    {
        return view('admin.garments.merchandiser.management', [
            'title' => __('Merchandiser Assignments & Activities'),
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function data(): JsonResponse
    {
        $orders = GarmentOrder::with(['buyer', 'style', 'merchandisers'])->latest()->take(30)->get();
        $tasks = MerchandiserTask::with('order')->latest()->take(30)->get();
        $communications = BuyerCommunication::with('order')->latest('communicated_at')->take(30)->get();

        return response()->json([
            'cards' => [
                'orders' => $orders->count(),
                'tasks' => $tasks->count(),
                'communications' => $communications->count(),
            ],
            'orders' => $orders->map(fn ($order) => [
                'id' => $order->id,
                'label' => trim($order->order_number . ' - ' . ($order->buyer?->company_name ?: __('Buyer not assigned'))),
                'order_number' => $order->order_number,
                'buyer' => $order->buyer?->company_name ?: __('Not assigned'),
                'style' => $order->style?->style_code ?: __('Not assigned'),
                'primary' => $order->merchandisers->firstWhere('pivot.is_primary', true)?->name,
                'team' => $order->merchandisers->map(fn ($merchandiser) => [
                    'name' => $merchandiser->name,
                    'is_primary' => (bool) $merchandiser->pivot->is_primary,
                ])->values(),
                'manage_url' => route('admin.garments.merchandiser.assign.create', ['order_id' => $order->id]),
            ])->values(),
            'tasks' => $tasks->map(fn ($task) => [
                'id' => $task->id,
                'title' => $task->title,
                'order' => $task->order?->order_number ?: __('Not assigned'),
                'due_date' => $task->due_date?->format('d M Y'),
                'status_label' => $task->status == 3 ? __('Done') : ($task->status == 2 ? __('Active') : __('Pending')),
                'status_class' => $task->status == 3 ? 'zBadge-complete' : ($task->status == 2 ? 'zBadge-primary' : 'zBadge-warning'),
            ])->values(),
            'communications' => $communications->map(fn ($communication) => [
                'id' => $communication->id,
                'channel' => ucfirst((string) $communication->channel),
                'order' => $communication->order?->order_number ?: __('Not assigned'),
                'subject' => $communication->subject ?: Str::limit((string) $communication->notes, 90),
                'date' => $communication->communicated_at?->format('d M Y, H:i'),
            ])->values(),
            'users' => User::where('status', STATUS_ACTIVE)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function createAssign(Request $request)
    {
        $selectedOrderId = $request->query('order_id');
        $selectedOrder = $selectedOrderId ? GarmentOrder::with('merchandisers')->find($selectedOrderId) : null;

        return view('admin.garments.merchandiser.assign', [
            'title' => __('Assign Merchandisers to Order'),
            'orders' => GarmentOrder::with(['style', 'buyer'])->latest()->get(),
            'users' => User::where('status', STATUS_ACTIVE)->orderBy('name')->get(),
            'selectedOrder' => $selectedOrder,
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function assign(Request $request)
    {
        $data = $request->validate(['order_id'=>'required|exists:garment_orders,id','user_ids'=>'required|array','user_ids.*'=>'exists:users,id','primary_user_id'=>'nullable|exists:users,id']);
        $order = GarmentOrder::findOrFail($data['order_id']);
        $sync = collect($data['user_ids'])->mapWithKeys(fn ($id) => [$id => ['is_primary' => (int) $id === (int) ($data['primary_user_id'] ?? 0)]])->all();
        $order->merchandisers()->sync($sync);
        return redirect()->route('admin.garments.merchandiser.management')->with('success', __('Merchandisers assigned successfully.'));
    }

    public function createTask()
    {
        return view('admin.garments.merchandiser.create-task', [
            'title' => __('Create Merchandiser Task'),
            'orders' => GarmentOrder::with(['style', 'buyer'])->latest()->get(),
            'users' => User::where('status', STATUS_ACTIVE)->orderBy('name')->get(),
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function task(Request $request)
    {
        $data = $request->validate(['order_id'=>'required|exists:garment_orders,id','user_id'=>'required|exists:users,id','title'=>'required|string|max:160','due_date'=>'nullable|date','priority'=>'required|in:1,2,3','notes'=>'nullable|string']);
        MerchandiserTask::create($data);
        return redirect()->route('admin.garments.merchandiser.management')->with('success', __('Task created successfully.'));
    }

    public function createCommunication()
    {
        return view('admin.garments.merchandiser.create-communication', [
            'title' => __('Log Buyer Communication'),
            'orders' => GarmentOrder::with(['style', 'buyer'])->latest()->get(),
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function communication(Request $request)
    {
        $data = $request->validate(['order_id'=>'required|exists:garment_orders,id','channel'=>'required|in:email,phone,meeting,whatsapp','communicated_at'=>'required|date','subject'=>'nullable|string','notes'=>'required|string']);
        $data['user_id'] = auth()->id();
        BuyerCommunication::create($data);
        return redirect()->route('admin.garments.merchandiser.management')->with('success', __('Communication logged successfully.'));
    }
}

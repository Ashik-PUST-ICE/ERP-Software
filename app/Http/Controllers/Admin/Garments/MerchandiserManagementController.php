<?php
namespace App\Http\Controllers\Admin\Garments;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\MerchandiserTask;
use App\Models\Garments\BuyerCommunication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MerchandiserManagementController extends Controller
{
    public function index()
    {
        return view('admin.garments.merchandiser.management', [
            'title' => __('Merchandiser Assignments & Activities'),
            'orders' => GarmentOrder::with('merchandisers')->latest()->take(30)->get(),
            'users' => User::where('status', STATUS_ACTIVE)->orderBy('name')->get(),
            'tasks' => MerchandiserTask::with('order')->latest()->take(30)->get(),
            'communications' => BuyerCommunication::with('order')->latest('communicated_at')->take(30)->get(),
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function assign(Request $request)
    {
        $data = $request->validate(['order_id'=>'required|exists:garment_orders,id','user_ids'=>'required|array','user_ids.*'=>'exists:users,id','primary_user_id'=>'nullable|exists:users,id']);
        $order = GarmentOrder::findOrFail($data['order_id']);
        $sync = collect($data['user_ids'])->mapWithKeys(fn ($id) => [$id => ['is_primary' => (int) $id === (int) ($data['primary_user_id'] ?? 0)]])->all();
        $order->merchandisers()->sync($sync);
        return back()->with('success', __('Merchandisers assigned successfully.'));
    }

    public function task(Request $request)
    {
        $data = $request->validate(['order_id'=>'required|exists:garment_orders,id','user_id'=>'required|exists:users,id','title'=>'required|string|max:160','due_date'=>'nullable|date','priority'=>'required|in:1,2,3','notes'=>'nullable|string']);
        MerchandiserTask::create($data);
        return back()->with('success', __('Task created successfully.'));
    }

    public function communication(Request $request)
    {
        $data = $request->validate(['order_id'=>'required|exists:garment_orders,id','channel'=>'required|in:email,phone,meeting,whatsapp','communicated_at'=>'required|date','subject'=>'nullable|string','notes'=>'required|string']);
        $data['user_id'] = auth()->id();
        BuyerCommunication::create($data);
        return back()->with('success', __('Communication logged successfully.'));
    }
}

<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Http\Services\Admin\TicketService;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\TicketAssignee;
use App\Models\TicketConversation;
use App\Models\TicketSeenUnseen;
use App\Models\User;
use App\Models\UserPackage;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    use ResponseTrait;

    private TicketService $ticketService;

    public function __construct()
    {
        $this->ticketService = new TicketService();
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->ticketService->ticketList($request->status);
        }

        $data['pageTitle'] = __('Ticket');
        $data['activeSupportTicket'] = 'active';
        $data['showSupportMenu'] = true;
        $data['ticketCount'] = $this->ticketService->ticketCount();
        
        // Get all active user packages for package selection in modal
        $data['paymentOrderList'] = UserPackage::where('status', STATUS_ACTIVE)
            ->whereDate('end_date', '>=', now())
            ->with(['user:id,name,email', 'packageable:id,name'])
            ->orderBy('id', 'desc')
            ->get();
        
        // Get all users who have active packages (for client selection)
        $activeUserIds = UserPackage::where('status', STATUS_ACTIVE)
            ->whereDate('end_date', '>=', now())
            ->distinct()
            ->pluck('user_id');
        
        $data['userList'] = User::whereIn('id', $activeUserIds)
            ->where('status', STATUS_ACTIVE)
            ->select('id', 'name', 'email')
            ->orderBy('name', 'asc')
            ->get();
        
        return view('auto_posts.admin.ticket.list', $data);
    }

    public function addNew()
    {
        $data['pageTitleParent'] = __('Ticket');
        $data['pageTitle'] = __('Add Ticket');
        $data['activeSupportTicket'] = 'active';
        $data['showSupportMenu'] = true;
        
        // Get all users who have paid packages (for client selection)
        $paidUserIds = Payment::where('payment_status', PAYMENT_STATUS_PAID)
            ->distinct()
            ->pluck('user_id');
        
        $data['userList'] = User::whereIn('id', $paidUserIds)
            ->where('status', STATUS_ACTIVE)
            ->select('id', 'name', 'email')
            ->orderBy('name', 'asc')
            ->get();
        
        // Get all paid payments/orders for package selection
        $data['paymentOrderList'] = Payment::where('payment_status', PAYMENT_STATUS_PAID)
            ->with(['user:id,name,email', 'paymentable:id,name'])
            ->orderBy('id', 'desc')
            ->get();
        
        return view('auto_posts.admin.ticket.add-new', $data);
    }

    public function edit($id)
    {
        $ticketDetails = $this->ticketService->ticketDetails(decrypt($id));
        if (!$ticketDetails) {
            abort(404);
        }
        $data['pageTitleParent'] = __('Ticket');
        $data['pageTitle'] = __('Edit Ticket');
        $data['activeSupportTicket'] = 'active';
        $data['showSupportMenu'] = true;

        // Use UserPackage (same as add-new/list) for consistent package selection
        $data['paymentOrderList'] = UserPackage::where('status', STATUS_ACTIVE)
            ->whereDate('end_date', '>=', now())
            ->with(['user:id,name,email', 'packageable:id,name'])
            ->orderBy('id', 'desc')
            ->get();

        $data['ticketDetails'] = $ticketDetails;
        return view('auto_posts.admin.ticket.edit', $data);
    }

    public function details($id)
    {
        $ticketDetails = $this->ticketService->ticketDetails(decrypt($id));
        if (!$ticketDetails) {
            abort(404);
        }
        $data['pageTitleParent'] = __('Ticket');
        $data['pageTitle'] = __('Ticket Details');
        $data['activeSupportTicket'] = 'active';
        $data['showSupportMenu'] = true;
        $data['ticketDetails'] = $ticketDetails;
        $data['ticketConversations'] = $this->ticketService->ticketConversations(decrypt($id));

        $seenData = TicketSeenUnseen::firstOrNew(
            ['ticket_id' => decrypt($id), 'created_by' => auth()->id()],
            ['is_seen' => 0]
        );
        $seenData->is_seen = 1;
        $seenData->save();

        return view('auto_posts.admin.ticket.details', $data);
    }

    public function store(TicketRequest $request)
    {
        return $this->ticketService->store($request);
    }

    public function conversationsStore(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
            'conversation_text' => 'required',
        ]);
        return $this->ticketService->conversationsStore($request);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $ticketData = Ticket::where('id', decrypt($id))->firstOrFail();
            $ticketData->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function conversationsDelete($id)
    {
        try {
            DB::beginTransaction();
            $conversation = TicketConversation::findOrFail(decrypt($id));
            $ticket = Ticket::find($conversation->ticket_id);
            if (!$ticket || $ticket->client_id !== (int) auth()->id()) {
                abort(404);
            }
            $conversation->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function assignMember(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->checked_status == 1) {
                $assignee = new TicketAssignee();
                $assignee->ticket_id = $request->ticket_id;
                $assignee->assigned_to = $request->member_id;
                $assignee->assigned_by = auth()->id();
                $assignee->is_active = ACTIVE;
                $assignee->save();
            } else {
                TicketAssignee::where(['ticket_id' => $request->ticket_id, 'assigned_to' => $request->member_id])->delete();
            }
            DB::commit();
            $responseData = ['datatable' => $request->data_table ?? ''];
            return $this->success($responseData, __('Assignee Updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function priorityChange($ticket_id, $priority)
    {
        try {
            DB::beginTransaction();
            $ticket = Ticket::findOrFail(decrypt($ticket_id));
            if ($ticket->client_id !== (int) auth()->id()) {
                abort(404);
            }
            $ticket->priority = $priority;
            $ticket->save();
            DB::commit();
            return redirect()->back()->with('success', __('Priority changed successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong!'));
        }
    }

    public function statusChange(Request $request)
    {
        try {
            DB::beginTransaction();
            $ticket = Ticket::findOrFail(decrypt($request->ticket_id));
            if ($ticket->client_id !== (int) auth()->id()) {
                abort(404);
            }
            $ticket->status = $request->status;
            $ticket->save();
            DB::commit();
            return $this->success([], __('Status changed successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, SOMETHING_WENT_WRONG));
        }
    }
}
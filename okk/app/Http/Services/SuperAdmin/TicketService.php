<?php

namespace App\Http\Services\SuperAdmin;

use App\Models\FileManager;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\TicketAssignee;
use App\Models\TicketConversation;
use App\Models\TicketSeenUnseen;
use App\Models\UserPackage;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class TicketService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                $data = Ticket::find($request->id);
                $msg = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                $data = new Ticket();
                $msg = getMessage(CREATED_SUCCESSFULLY);
            }

            $clientId = $request->client_id;
            if ($request->order_id) {
                $order = UserPackage::find($request->order_id) ?? Payment::find($request->order_id);
                if ($order) {
                    $clientId = $order->user_id;
                }
            }
            $data->client_id = $clientId;
            $data->order_id = $request->order_id ?? null;
            $data->ticket_title = $request->ticket_title ?? 'Support Request';
            $data->ticket_description = $request->description;
            $data->priority = $request->priority ?? TICKET_PRIORITY_LOW;
            if ($request->id && $request->has('status')) {
                $data->status = $request->status;
            }
            $data->created_by = auth()->id();

            if ($request->hasFile('file')) {
                $fileId = [];
                foreach ((array) $request->file('file') as $singlefile) {
                    $new_file = new FileManager();
                    $uploaded = $new_file->upload('ticket-documents', $singlefile);
                    if ($uploaded) {
                        $fileId[] = (string) $uploaded->id;
                    }
                }
                $data->file_id = !empty($fileId) ? json_encode($fileId) : null;
            } elseif ($request->oldFiles) {
                $data->file_id = json_encode((array) $request->oldFiles);
            } else {
                $data->file_id = $request->id ? ($data->file_id ?? null) : null;
            }

            $data->save();

            if (!$request->id) {
                $ticketId = 'ST' . sprintf('%06d', $data->id);
                Ticket::where('id', $data->id)->update(['ticket_id' => $ticketId]);
            }

            if ($request->assign_member && count((array) $request->assign_member) > 0) {
                TicketAssignee::where('ticket_id', $data->id)->delete();
                foreach ((array) $request->assign_member as $assignee) {
                    $dataObj = new TicketAssignee();
                    $dataObj->ticket_id = $data->id;
                    $dataObj->assigned_to = $assignee;
                    $dataObj->assigned_by = auth()->id();
                    $dataObj->is_active = ACTIVE;
                    $dataObj->save();
                }
            }

            DB::commit();

            if (function_exists('newTicketEmailNotify')) {
                newTicketEmailNotify($data->id);
            }
            if (function_exists('newTicketNotify')) {
                newTicketNotify($data->id);
            }

            return $this->success([], $msg);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function ticketCount(): int
    {
        return Ticket::withTrashed()->count();
    }

    public function ticketList($status)
    {
        $baseQuery = Ticket::leftJoin('users', 'tickets.client_id', '=', 'users.id')
            ->leftJoin('ticket_seen_unseens', function ($join) {
                $join->on('tickets.id', '=', 'ticket_seen_unseens.ticket_id')
                    ->where('ticket_seen_unseens.created_by', '=', auth()->id());
            })
            ->orderByRaw('COALESCE(tickets.last_reply_time, tickets.created_at) DESC')
            ->orderBy('tickets.id', 'DESC')
            ->select([
                'tickets.*',
                'users.name as client_name',
                'users.email as client_email',
                'ticket_seen_unseens.is_seen'
            ]);

        if ($status == 'all') {
            $data = (clone $baseQuery)->with(['assignee', 'payment.paymentable', 'userPackage.packageable']);
        } elseif ($status == TICKET_STATUS_TRASHED) {
            $data = (clone $baseQuery)->onlyTrashed()->with(['assignee', 'payment.paymentable', 'userPackage.packageable']);
        } else {
            $data = (clone $baseQuery)->where('tickets.status', $status)->with(['assignee', 'payment.paymentable', 'userPackage.packageable']);
        }

        return datatables($data)
            ->addIndexColumn()
            ->editColumn('client_name', function ($row) {
                return '<p>' . e($row->client_name ?? '-') . '</p>';
            })
            ->editColumn('order_id', function ($row) {
                $packageName = $row->userPackage && $row->userPackage->packageable ? $row->userPackage->packageable->name : ($row->payment->paymentable->name ?? ($row->order_id ?? '-'));
                return '<p>' . e($packageName) . '</p>';
            })
            ->editColumn('ticket_id', function ($row) {
                return getTicketIdHtml($row, 'super_admin');
            })
            ->editColumn('priority', function ($row) {
                if ($row->priority == TICKET_PRIORITY_HIGH) {
                    return '<p>' . __('High') . '</p>';
                }
                if ($row->priority == TICKET_PRIORITY_MEDIUM) {
                    return '<p>' . __('Medium') . '</p>';
                }
                return '<p>' . __('Low') . '</p>';
            })
            ->editColumn('status', function ($row) use ($status) {
                if ($status == TICKET_STATUS_TRASHED) {
                    return "<p class='zBadge zBadge-cancel'>" . __('Deleted') . "</p>";
                }
                $statusMap = [
                    TICKET_STATUS_OPEN => ['zBadge-open', __('Open')],
                    TICKET_STATUS_IN_PROGRESS => ['zBadge-onHold', __('In Progress')],
                    TICKET_STATUS_RESOLVED => ['zBadge-complete', __('Resolved')],
                    TICKET_STATUS_CLOSED => ['zBadge-closed', __('Closed')],
                ];
                $s = $statusMap[$row->status] ?? ['zBadge-open', __('Open')];
                return "<p class='zBadge " . $s[0] . "'>" . $s[1] . "</p>";
            })
            ->addColumn('action', function ($row) {
                $detailsUrl = route('super_admin.ticket.details', encrypt($row->id));
                $editUrl = route('super_admin.ticket.edit', encrypt($row->id));
                $deleteUrl = route('super_admin.ticket.delete', encrypt($row->id));
                return '<div class="dropdown options-area">
                    <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="' . $detailsUrl . '">' . __('Details') . '</a></li>
                        <li><a class="dropdown-item" href="' . $editUrl . '">' . __('Edit') . '</a></li>
                        <li><a class="dropdown-item delete-item" href="#" data-route="' . $deleteUrl . '">' . __('Delete') . '</a></li>
                    </ul>
                </div>';
            })
            ->rawColumns(['client_name', 'order_id', 'ticket_id', 'priority', 'status', 'action'])
            ->make(true);
    }

    public function ticketDetails($id)
    {
        return Ticket::leftJoin('users', 'tickets.client_id', '=', 'users.id')
            ->where(['tickets.id' => $id])
            ->select([
                'tickets.*',
                'users.name as client_name',
                'users.email as client_email',
                'users.image as client_image',
                'users.role as client_role',
            ])->withTrashed()->with(['assignee', 'payment.paymentable', 'userPackage.packageable', 'userPackage'])->first();
    }

    public function ticketConversations($id)
    {
        return TicketConversation::leftJoin('users', 'ticket_conversations.user_id', '=', 'users.id')
            ->where(['ticket_conversations.ticket_id' => $id])
            ->select([
                'ticket_conversations.*',
                'users.name as client_name',
                'users.email as client_email',
                'users.image as client_image',
                'users.role as client_role',
            ])->get();
    }

    public function conversationsStore($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                $data = TicketConversation::find($request->id);
                $msg = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                $data = new TicketConversation();
                $msg = getMessage(CREATED_SUCCESSFULLY);
            }
            $data->ticket_id = decrypt($request->ticket_id);
            $data->conversation_text = $request->conversation_text;
            $data->user_id = auth()->id();

            if ($request->hasFile('file')) {
                $fileId = [];
                foreach ((array) $request->file('file') as $singlefile) {
                    $new_file = new FileManager();
                    $uploaded = $new_file->upload('ticket-conversation-documents', $singlefile);
                    if ($uploaded) {
                        $fileId[] = (string) $uploaded->id;
                    }
                }
                $data->attachment = !empty($fileId) ? json_encode($fileId) : null;
            } elseif ($request->oldFiles) {
                $data->attachment = json_encode((array) $request->oldFiles);
            } else {
                $data->attachment = $request->id ? ($data->attachment ?? null) : null;
            }

            $data->save();

            TicketSeenUnseen::where('ticket_id', decrypt($request->ticket_id))
                ->where('created_by', '!=', auth()->id())
                ->update(['is_seen' => 0]);

            $ticketUpdate = [
                'last_reply_id' => $data->id,
                'last_reply_by' => auth()->id(),
                'last_reply_time' => now(),
            ];
            if ($request->has('status') && in_array((int) $request->status, [TICKET_STATUS_OPEN, TICKET_STATUS_IN_PROGRESS, TICKET_STATUS_RESOLVED, TICKET_STATUS_CLOSED])) {
                $ticketUpdate['status'] = (int) $request->status;
            }
            Ticket::where('id', decrypt($request->ticket_id))->update($ticketUpdate);

            DB::commit();

            if (function_exists('ticketConversationEmailNotifyToAdminAndTeamMember')) {
                ticketConversationEmailNotifyToAdminAndTeamMember(decrypt($request->ticket_id));
            }
            if (function_exists('ticketConversationNotifyToAdminAndTeamMember')) {
                ticketConversationNotifyToAdminAndTeamMember(decrypt($request->ticket_id));
            }

            return $this->success([], $msg);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }
}

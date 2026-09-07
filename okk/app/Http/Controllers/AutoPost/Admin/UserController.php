<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;


class UserController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $data = [
            'activeUsers' => 'active',
            'showUsersMenu' => 'show',
            'title' => __('User List'),
            'breadcrumb' => __('User Management') . ' / ' . __('Users List'),
        ];

        return view('auto_posts.admin.users.index', $data);
    }

    public function data(Request $request)
    {
        $authUser = auth()->user();
        $tenantId = $authUser->tenant_id;

        $users = User::select('id', 'name', 'email', 'role', 'mobile', 'status')
            ->where('role', USER_ROLE_ADMIN)
            // Super admin can see all admins across tenants
            ->when($authUser->role != USER_ROLE_SUPER_ADMIN, function ($q) use ($tenantId, $authUser) {
                if (!empty($tenantId)) {
                    // Normal case: scope by tenant_id
                    $q->where('tenant_id', $tenantId);
                } else {
                    // Safety net: if this admin has no tenant_id yet, only allow seeing own record
                    $q->where('id', $authUser->id);
                }
            })
            ->orderBy('id', 'DESC');

        return datatables($users)
            ->addColumn('sl', function ($data) {
                static $count = 0;
                return ++$count;
            })
            ->addColumn('role', function ($data) {
                return 'Admin';
            })
            ->editColumn('status', function ($data) {
                if ($data->status == STATUS_ACTIVE) {
                    return '<span class="status active">' . __('Active') . '</span>';
                } else {
                    return '<span class="status failed">' . __('Deactivate') . '</span>';
                }
            })
            ->addColumn('action', function ($data) {
                return '<div class="inline-flex">
                            <div class="dropdown options-area">
                                <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.users.edit', $data->id) . '\', \'#edit-modal\')">
                                            ' . __('Edit') . '
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function edit($id)
    {
        try {
            $authUser = auth()->user();
            $tenantId = $authUser->tenant_id;

            $query = User::where('id', $id);

            if ($authUser->role != USER_ROLE_SUPER_ADMIN) {
                if (!empty($tenantId)) {
                    $query->where('tenant_id', $tenantId);
                } else {
                    // If this admin has no tenant yet, only allow editing own profile
                    $query->where('id', $authUser->id);
                }
            }

            $user = $query->firstOrFail();
            $data = [
                'user' => $user,
            ];

            return view('auto_posts.admin.users.edit', $data);
        } catch (\Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'mobile' => 'nullable|string|max:20',
            'status' => 'required|in:1,3',
        ]);

        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->mobile = $request->mobile;
            $user->role = USER_ROLE_ADMIN;
            $user->status = $request->status;
            $user->tenant_id = auth()->user()->tenant_id;
            $user->save();

            DB::commit();
            $message = getMessage(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile' => 'nullable|string|max:20',
            'status' => 'required|in:1,3',
        ]);

        try {
            DB::beginTransaction();
            $authUser = auth()->user();
            $tenantId = $authUser->tenant_id;

            $query = User::where('id', $id);

            if ($authUser->role != USER_ROLE_SUPER_ADMIN) {
                if (!empty($tenantId)) {
                    $query->where('tenant_id', $tenantId);
                } else {
                    // Safety: non-super-admin without tenant_id can only update own record
                    $query->where('id', $authUser->id);
                }
            }

            $user = $query->firstOrFail();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->status = $request->status;
            $user->save();

            DB::commit();
            $message = getMessage(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }
}

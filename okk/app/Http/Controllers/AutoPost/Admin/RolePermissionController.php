<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     * Admin manages roles for Admin users (user_type = USER_ROLE_ADMIN)
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $tenantId = auth()->user()->tenant_id ?? null; // Get current user's tenant_id
            
            $roles = Role::where('user_type', USER_ROLE_ADMIN)
                ->when($tenantId, function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                })
                ->withCount('permissions')
                ->orderBy('id', 'DESC');
            return datatables($roles)
                ->addIndexColumn()
                ->addColumn('sl', function ($data) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('display_name', function ($data) {
                    return $data->display_name;
                })
                ->addColumn('permissions_count', function ($data) {
                    return $data->permissions_count;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == STATUS_ACTIVE) {
                        return '<div class="zBadge zBadge-complete">' . __("Active") . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-deactive">' . __("Deactivate") . '</div>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return
                        '<div class="inline-flex">
                            <div class="dropdown options-area">
                                <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.roles.edit', $data->id) . '\', \'#edit-modal\')">
                                            ' . __('Edit') . '
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="' . route('admin.roles.permissions', $data->id) . '">
                                            ' . __('Permissions') . '
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.roles.destroy', [$data->id]) . '\', \'rolesDataTable\')">
                                            ' . __('Delete') . '
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $data['title'] = __('User Roles');
        $data['breadcrumb'] = __('User Roles') . ' / ' . __('Add Roles');
        $data['activeRoles'] = 'active';
        $data['showRolesMenu'] = 'show';
        $tenantId = auth()->user()->tenant_id ?? null;
        $data['roles'] = Role::where('user_type', USER_ROLE_ADMIN)
            ->when($tenantId, function ($query) use ($tenantId) {
                return $query->where('tenant_id', $tenantId);
            })
            ->orderBy('id', 'DESC')->get();
        $data['permissions'] = Permission::all();
        return view('auto_posts.admin.roles.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        try {
            DB::beginTransaction();
            $tenantId = auth()->user()->tenant_id ?? null;
            
            $role = new Role();
            $role->name = $request->name;
            $role->display_name = $request->name;
            $role->guard_name = 'web';
            $role->status = STATUS_ACTIVE;
            $role->user_type = USER_ROLE_ADMIN; // Admin creates roles for admin users
            $role->tenant_id = $tenantId; // Add tenant_id
            $role->save();

            if (!empty($request->permissions)) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();
            $message = __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tenantId = auth()->user()->tenant_id ?? null;
        $role = Role::where('user_type', USER_ROLE_ADMIN)
            ->when($tenantId, function ($query) use ($tenantId) {
                return $query->where('tenant_id', $tenantId);
            })
            ->findOrFail($id);
        $data['role'] = $role;
        $data['permissions'] = Permission::all();
        $data['oldPermissions'] = $data['role']->permissions->pluck('name')->toArray();
        return view('auto_posts.admin.roles.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        try {
            DB::beginTransaction();
            $tenantId = auth()->user()->tenant_id ?? null;
            $role = Role::where('user_type', USER_ROLE_ADMIN)
                ->when($tenantId, function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                })
                ->findOrFail($id);

            $role->name = $request->name;
            $role->display_name = $request->name;
            // Keep existing status; do not overwrite with null
            if ($role->status === null) {
                $role->status = STATUS_ACTIVE;
            }
            $role->save();

            if (!empty($request->permissions)) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();
            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    /**
     * Show permissions for a role.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function permissions($id)
    {
        $tenantId = auth()->user()->tenant_id ?? null;
        $data['title'] = __('User Permissions for Role');
        $data['activeRoles'] = 'active';
        $data['showRolesMenu'] = 'show';
        $data['role'] = Role::where('user_type', USER_ROLE_ADMIN)
            ->when($tenantId, function ($query) use ($tenantId) {
                return $query->where('tenant_id', $tenantId);
            })
            ->findOrFail($id);
        $permissions = Permission::all();
        $groupedPermissions = [];

        foreach ($permissions as $permission) {
            // Extract module from permission name (format: fund.admin.roles.view)
            $parts = explode('.', $permission->name);
            if (count($parts) >= 3) {
                $module = $parts[2]; // Extract module name

                $permission->module = $module;

                if (!isset($groupedPermissions[$module])) {
                    $groupedPermissions[$module] = [];
                }
                $groupedPermissions[$module][] = $permission;
            }
        }

        $data['permissions'] = $groupedPermissions;
        $data['oldPermissions'] = $data['role']->permissions->pluck('id')->toArray();
        return view('auto_posts.admin.roles.permissions', $data);
    }

    /**
     * Update permissions for a role.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $tenantId = auth()->user()->tenant_id ?? null;
            $role = Role::where('user_type', USER_ROLE_ADMIN)
                ->when($tenantId, function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                })
                ->where('id', $id)->first();

            // Form sends permission names, so use them directly
            $permissions = $request->permissions ?? [];
            $role->syncPermissions($permissions);

            DB::commit();

            // Create notification for role permissions update
            setCommonNotification(
                'User Role Permissions Updated',
                'Permissions for user role "' . $role->name . '" have been updated by ' . auth()->user()->name,
                route('admin.roles.permissions', $role->id)
            );

            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $tenantId = auth()->user()->tenant_id ?? null;
            Role::where('user_type', USER_ROLE_ADMIN)
                ->when($tenantId, function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                })
                ->where('id', $id)->delete();

            DB::commit();
            $message = __(DELETED_SUCCESSFULLY);

            if (request()->ajax()) {
                return $this->success([], $message);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());

            if (request()->ajax()) {
                return $this->error([], $message);
            }

            return redirect()->back()->with('error', $message);
        }
    }

    private function extractModule($name)
    {
        $parts = explode(': ', $name);
        return $parts[0] ?? $name;
    }
}
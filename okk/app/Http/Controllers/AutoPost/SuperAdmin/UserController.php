<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

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
            'title' => __('User List'),
            'breadcrumb' => __('User Management') . ' / ' . __('Users List'),
            
        ];

        return view('auto_posts.super_admin.users.index', $data);
    }

    public function data(Request $request)
    {
        $users = User::select('id', 'name', 'email', 'role', 'mobile', 'status')
            ->where('role', USER_ROLE_ADMIN)
            ->orderBy('id', 'DESC');

        return datatables($users)
            ->addColumn('sl', function ($data) {
                static $count = 0;
                $start = (int) request()->input('start', 0);
                return $start + (++$count);
            })
            ->addColumn('role', function ($data) {
                return $data->role == USER_ROLE_ADMIN ? 'Admin' : 'User';
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
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('super_admin.users.edit', $data->id) . '\', \'#edit-modal\')">
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
            $user = User::findOrFail($id);
            $data = [
                'user' => $user,
            ];

            return view('auto_posts.super_admin.users.edit', $data);
        } catch (\Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
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
            $user = User::findOrFail($id);
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
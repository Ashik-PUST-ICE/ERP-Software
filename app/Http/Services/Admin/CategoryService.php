<?php

namespace App\Http\Services\Admin;

use App\Models\Category;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryService
{
    use ResponseTrait;

    public function getAllData($request)
    {
        $query = Category::query()->with('creator');

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return datatables($query)
            ->addColumn('title', function ($category) {
                return $category->title;
            })
            ->addColumn('type', function ($category) {
                return $category->type ?? '-';
            })
            ->addColumn('status', function ($category) {
                $statusText = $category->status == STATUS_ACTIVE ? 'Active' : 'Inactive';
                $statusClass = $category->status == STATUS_ACTIVE ? 'active' : 'inactive';
                return '<span class="status ' . $statusClass . '">' . $statusText . '</span>';
            })
            ->addColumn('action', function ($category) {
                return '<div class="dropdown options-area">
                    <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.category.edit', $category->id) . '\', \'#edit-modal\')">Edit</a></li>
                        <li><a class="dropdown-item delete-item" href="#" data-route="' . route('admin.category.destroy', $category->id) . '">Delete</a></li>
                    </ul>
                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function getSelected()
    {
        return Category::where('status', STATUS_ACTIVE)
            ->orderBy('title', 'asc')
            ->pluck('title', 'id');
    }

    public function getParentSelected()
    {
        return Category::where('status', STATUS_ACTIVE)
            ->orderBy('title', 'asc')
            ->pluck('title', 'id');
    }

    public function getById($id)
    {
        return Category::with('creator')->findOrFail($id);
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $category = new Category();
            $category->title = $request->title;
            $category->slug = Str::slug($request->title);
            $category->type = $request->type ?: null;
            $category->short_description = $request->short_description ?: null;
            $category->status = $request->status == 'active' ? STATUS_ACTIVE : STATUS_DEACTIVATE;
            $category->created_by = auth()->id();
            $category->tenant_id = auth()->user()->tenant_id;
            $category->save();

            DB::commit();

            return $this->success(['category' => $category], getMessage(CREATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function update($request, $id)
    {
        try {
            DB::beginTransaction();

            $category = Category::findOrFail($id);

            $category->title = $request->title;
            $category->slug = Str::slug($request->title);
            $category->type = $request->type ?: null;
            $category->short_description = $request->short_description ?: null;

            if ($request->has('status')) {
                $category->status = $request->status == 'active' ? STATUS_ACTIVE : STATUS_DEACTIVATE;
            }

            $category->save();

            DB::commit();

            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function deleteById($id)
    {
        try {
            DB::beginTransaction();

            $category = Category::findOrFail($id);
            $category->delete();

            DB::commit();

            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }
}

<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\CategoryService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ResponseTrait;

    public $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [
            'activeCategory' => 'active',
            'showCategoryMenu' => 'true',
            'title' => __('Manage Categories'),
        ];

        return view('auto_posts.admin.category.index', $data);
    }

    /**
     * Display category datatable.
     */
    public function datatable(Request $request)
    {
        return $this->categoryService->getAllData($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'activeCategory' => 'active',
            'showCategoryMenu' => 'true',
            'title' => __('Create Category'),
            'parentCategories' => $this->categoryService->getParentSelected(),
        ];

        return view('auto_posts.admin.category.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
            'short_description' => 'nullable|string',
        ]);

        try {
            $result = $this->categoryService->store($request);
            $data = $result->getData();
            if ($data->status) {
                if ($request->ajax()) {
                    return response()->json(['status' => true, 'message' => $data->message]);
                }
                return redirect()->route('admin.category.index')->with(['success' => $data->message]);
            }
            if ($request->ajax()) {
                return response()->json(['status' => false, 'message' => $data->message], 422);
            }
            return redirect()->back()->with(['error' => $data->message]);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                 return response()->json(['status' => false, 'message' => getMessage(SOMETHING_WENT_WRONG)], 500);
            }
            return redirect()->back()->with(['error' => getMessage(SOMETHING_WENT_WRONG)]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = $this->categoryService->getById($id);
        
        $data = [
            'activeCategory' => 'active',
            'showCategoryMenu' => 'true',
            'title' => __('View Category'),
            'category' => $category,
        ];

        return view('auto_posts.admin.category.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = $this->categoryService->getById($id);
        
        $data = [
            'activeCategory' => 'active',
            'showCategoryMenu' => 'true',
            'title' => __('Edit Category'),
            'category' => $category,
            'parentCategories' => $this->categoryService->getParentSelected()->except($id),
        ];

        return view('auto_posts.admin.category.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
            'short_description' => 'nullable|string',
        ]);

        try {
            $result = $this->categoryService->update($request, $id);
            $data = $result->getData();
            if ($data->status) {
                 if ($request->ajax()) {
                    return response()->json(['status' => true, 'message' => $data->message]);
                }
                return redirect()->route('admin.category.index')->with(['success' => $data->message]);
            }
            if ($request->ajax()) {
                return response()->json(['status' => false, 'message' => $data->message], 422);
            }
            return redirect()->back()->with(['error' => $data->message]);
        } catch (\Exception $e) {
             if ($request->ajax()) {
                 return response()->json(['status' => false, 'message' => getMessage(SOMETHING_WENT_WRONG)], 500);
            }
            return redirect()->back()->with(['error' => getMessage(SOMETHING_WENT_WRONG)]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->categoryService->deleteById($id);
            $data = $result->getData();
            if (request()->ajax()) {
                return response()->json([
                    'success' => $data->status,
                    'message' => $data->message
                ]);
            }
            return redirect()->back()->with(['success' => $data->message]);
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => getMessage(SOMETHING_WENT_WRONG)
                ], 500);
            }
            return redirect()->back()->with(['error' => getMessage(SOMETHING_WENT_WRONG)]);
        }
    }


    
}
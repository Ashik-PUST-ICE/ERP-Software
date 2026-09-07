<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\GalleryService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    use ResponseTrait;

    public $galleryService;

    public function __construct()
    {
        $this->galleryService = new GalleryService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $data = [
            'activeGallery' => 'active',
            'showGalleryMenu' => 'true',
            'title' => __('Manage Image Library'),
            'galleries' => $this->galleryService->getAll(auth()->id(), $search),
        ];

        if ($request->ajax() && $request->has('view') && $request->view == 'modal') {
            return view('auto_posts.admin.gallery.modal-list', $data);
        }

        return view('auto_posts.admin.gallery.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,gif,svg,webp|max:5120', // 5MB max
        ]);

        try {
            $gallery = $this->galleryService->store($request);
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Image uploaded successfully'),
                    'data' => $gallery
                ]);
            }
            return redirect()->back()->with(['success' => __('Image uploaded successfully')]);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to upload image')
                ], 500);
            }
            return redirect()->back()->with(['error' => __('Failed to upload image')]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $this->galleryService->update($request, $id);
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Image updated successfully')
                ]);
            }
            return redirect()->back()->with(['success' => __('Image updated successfully')]);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to update image')
                ], 500);
            }
            return redirect()->back()->with(['error' => __('Failed to update image')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->galleryService->delete($id);
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Image deleted successfully')
                ]);
            }
            return redirect()->back()->with(['success' => __('Image deleted successfully')]);
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with(['error' => __('Failed to delete image')]);
        }
    }
}
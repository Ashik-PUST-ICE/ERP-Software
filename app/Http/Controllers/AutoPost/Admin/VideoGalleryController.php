<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\VideoService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class VideoGalleryController extends Controller
{
    use ResponseTrait;

    public $videoService;

    public function __construct()
    {
        $this->videoService = new VideoService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $data = [
            'activeVideoGallery' => 'active',
            'showVideoGalleryMenu' => 'true',
            'title' => __('Manage Video Library'),
            'videos' => $this->videoService->getAll(auth()->id(), $search),
        ];

        if ($request->ajax() && $request->has('view') && $request->view == 'modal') {
            return view('auto_posts.admin.video-gallery.modal-list', $data);
        }

        return view('auto_posts.admin.video-gallery.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:mp4,avi,mov,wmv,flv,webm|max:102400', // 100MB max
        ]);

        try {
            $video = $this->videoService->store($request);
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Video uploaded successfully'),
                    'data' => $video
                ]);
            }
            return redirect()->back()->with(['success' => __('Video uploaded successfully')]);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to upload video')
                ], 500);
            }
            return redirect()->back()->with(['error' => __('Failed to upload video')]);
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
            $this->videoService->update($request, $id);
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Video updated successfully')
                ]);
            }
            return redirect()->back()->with(['success' => __('Video updated successfully')]);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to update video')
                ], 500);
            }
            return redirect()->back()->with(['error' => __('Failed to update video')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->videoService->delete($id);
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Video deleted successfully')
                ]);
            }
            return redirect()->back()->with(['success' => __('Video deleted successfully')]);
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with(['error' => __('Failed to delete video')]);
        }
    }
}
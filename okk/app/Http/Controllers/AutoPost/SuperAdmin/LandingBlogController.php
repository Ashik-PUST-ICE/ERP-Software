<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LandingBlog;
use App\Models\FileManager;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingBlogController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the blogs.
     */
    public function index()
    {
        $data['subManageBlogsActiveClass'] = 'active';
        return view('auto_posts.super_admin.blogs.index', $data);
    }

    /**
     * Data for DataTables (AJAX).
     */
    public function data(Request $request)
    {
        $blogs = LandingBlog::query()->orderBy('id', 'DESC');

        return datatables($blogs)
            ->addIndexColumn()
            ->addColumn('sl', function () {
                static $count = 0;
                $start = (int) request()->input('start', 0);
                return $start + (++$count);
            })
            ->addColumn('description', function ($blog) {
                if (!$blog->description) {
                    return '';
                }
                $text = strip_tags($blog->description);
                return mb_strlen($text) > 100 ? mb_substr($text, 0, 100) . '...' : $text;
            })
            ->addColumn('image', function ($blog) {
                if (!$blog->image) {
                    return '';
                }

                // Support both: FileManager ID (new) and direct URL/path (old data)
                if (is_numeric($blog->image)) {
                    $src = e(getFileUrl($blog->image));
                } else {
                    $src = e($blog->image);
                }

                return '<img src="' . $src . '" alt="' . e($blog->title) . '" style="max-height:40px;">';
            })
            ->addColumn('status', function ($blog) {
                if ((int) $blog->status === STATUS_ACTIVE) {
                    return '<div class="zBadge zBadge-complete">' . __('Active') . '</div>';
                }
                return '<div class="zBadge zBadge-deactive">' . __('Inactive') . '</div>';
            })
            ->addColumn('action', function ($blog) {
                return
                    '<div class="inline-flex">
                        <div class="dropdown options-area">
                            <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="getEditModal(\'' . route('super_admin.setting.blogs.edit', $blog->id) . '\', \'#editBlogModal\')">
                                        ' . __('Edit') . '
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="deleteItem(\'' . route('super_admin.setting.blogs.destroy', [$blog->id]) . '\', \'blogsDataTable\')">
                                        ' . __('Delete') . '
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>';
            })
            ->rawColumns(['image', 'status', 'action'])
            ->make(true);
    }

    /**
     * Store a new blog.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'   => 'nullable|string|max:255',
            'url'    => 'nullable|string|max:255',
            'image'  => 'nullable|image|max:2048',
            'status' => 'nullable|integer|in:0,1',
        ]);

        try {
            DB::beginTransaction();

            $blog = new LandingBlog();
            $blog->title = $request->title;
            $blog->description = $request->description;
            $blog->date = $request->date;
            $blog->url = $request->url;
            $blog->status = (int) $request->input('status', STATUS_ACTIVE);

            if ($request->hasFile('image')) {
                $file = new FileManager();
                $uploaded = $file->upload('landing-blogs', $request->image);
                if (is_null($uploaded)) {
                    throw new \Exception(getMessage(SOMETHING_WENT_WRONG));
                }
                // Store FileManager ID
                $blog->image = $uploaded->id;
            }

            $blog->save();

            DB::commit();
            return $this->success([], __(CREATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    /**
     * Edit modal content.
     */
    public function edit($id)
    {
        $data['blog'] = LandingBlog::findOrFail($id);
        return view('auto_posts.super_admin.blogs.edit', $data);
    }

    /**
     * Update a blog.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'   => 'nullable|string|max:255',
            'url'    => 'nullable|string|max:255',
            'image'  => 'nullable|image|max:2048',
            'status' => 'nullable|integer|in:0,1',
        ]);

        try {
            DB::beginTransaction();

            $blog = LandingBlog::findOrFail($id);
            $blog->title = $request->title;
            $blog->description = $request->description;
            $blog->date = $request->date;
            $blog->url = $request->url;
            $blog->status = (int) $request->input('status', STATUS_ACTIVE);

            if ($request->hasFile('image')) {
                $fileId = $blog->image;

                // Reuse existing FileManager record if possible
                if ($fileId) {
                    $existingFile = FileManager::where('id', $fileId)->first();
                    if ($existingFile) {
                        $existingFile->removeFile();
                        $uploaded = $existingFile->upload('landing-blogs', $request->image, '', $existingFile->id);
                    } else {
                        $newFile = new FileManager();
                        $uploaded = $newFile->upload('landing-blogs', $request->image);
                    }
                } else {
                    $newFile = new FileManager();
                    $uploaded = $newFile->upload('landing-blogs', $request->image);
                }

                if (is_null($uploaded)) {
                    throw new \Exception(getMessage(SOMETHING_WENT_WRONG));
                }

                $blog->image = $uploaded->id;
            }

            $blog->save();

            DB::commit();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    /**
     * Delete a blog.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $blog = LandingBlog::findOrFail($id);

            // Remove associated image from FileManager
            if ($blog->image) {
                $file = FileManager::find($blog->image);
                if ($file) {
                    $file->removeFile();
                    $file->delete();
                }
            }

            $blog->delete();

            DB::commit();
            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }
}
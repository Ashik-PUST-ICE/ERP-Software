<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AutoPost\SuperAdmin\PageRequest;
use App\Models\FileManager;
use App\Models\Page;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    use General;
    public function index(Request $request)
    {
        $data['title'] = 'Page List';
        $data['activePages'] = 'active';
        $data['showPagesMenu'] = 'show';
        $data['subPagesActiveClass'] = 'active';
        $data['pageCount'] = Page::count();

        return view('auto_posts.super_admin.page.list', $data);
    }

    public function getPages(Request $request)
    {
        $pages = Page::query()->orderBy('id', 'DESC');

        return datatables($pages)
            ->addIndexColumn()
            ->addColumn('sl', function () {
                static $count = 0;
                $start = (int) request()->input('start', 0);
                return $start + (++$count);
            })
            ->addColumn('slug', function ($page) {
                return '<a href="' . url($page->slug) . '" target="_blank">' . url($page->slug) . '</a>';
            })
            ->addColumn('action', function ($page) {
                return
                    '<div class="inline-flex">' .
                        '<div class="dropdown options-area">' .
                            '<a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' .
                                '<i class="fa-solid fa-ellipsis"></i>' .
                            '</a>' .
                            '<ul class="dropdown-menu dropdown-menu-end">' .
                                '<li>' .
                                    '<a class="dropdown-item" href="' . route('super_admin.setting.page.edit', $page->uuid) . '">' .
                                        __('Edit') .
                                    '</a>' .
                                '</li>' .
                                '<li>' .
                                    '<a class="dropdown-item text-danger delete-item" href="javascript:void(0)" ' .
                                        'data-url="' . route('super_admin.setting.page.delete', $page->uuid) . '" ' .
                                        'data-redirect="' . route('super_admin.setting.page.index') . '">' .
                                        __('Delete') .
                                    '</a>' .
                                '</li>' .
                            '</ul>' .
                        '</div>' .
                    '</div>';
            })
            ->rawColumns(['slug', 'action'])
            ->make(true);
    }

    public function store(PageRequest $request)
    {
        if(Page::where('slug', $request->slug)->exists()) {
            $slug = $request->slug.'-'.time();
        }else{
            $slug = $request->slug;
        }

        $data = [
            'en_title' => $request->title,
            'en_description' => $request->en_description,
            'slug' => $slug,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ];

        if ($request->hasFile('og_image')) {
            $file = new FileManager();
            $uploaded = $file->upload('meta', $request->og_image);
            if ($uploaded) {
                $data['og_image'] = $uploaded->id;
            }
        }

        Page::create($data);

        return response()->json([
            'status' => true,
            'message' => __('Created Successful'),
            'redirect' => route('super_admin.setting.page.index')
        ]);
    }

    public function edit($uuid)
    {
        $data['page'] = Page::where('uuid', $uuid)->first();
        if($data['page']) {
            $data['title'] = 'Edit Page';
            $data['activePages'] = 'active';
            $data['showPagesMenu'] = 'show';
            $data['subPagesActiveClass'] = 'active';

            return view('auto_posts.super_admin.page.edit', $data);
        }

        $this->showToastrMessage('error', __('Page not found!'));
        return redirect()->back();
    }

    public function update(PageRequest $request, $uuid)
    {
        $page = Page::where('uuid', $uuid)->first();

        $request->validate([
            'title' => 'required|unique:pages,en_title,' . $page->id,
        ]);

        if(Page::where('slug', $request->slug)->exists()) {
            $slug = $request->slug.'-'.time();
        }else{
            $slug = $request->slug;
        }

        if($page) {
            $data = [
                'en_title' => $request->title ,
                'en_description' => $request->en_description,
                'slug' => $slug,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
            ];

            if ($request->hasFile('og_image')) {
                $fileId = $page->og_image;
                if ($fileId && is_numeric($fileId)) {
                    $existingFile = FileManager::find($fileId);
                    if ($existingFile) {
                        $existingFile->removeFile();
                        $uploaded = $existingFile->upload('meta', $request->og_image, '', $existingFile->id);
                    } else {
                        $file = new FileManager();
                        $uploaded = $file->upload('meta', $request->og_image);
                    }
                } else {
                    $file = new FileManager();
                    $uploaded = $file->upload('meta', $request->og_image);
                }
                if ($uploaded) {
                    $data['og_image'] = $uploaded->id;
                }
            }

            $update = $page->update($data);

            if(!empty($update)) {
                return response()->json([
                    'status' => true,
                    'message' => __('Page successfully updated'),
                    'redirect' => route('super_admin.setting.page.index')
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => __('Something went wrong!')
        ]);
    }

    public function delete($uuid)
    {
        $page = Page::where('uuid', $uuid)->delete();
        if(!empty($page)) {
            if(request()->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => __('Page successfully deleted'),
                    'redirect' => route('super_admin.setting.page.index')
                ]);
            }
            $this->showToastrMessage('success', __('Page successfully deleted'));
            return redirect()->back();
        }

        if(request()->ajax()) {
            return response()->json([
                'status' => false,
                'message' => __('Something went wrong!')
            ]);
        }
        $this->showToastrMessage('error', __('Something went wrong!'));
        return redirect()->back();
    }

    public function pageShow($slug)
    {

        $page = Page::where('slug', $slug)->first();

        if (!$page) {
            $this->showToastrMessage('error', __('Page not found'));
            return redirect()->back();
        }

        $navPages = Page::orderBy('id')->get(['en_title', 'slug']);

        return view('frontend.page', [

            'pageTitle' => __($page->en_title),
            'page' => $page,
            'navPages' => $navPages,
        ]);
    }

}
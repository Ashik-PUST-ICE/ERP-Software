<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    use General;

    /**
     * Frontend helper: Footer Left menus (type=3, active only).
     */
    public static function footerLeftMenus()
    {
        return Menu::query()
            ->where('type', 3)
            ->where('status', 1)
            ->orderBy('id')
            ->get();
    }

    /**
     * Frontend helper: Footer Right menus (type=4, active only).
     */
    public static function footerRightMenus()
    {
        return Menu::query()
            ->where('type', 4)
            ->where('status', 1)
            ->orderBy('id')
            ->get();
    }

    public function staticMenu()
    {
        $data['title'] = 'Static Menu List';
        $data['activeMenus'] = 'active';
        $data['showMenusMenu'] = 'show';
        $data['subStaticMenusActiveClass'] = 'active';

        return view('auto_posts.super_admin.menu.static-menu-list', $data);
    }

    public function staticMenuStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,NULL,id,type,1',
        ]);

        $slug = Str::slug($request->name);
        if (Menu::where('slug', $slug)->where('type', 1)->exists()) {
            $slug = $slug . '-' . time();
        }

        $menu = new Menu();
        $menu->name = $request->name;
        $menu->slug = $slug;
        $menu->url = $request->filled('link') ? $request->link : null;
        $menu->type = 1;
        $menu->status = $request->status;
        $menu->save();

        return response()->json([
            'status' => true,
            'message' => __('Created Successful'),
            'redirect' => route('super_admin.setting.menu.static')
        ]);
    }

    public function getStaticMenus(Request $request)
    {
        $menus = Menu::query()->where('type', 1);

        return datatables($menus)
            ->addIndexColumn()
            ->addColumn('sl', function () {
                static $count = 0;
                $start = (int) request()->input('start', 0);
                return $start + (++$count);
            })
            ->addColumn('url', function ($menu) {
                if ($menu->url) {
                    return '<a href="' . url($menu->url) . '" target="_blank">' . url($menu->url) . '</a>';
                }
                return $menu->slug;
            })
            ->addColumn('status', function ($menu) {
                if ($menu->status == 1) {
                    return '<span class="">' . __('Active') . '</span>';
                }
                return '<span class="">' . __('Deactivated') . '</span>';
            })
            ->addColumn('action', function ($menu) {
                return
                    '<div class="inline-flex">' .
                        '<div class="dropdown options-area">' .
                            '<a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' .
                                '<i class="fa-solid fa-ellipsis"></i>' .
                            '</a>' .
                            '<ul class="dropdown-menu dropdown-menu-end">' .
                                '<li>' .
                                    '<a class="dropdown-item edit-static-menu" href="javascript:void(0)" ' .
                                        'data-item="' . htmlspecialchars(json_encode([
                                            'slug' => $menu->slug,
                                            'name' => $menu->name,
                                            'url' => $menu->url,
                                            'status' => $menu->status
                                        ])) . '" ' .
                                        'data-updateurl="' . route('super_admin.setting.menu.static.update', $menu->slug) . '">' .
                                        __('Edit') .
                                    '</a>' .
                                '</li>' .
                                '<li>' .
                                    '<a class="dropdown-item text-danger delete-item" href="javascript:void(0)" ' .
                                        'data-url="' . route('super_admin.setting.menu.static.delete', $menu->slug) . '" ' .
                                        'data-redirect="' . route('super_admin.setting.menu.static') . '">' .
                                        __('Delete') .
                                    '</a>' .
                                '</li>' .
                            '</ul>' .
                        '</div>' .
                    '</div>';
            })
            ->rawColumns(['url', 'status', 'action'])
            ->make(true);
    }

    public function staticMenuUpdate(Request $request, $slug)
    {
        $menu = Menu::where('slug', $slug)->firstOrFail();
        $menu->name = $request->name;
        $menu->url = $request->filled('link') ? $request->link : null;
        $menu->status = $request->status;
        $menu->save();

        return response()->json([
            'status' => true,
            'message' => __('Updated Successful'),
            'redirect' => route('super_admin.setting.menu.static')
        ]);
    }


    



    public function staticMenuDelete($slug)
    {
        Menu::where('slug', $slug)->delete();

        return response()->json([
            'status' => true,
            'message' => __('Deleted Successful'),
            'redirect' => route('super_admin.setting.menu.static')
        ]);
    }

    public function dynamicMenu()
    {
        $data['title'] = 'Dynamic Menu List';
        $data['activeMenus'] = 'active';
        $data['showMenusMenu'] = 'show';
        $data['subDynamicMenusActiveClass'] = 'active';
        $data['urls'] = Page::all();

        return view('auto_posts.super_admin.menu.dynamic-menu-list', $data);
    }

    public function getDynamicMenus(Request $request)
    {
        $menus = Menu::query()->where('type', 2);

        return datatables($menus)
            ->addIndexColumn()
            ->addColumn('sl', function () {
                static $count = 0;
                $start = (int) request()->input('start', 0);
                return $start + (++$count);
            })
            ->addColumn('page_url', function ($menu) {
                $page = Page::find((int) $menu->url);
                if ($page) {
                    $pageUrl = url('/page/' . $page->slug);
                    return '<a href="' . e($pageUrl) . '" target="_blank" class="text-primary">' . e($pageUrl) . '</a>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('status', function ($menu) {
                if ($menu->status == 1) {
                    return '<span class="">' . __('Active') . '</span>';
                }
                return '<span class="">' . __('Deactivated') . '</span>';
            })
            ->addColumn('action', function ($menu) {
                $page = Page::find($menu->url);
                return
                    '<div class="inline-flex">' .
                        '<div class="dropdown options-area">' .
                            '<a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' .
                                '<i class="fa-solid fa-ellipsis"></i>' .
                            '</a>' .
                            '<ul class="dropdown-menu dropdown-menu-end">' .
                                '<li>' .
                                    '<a class="dropdown-item edit-menu" href="javascript:void(0)" ' .
                                        'data-item="' . htmlspecialchars(json_encode([
                                            'id' => $menu->id,
                                            'name' => $menu->name,
                                            'url' => (int) $menu->url,
                                            'status' => $menu->status
                                        ])) . '" ' .
                                        'data-updateurl="' . route('super_admin.setting.menu.dynamic.update', $menu->id) . '">' .
                                        __('Edit') .
                                    '</a>' .
                                '</li>' .
                                '<li>' .
                                    '<a class="dropdown-item text-danger delete-item" href="javascript:void(0)" ' .
                                        'data-url="' . route('super_admin.setting.menu.dynamic.delete', $menu->id) . '" ' .
                                        'data-redirect="' . route('super_admin.setting.menu.dynamic') . '">' .
                                        __('Delete') .
                                    '</a>' .
                                '</li>' .
                            '</ul>' .
                        '</div>' .
                    '</div>';
            })
            ->rawColumns(['page_url', 'status', 'action'])
            ->make(true);
    }

    public function dynamicMenuStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,NULL,id,type,2',
        ]);

        $menu = new Menu();
        $menu->name = $request->name;
        $menu->url = $request->url;
        $menu->type = 2;
        $menu->status = $request->status;
        $menu->save();

        return response()->json([
            'status' => true,
            'message' => __('Created Successful'),
            'redirect' => route('super_admin.setting.menu.dynamic')
        ]);
    }

    public function dynamicMenuUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,'.$id.',id,type,2',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->name = $request->name;
        $menu->url = $request->url;
        $menu->type = 2;
        $menu->status = $request->status;
        $menu->save();

        return response()->json([
            'status' => true,
            'message' => __('Updated Successful'),
            'redirect' => route('super_admin.setting.menu.dynamic')
        ]);
    }

    public function dynamicMenuDelete($id)
    {
        Menu::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => __('Deleted Successful'),
            'redirect' => route('super_admin.setting.menu.dynamic')
        ]);
    }
   
    public function footerCompanyMenu()
    {
        $data['title'] = 'Footer Company Menu List';
        $data['activeMenus'] = 'active';
        $data['showMenusMenu'] = 'show';
        $data['subFooterCompanyMenusActiveClass'] = 'active';
        $data['menus'] = Menu::where('type', 3)->paginate(25);
        $data['urls'] = Page::all();

        return view('auto_posts.super_admin.menu.footer-company-menu-list', $data);
    }

    public function footerCompanyMenuStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,NULL,id,type,3',
        ]);

        $menu = new Menu();
        $menu->name = $request->name;
        $menu->url = $request->filled('link') ? $request->link : null;
        $menu->type = 3;
        $menu->status = $request->status;
        $menu->save();

        return response()->json([
            'status' => true,
            'message' => __('Created Successful'),
            'redirect' => route('super_admin.setting.menu.footer-left'),
        ]);
    }

    public function footerCompanyMenuUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,'.$id.',id,type,3',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->name = $request->name;
        $menu->url = $request->filled('link') ? $request->link : null;
        $menu->type = 3;
        $menu->status = $request->status;
        $menu->save();

        return response()->json([
            'status' => true,
            'message' => __('Updated Successful'),
            'redirect' => route('super_admin.setting.menu.footer-left'),
        ]);
    }

    public function footerCompanyMenuDelete($id)
    {
        Menu::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => __('Deleted Successful'),
            'redirect' => route('super_admin.setting.menu.footer-left'),
        ]);
    }
   
    public function footerSupportMenu()
    {
        $data['title'] = 'Footer Support Menu List';
        $data['activeMenus'] = 'active';
        $data['showMenusMenu'] = 'show';
        $data['subFooterSupportMenusActiveClass'] = 'active';
        $data['menus'] = Menu::where('type', 4)->paginate(25);
        $data['urls'] = Page::all();

        return view('auto_posts.super_admin.menu.footer-support-menu-list', $data);
    }

    public function footerSupportMenuStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,NULL,id,type,4',
        ]);

        $menu = new Menu();
        $menu->name = $request->name;
        $menu->url = $request->filled('link') ? $request->link : null;
        $menu->type = 4;
        $menu->status = $request->status;
        $menu->save();

        return response()->json([
            'status' => true,
            'message' => __('Created Successful'),
            'redirect' => route('super_admin.setting.menu.footer-right'),
        ]);
    }

    public function footerSupportMenuUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,'.$id.',id,type,4',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->name = $request->name;
        $menu->url = $request->filled('link') ? $request->link : null;
        $menu->type = 4;
        $menu->status = $request->status;
        $menu->save();

        return response()->json([
            'status' => true,
            'message' => __('Updated Successful'),
            'redirect' => route('super_admin.setting.menu.footer-right'),
        ]);
    }

    public function footerSupportMenuDelete($id)
    {
        Menu::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => __('Deleted Successful'),
            'redirect' => route('super_admin.setting.menu.footer-right'),
        ]);
    }

    // AJAX DataTable methods for footer menus
    public function getFooterCompanyMenus(Request $request)
    {
        $menus = Menu::query()->where('type', 3);

        return datatables($menus)
            ->addIndexColumn()
            ->addColumn('sl', function () {
                static $count = 0;
                $start = (int) request()->input('start', 0);
                return $start + (++$count);
            })
            ->addColumn('url', function ($menu) {
                if ($menu->url) {
                    return '<a href="' . url($menu->url) . '" target="_blank">' . url($menu->url) . '</a>';
                }
                return '-';
            })
            ->addColumn('status', function ($menu) {
                if ($menu->status == 1) {
                    return '<span class="">' . __('Active') . '</span>';
                }
                return '<span class="">' . __('Deactivated') . '</span>';
            })
            ->addColumn('action', function ($menu) {
                return
                    '<div class="inline-flex">' .
                        '<div class="dropdown options-area">' .
                            '<a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' .
                                '<i class="fa-solid fa-ellipsis"></i>' .
                            '</a>' .
                            '<ul class="dropdown-menu dropdown-menu-end">' .
                                '<li>' .
                                    '<a class="dropdown-item edit-footer-company-menu" href="javascript:void(0)" ' .
                                        'data-item="' . htmlspecialchars(json_encode([
                                            'id' => $menu->id,
                                            'name' => $menu->name,
                                            'url' => $menu->url,
                                            'status' => $menu->status
                                        ])) . '" ' .
                                        'data-updateurl="' . route('super_admin.setting.menu.footer-left.update', $menu->id) . '">' .
                                        __('Edit') .
                                    '</a>' .
                                '</li>' .
                                '<li>' .
                                    '<a class="dropdown-item text-danger delete-item" href="javascript:void(0)" ' .
                                        'data-url="' . route('super_admin.setting.menu.footer-left.delete', $menu->id) . '" ' .
                                        'data-redirect="' . route('super_admin.setting.menu.footer-left') . '">' .
                                        __('Delete') .
                                    '</a>' .
                                '</li>' .
                            '</ul>' .
                        '</div>' .
                    '</div>';
            })
            ->rawColumns(['url', 'status', 'action'])
            ->make(true);
    }

    public function getFooterSupportMenus(Request $request)
    {
        $menus = Menu::query()->where('type', 4);

        return datatables($menus)
            ->addIndexColumn()
            ->addColumn('sl', function () {
                static $count = 0;
                $start = (int) request()->input('start', 0);
                return $start + (++$count);
            })
            ->addColumn('url', function ($menu) {
                if ($menu->url) {
                    return '<a href="' . url($menu->url) . '" target="_blank">' . url($menu->url) . '</a>';
                }
                return '-';
            })
            ->addColumn('status', function ($menu) {
                if ($menu->status == 1) {
                    return '<span class="">' . __('Active') . '</span>';
                }
                return '<span class="">' . __('Deactivated') . '</span>';
            })
            ->addColumn('action', function ($menu) {
                return
                    '<div class="inline-flex">' .
                        '<div class="dropdown options-area">' .
                            '<a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' .
                                '<i class="fa-solid fa-ellipsis"></i>' .
                            '</a>' .
                            '<ul class="dropdown-menu dropdown-menu-end">' .
                                '<li>' .
                                    '<a class="dropdown-item edit-footer-support-menu" href="javascript:void(0)" ' .
                                        'data-item="' . htmlspecialchars(json_encode([
                                            'id' => $menu->id,
                                            'name' => $menu->name,
                                            'url' => $menu->url,
                                            'status' => $menu->status
                                        ])) . '" ' .
                                        'data-updateurl="' . route('super_admin.setting.menu.footer-right.update', $menu->id) . '">' .
                                        __('Edit') .
                                    '</a>' .
                                '</li>' .
                                '<li>' .
                                    '<a class="dropdown-item text-danger delete-item" href="javascript:void(0)" ' .
                                        'data-url="' . route('super_admin.setting.menu.footer-right.delete', $menu->id) . '" ' .
                                        'data-redirect="' . route('super_admin.setting.menu.footer-right') . '">' .
                                        __('Delete') .
                                    '</a>' .
                                '</li>' .
                            '</ul>' .
                        '</div>' .
                    '</div>';
            })
            ->rawColumns(['url', 'status', 'action'])
            ->make(true);
    }


}
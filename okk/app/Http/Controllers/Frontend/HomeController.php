<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Http\Services\Frontend\HomeService;
use App\Http\Services\PackageService;
use App\Models\Batch;
use App\Models\CommitteeCategory;

use App\Models\Department;
use App\Models\LandingBlog;
use App\Models\Menu;
use App\Models\User;
use App\Models\Page;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use ResponseTrait;
    public $homeService;
    protected $packageService;

    public function __construct(HomeService $homeService, PackageService $packageService)
    {
        $this->homeService = $homeService;
        $this->packageService = $packageService;
    }

    public function index(Request $request)
    {
        // Only show active packages on the landing page
        $packages = $this->packageService->getActiveAll();

        // Landing page blogs: only active blogs, latest first, limit 3
        $blogs = LandingBlog::where('status', STATUS_ACTIVE)
            ->orderBy('id', 'DESC')
            ->take(3)
            ->get();

        // Dynamic menu items (type = 2, status = 1) with page – for nav labels and landing sections
        $dynamicMenuItems = Menu::where('type', 2)
            ->where('status', 1)
        
            ->orderBy('id')
            ->get()
            ;

        // Static menus (type = 1, status = 1) for navigation
        $staticMenus = Menu::where('type', 1)
            ->where('status', 1)
            ->orderBy('id')
            ->get();

        // All pages for header dropdown navigation
        $navPages = Page::orderBy('id')->get(['en_title', 'slug']);

        return view('auto_posts.frontend.index', compact('packages', 'blogs', 'dynamicMenuItems', 'staticMenus', 'navPages'));
    }

    public function page($slug)
    {
        $data['pageTitle'] = __(getOption($slug . '_title'));
        $data['description'] = getOption($slug . '_description');
        return view('frontend.page', $data);
    }

    public function blogShow(LandingBlog $blog)
    {
        return view('auto_posts.frontend.blog-details', compact('blog'));
    }
}
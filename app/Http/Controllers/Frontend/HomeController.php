<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Services\Frontend\HomeService;
use App\Http\Services\PackageService;
use App\Models\User;
use App\Models\Page;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
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
        $blogs = collect();
        $dynamicMenuItems = collect();
        $staticMenus = collect();
        $navPages = collect();

        return view('auto_posts.frontend.index', compact('packages', 'blogs', 'dynamicMenuItems', 'staticMenus', 'navPages'));
    }

    public function page($slug)
    {
        $data['pageTitle'] = __(getOption($slug . '_title'));
        $data['description'] = getOption($slug . '_description');
        return view('frontend.page', $data);
    }

    public function blogShow($blog)
    {
        return redirect()->route('frontend.index');
    }
}
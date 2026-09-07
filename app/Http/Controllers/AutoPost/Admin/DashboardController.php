<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\DashboardService;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ResponseTrait;

    public $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index()
    {
        return redirect()->route('admin.hrm.dashboard');
    }

    public function latestPostsDatatable(Request $request)
    {
        return datatables(collect([]))
            ->addIndexColumn()
            ->addColumn('platform_name', function () {
                return '-';
            })
            ->addColumn('account_name', function () {
                return '-';
            })
            ->addColumn('schedule_time', function () {
                return '-';
            })
            ->addColumn('post_type', function () {
                return '-';
            })
            ->rawColumns(['platform_name'])
            ->make(true);
    }
}
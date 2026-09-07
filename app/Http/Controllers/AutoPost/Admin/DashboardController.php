<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\DashboardService;
use App\Models\PostHistory;
use App\Models\ScheduledPost;
use App\Models\SocialMediaAccount;
use App\Models\SocialMediaConfig;
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
        // Platforms from config (active); fallback to all 7 if none configured
        $platforms = SocialMediaConfig::where('is_active', true)
            ->orderBy('platform')
            ->pluck('platform')
            ->map(fn ($p) => strtolower($p))
            ->unique()
            ->values()
            ->toArray();
        if (empty($platforms)) {
            $platforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok', 'threads'];
        }

        $since = Carbon::now()->subDays(30);

        $tenantId = auth()->user()->tenant_id;

        $postCounts = PostHistory::where('success', true)
            ->where('posted_at', '>=', $since)
            ->when($tenantId, fn($q) => $q->where('post_histories.tenant_id', $tenantId))
            ->select('platform', DB::raw('count(*) as total'))
            ->groupBy('platform')
            ->pluck('total', 'platform')
            ->toArray();

        $totalPosts = array_sum($postCounts);

        // Account stats only for configured platforms (config + account – single source)
        $accountStats = SocialMediaAccount::whereIn('platform', $platforms)
            ->when($tenantId, fn($q) => $q->where('social_media_accounts.tenant_id', $tenantId))
            ->select(
                'platform',
                DB::raw('count(*) as total'),
                DB::raw('sum(case when is_active = 1 then 1 else 0 end) as active')
            )
            ->groupBy('platform')
            ->get()
            ->keyBy(fn ($row) => strtolower($row->platform));

        $totalAccounts  = SocialMediaAccount::whereIn('platform', $platforms)
            ->when($tenantId, fn($q) => $q->where('social_media_accounts.tenant_id', $tenantId))
            ->count();
        $activeAccounts = SocialMediaAccount::whereIn('platform', $platforms)
            ->where('is_active', true)
            ->when($tenantId, fn($q) => $q->where('social_media_accounts.tenant_id', $tenantId))
            ->count();

        $chartColors = [
            'facebook'  => '#FF4F02',
            'instagram' => '#02BCFF',
            'twitter'   => '#FFC402',
            'linkedin'  => '#0FA958',
            'youtube'   => '#0D0D0D',
            'tiktok'    => '#6BD096',
            'threads'   => '#6B02FF',
        ];

        // Use fixed platform order so all 7 platforms show + correct mapping (fixes LinkedIn data mismatch)
        $chartLabels = [];
        $chartSeries = [];
        foreach ($platforms as $platform) {
            $chartLabels[] = ucfirst($platform);
            $stat = $accountStats[strtolower($platform)] ?? null;
            $chartSeries[] = (int) ($stat->total ?? 0);
        }

        return view('auto_posts.admin.dashboard', [
            'pageTitle'       => 'Dashboard',
            'activeDashboard' => 'active',
            'platforms'       => $platforms,
            'postCounts'      => $postCounts,
            'totalPosts'      => $totalPosts,
            'accountStats'    => $accountStats,
            'totalAccounts'   => $totalAccounts,
            'activeAccounts'  => $activeAccounts,
            'chartLabels'     => $chartLabels,
            'chartSeries'     => $chartSeries,
            'chartColors'     => $chartColors,
        ]);
    }

    public function latestPostsDatatable(Request $request)
    {
        $query = ScheduledPost::with(['socialMediaAccount', 'user'])
            ->select('scheduled_posts.*');

        if ($request->filled('platform') && $request->platform !== 'all') {
            $query->whereHas('socialMediaAccount', function ($q) use ($request) {
                $q->where('platform', $request->platform);
            });
        }

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('platform_name', function ($post) {
                $icons = [
                    'facebook'  => 'fa-facebook-f',
                    'twitter'   => 'fa-x-twitter',
                    'instagram' => 'fa-instagram',
                    'linkedin'  => 'fa-linkedin-in',
                    'youtube'   => 'fa-youtube',
                    'tiktok'    => 'fa-tiktok',
                    'threads'   => 'fa-threads',
                ];
                $icon = $icons[$post->socialMediaAccount->platform ?? ''] ?? 'fa-globe';
                return '<span class="d-flex align-items-center gap-1">
                            <i class="fa-brands ' . $icon . '"></i>
                            ' . ucfirst($post->socialMediaAccount->platform ?? '') . '
                        </span>';
            })
            ->addColumn('account_name', function ($post) {
                return $post->socialMediaAccount 
                    ? ($post->socialMediaAccount->username ?? $post->socialMediaAccount->account_id) 
                    : '-';
            })
            ->addColumn('schedule_time', function ($post) {
                if (!$post->scheduled_time) {
                    return '-';
                }
                return formatDateToCustomer($post->scheduled_time, 'd/m/Y - h:i A', 'UTC', getAppTimeZone());
            })
            ->addColumn('post_type', function ($post) {
                return ucfirst($post->post_type ?? 'feed');
            })
            ->rawColumns(['platform_name'])
            ->make(true);
    }
}
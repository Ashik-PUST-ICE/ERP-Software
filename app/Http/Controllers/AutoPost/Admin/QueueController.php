<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class QueueController extends Controller
{
    public function status()
    {
        $pending = 0;
        $failed = 0;
        $ready = true;

        try {
            $pending = DB::table(config('queue.connections.database.table', 'jobs'))->count();
            $failed = DB::table(config('queue.failed.table', 'failed_jobs'))->count();
        } catch (\Throwable $exception) {
            $ready = false;
        }

        $failedJobs = [];
        if ($ready && $failed > 0) {
            $failedJobs = DB::table(config('queue.failed.table', 'failed_jobs'))
                ->latest('failed_at')
                ->limit(10)
                ->get(['id', 'queue', 'failed_at', 'exception'])
                ->map(fn ($job) => [
                    'id' => $job->id,
                    'queue' => $job->queue,
                    'failed_at' => $job->failed_at ? date('d M Y, h:i A', strtotime($job->failed_at)) : '',
                    'exception' => Str::limit((string) $job->exception, 120),
                ])
                ->values();
        }

        return response()->json([
            'success' => true,
            'connection' => config('queue.default'),
            'pending' => $pending,
            'failed' => $failed,
            'failed_jobs' => $failedJobs,
            'ready' => $ready,
            'worker_running' => Cache::has('admin_queue_worker_running'),
            'command' => 'php artisan queue:work --queue=default --tries=3 --timeout=120',
            'checked_at' => now()->format('d M Y, h:i A'),
        ]);
    }

    public function start()
    {
        if (Cache::has('admin_queue_worker_running')) {
            return response()->json(['success' => false, 'message' => __('A queue worker is already running.')], 409);
        }

        Cache::put('admin_queue_worker_running', true, now()->addMinutes(2));

        try {
            $process = new Process([
                PHP_BINARY,
                base_path('artisan'),
                'queue:work',
                'database',
                '--queue=default',
                '--stop-when-empty',
                '--tries=3',
                '--timeout=120',
                '--no-interaction',
            ], base_path());
            $process->disableOutput();
            $process->start();
        } catch (\Throwable $exception) {
            Cache::forget('admin_queue_worker_running');
            report($exception);

            return response()->json(['success' => false, 'message' => __('Unable to start the queue worker.')], 500);
        }

        return response()->json([
            'success' => true,
            'message' => __('Queue worker started. It will stop automatically when the queue is empty.'),
        ]);
    }

    public function retryFailed(int $id)
    {
        Artisan::call('queue:retry', ['id' => $id]);
        return response()->json(['success' => true, 'message' => __('Failed job queued for retry.')]);
    }

    public function forgetFailed(int $id)
    {
        Artisan::call('queue:forget', ['id' => $id]);
        return response()->json(['success' => true, 'message' => __('Failed job removed.')]);
    }
}

<?php

namespace App\Providers;

use App\Models\CommitteeCategory;
use App\Models\Language;
use App\Models\Setting;
use App\Models\SocialMediaConfig;
use Illuminate\Database\Schema\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);

        try {
            $connection = DB::connection()->getPdo();
            if ($connection) {
                $allOptions = [];

                $allOptions['settings'] = Setting::all()->pluck('option_value', 'option_key')->toArray();

                config($allOptions);

                // Respect the language chosen in session if it exists;
                // otherwise fall back to default active language, then first language.
                $language = Language::where('iso_code', session()->get('local'))->first();

                if (!$language) {
                    $language = Language::where('default', ACTIVE)->first() ?: Language::first();
                    if ($language) {
                        session(['local' => $language->iso_code]);
                    }
                }

                if (session()->has('local')) {
                    App::setLocale(session()->get('local'));
                }
                config(['app.defaultLanguage' => getDefaultLanguage()]);
                config(['app.currencySymbol' => getCurrencySymbol()]);
                config(['app.isoCode' => getIsoCode()]);
                config(['app.currencyPlacement' => getCurrencyPlacement()]);
                config(['app.debug' => filter_var(env('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN) === true ? true : getOption('app_debug', false)]);
                config(['app.timezone' => getOption('app_timezone', 'UTC')]);

                config(['services.google.client_id' => getOption('google_client_id')]);
                config(['services.google.client_secret' => getOption('google_client_secret')]);
                config(['services.google.redirect' => url('auth/google/callback')]);

                // Get Facebook config from social_media_configs table
                $facebookConfig = SocialMediaConfig::where('platform', 'facebook')->where('is_active', true)->first();
                if ($facebookConfig) {
                    config(['services.facebook.client_id' => $facebookConfig->app_id]);
                    config(['services.facebook.client_secret' => $facebookConfig->app_secret]);
                    config(['services.facebook.redirect' => url('autopost/admin/social/account/list/facebook/callback')]);
                }

                // Get Instagram config from social_media_configs table (uses Facebook Login OAuth)
                $instagramConfig = SocialMediaConfig::where('platform', 'instagram')->where('is_active', true)->first();
                if ($instagramConfig) {
                    config(['services.instagram.client_id' => $instagramConfig->app_id]);
                    config(['services.instagram.client_secret' => $instagramConfig->app_secret]);
                    config(['services.instagram.redirect' => url('autopost/admin/social/account/list/instagram/callback')]);
                }

                // Get Threads config from social_media_configs table (uses Facebook Login OAuth)
                $threadsConfig = SocialMediaConfig::where('platform', 'threads')->where('is_active', true)->first();
                if ($threadsConfig) {
                    config(['services.threads.client_id' => $threadsConfig->app_id]);
                    config(['services.threads.client_secret' => $threadsConfig->app_secret]);
                    config(['services.threads.redirect' => url('autopost/admin/social/account/list/threads/callback')]);
                }

                // Get YouTube config (uses Google OAuth)
                $youtubeConfig = SocialMediaConfig::where('platform', 'youtube')->where('is_active', true)->first();
                if ($youtubeConfig) {
                    config(['services.youtube.client_id' => $youtubeConfig->app_id]);
                    config(['services.youtube.client_secret' => $youtubeConfig->app_secret]);
                    config(['services.youtube.redirect' => url('autopost/admin/social/account/list/youtube/callback')]);
                }

                // Get LinkedIn config
                $linkedinConfig = SocialMediaConfig::where('platform', 'linkedin')->where('is_active', true)->first();
                if ($linkedinConfig) {
                    config(['services.linkedin.client_id' => $linkedinConfig->app_id]);
                    config(['services.linkedin.client_secret' => $linkedinConfig->app_secret]);
                    config(['services.linkedin.redirect' => $linkedinConfig->redirect_uri ?: url('autopost/admin/social/account/list/linkedin/callback')]);
                }

                // Get Twitter config
                $twitterConfig = SocialMediaConfig::where('platform', 'twitter')->where('is_active', true)->first();
                if ($twitterConfig) {
                    config(['services.twitter.client_id' => $twitterConfig->app_id]);
                    config(['services.twitter.client_secret' => $twitterConfig->app_secret]);
                    config(['services.twitter.redirect' => url('autopost/admin/social/account/list/twitter/callback')]);
                }

                // Get TikTok config
                $tiktokConfig = SocialMediaConfig::where('platform', 'tiktok')->where('is_active', true)->first();
                if ($tiktokConfig) {
                    config(['services.tiktok.client_id' => $tiktokConfig->app_id]);
                    config(['services.tiktok.client_secret' => $tiktokConfig->app_secret]);
                    config(['services.tiktok.redirect' => $tiktokConfig->redirect_uri ?: url('autopost/admin/social/account/list/tiktok/callback')]);
                }

                if (!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == 1) {
                    config(['recaptchav3.sitekey' => getOption('google_recaptcha_site_key')]);
                    config(['recaptchav3.secret' => getOption('google_recaptcha_secret_key')]);
                }

                if (getOption('pusher_status', 0)) {
                    config(['broadcasting.connections.pusher.key' => getOption('pusher_app_key', 'null')]);
                    config(['broadcasting.connections.pusher.secret' => getOption('pusher_app_secret', 'null')]);
                    config(['broadcasting.connections.pusher.app_id' => getOption('pusher_app_id', 'null')]);
                    config(['broadcasting.connections.pusher.options.cluster' => getOption('pusher_cluster', 'null')]);
                    config(['broadcasting.default' => 'pusher']);
                } else {
                    config(['broadcasting.default' => 'null']);
                }

                date_default_timezone_set(getOption('app_timezone', 'UTC'));

                Gate::before(function ($user, $ability) {
                    return (($user->is_alumni && $user->role == USER_ROLE_USER) | (is_null($user->created_by) && $user->role == USER_ROLE_SUPER_ADMIN)) ? true : null;
                });
                if (getOption('force_ssl', 0)) {
                    URL::forceScheme('https');
                }
            }
        } catch (\Exception $e) {
            Log::info('Service Provider - ' . $e->getMessage());
        }

    }
}

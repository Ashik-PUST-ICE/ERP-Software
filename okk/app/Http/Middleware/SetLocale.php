<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session
        $locale = session()->get('local');
        
        // If no locale in session, try to get the default language
        if (!$locale) {
            $defaultLanguage = Language::where('default', 1)->first();
            if ($defaultLanguage) {
                $locale = $defaultLanguage->iso_code;
                session(['local' => $locale]);
            }
        }
        
        // Set the application locale
        if ($locale) {
            App::setLocale($locale);
        }
        
        return $next($request);
    }
}
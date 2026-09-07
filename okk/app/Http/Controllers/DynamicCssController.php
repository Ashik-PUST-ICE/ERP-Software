<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class DynamicCssController extends Controller
{
    /**
     * Serve dynamic CSS (theme variables + custom_css) for the given context.
     */
    public function show(string $context): Response
    {
        $allowed = ['admin', 'super_admin', 'frontend', 'user'];
        if (!in_array($context, $allowed, true)) {
            abort(404);
        }

        $view = "dynamic_css.{$context}";
        $css = view($view)->render();

        return response($css, 200, [
            'Content-Type' => 'text/css; charset=UTF-8',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
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
        // Get locale from session, fallback to config default
        $locale = Session::get('locale', config('app.locale', 'en'));

        // Validate locale
        $supportedLocales = ['en', 'am', 'ti'];
        if (! in_array($locale, $supportedLocales)) {
            $locale = 'en';
        }

        // Set the application locale
        App::setLocale($locale);

        return $next($request);
    }
}

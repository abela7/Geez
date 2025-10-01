<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        // Validate the locale
        $supportedLocales = ['en', 'am', 'ti'];
        
        if (!in_array($locale, $supportedLocales)) {
            abort(404);
        }
        
        // Set the application locale
        App::setLocale($locale);
        
        // Store the locale in session for persistence
        Session::put('locale', $locale);
        
        // Redirect back to the previous page
        return redirect()->back()->with('success', __('common.language') . ' changed successfully');
    }
    
    /**
     * Get the current locale
     */
    public function current(): string
    {
        return App::getLocale();
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param $locale
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $locale = null)
    {
        if (!$locale) {
            $locale = in_array($request->header('locale'), config('translatable.locales'))
                ? $request->header('locale')
                : config('translatable.fallback_locale');
        } elseif (!in_array($locale, config('translatable.locales'))) {
            return redirect('/');
        }

        App::setLocale($locale);

        return $next($request);
    }
}

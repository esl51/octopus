<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $this->getLocale();
        if ($locale === config('app.fallback_locale')
            && (Str::startsWith(request()->path(), "$locale/")
                || request()->path() === $locale
            )
        ) {
            return redirect()->to(Str::after(request()->fullUrl(), "/$locale"));
        }
        app()->setLocale($locale);

        return $next($request);
    }

    public function getLocale()
    {
        if (array_key_exists(request()->segment(1), config('app.locales'))) {
            $locale = request()->segment(1);
        } else {
            $locale = config('app.fallback_locale');
        }
        return $locale;
    }
}

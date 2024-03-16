<?php

namespace App\Http\Middleware;

use Closure;

class WkhtmltopdfMiddleware
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
        if (str_contains($request->header('User-Agent'), 'wkhtmltopdf')) {
            $response = $next($request);
            $content = $response->content();

            // Modify the HTML content here to use absolute URLs for external resources
            // For example, you can use regex or DOM manipulation to modify URLs

            $response->setContent($content);
            return $response;
        }

        return $next($request);
    }
}

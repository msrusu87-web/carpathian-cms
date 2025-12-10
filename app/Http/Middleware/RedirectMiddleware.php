<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $url = $request->path();
        
        if ($url !== '/') {
            $url = '/' . $url;
        }
        
        $redirect = Redirect::findByUrl($url);
        
        if ($redirect) {
            $redirect->incrementHits();
            return redirect($redirect->to_url, $redirect->status_code);
        }
        
        return $next($request);
    }
}

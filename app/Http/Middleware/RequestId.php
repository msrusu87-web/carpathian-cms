<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestId
{
    public function handle(Request $request, Closure $next): Response
    {
        $rid = (string) Str::uuid();

        // Make request ID available everywhere (views, logs, etc.).
        $request->attributes->set('rid', $rid);

        // Add to all logs emitted during this request.
        Log::withContext([
            'rid' => $rid,
            'path' => $request->path(),
            'method' => $request->method(),
        ]);

        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Request-Id', $rid);

        return $response;
    }
}

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Laravel Session Debug Route
Route::get('/debug/session', function (Request $request) {
    $sessionData = $request->session()->all();
    $sessionId = $request->session()->getId();
    $token = csrf_token();
    
    $debugInfo = [
        'session_id' => $sessionId,
        'csrf_token' => $token,
        'session_data' => $sessionData,
        'session_driver' => config('session.driver'),
        'session_lifetime' => config('session.lifetime'),
        'session_domain' => config('session.domain'),
        'session_secure' => config('session.secure'),
        'session_same_site' => config('session.same_site'),
        'cookies' => $request->cookies->all(),
        'session_has_token' => $request->session()->has('_token'),
        'session_token_value' => $request->session()->get('_token'),
        'is_https' => $request->secure(),
        'host' => $request->getHost(),
        'full_url' => $request->fullUrl(),
    ];
    
    // Test session write
    $request->session()->put('debug_test', now()->toDateTimeString());
    $request->session()->save();
    
    return response()->json($debugInfo, 200, [], JSON_PRETTY_PRINT);
});

// Test CSRF Token Generation
Route::get('/debug/csrf-test', function () {
    return view('debug.csrf-test');
});

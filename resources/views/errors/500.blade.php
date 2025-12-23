@php
    $rid = request()->attributes->get('rid') ?? request()->header('X-Request-Id');
    $locale = app()->getLocale();
@endphp
<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.error_500') ?? 'Server Error' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="max-w-xl w-full bg-white shadow rounded-xl p-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.error_500') ?? 'Server Error' }}</h1>
        <p class="mt-2 text-gray-600">
            {{ __('messages.something_went_wrong') ?? 'Something went wrong on our side. Please try again in a moment.' }}
        </p>

        <div class="mt-6 flex gap-3">
            <a href="/" class="inline-flex items-center px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700">
                {{ __('messages.go_home') ?? 'Go Home' }}
            </a>
            <a href="/contact" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200">
                {{ __('messages.contact') ?? 'Contact' }}
            </a>
        </div>

        <div class="mt-8 border-t pt-4 text-sm text-gray-500">
            <div><span class="font-semibold">HTTP</span>: 500</div>
            <div><span class="font-semibold">Locale</span>: {{ $locale }}</div>
            @if($rid)
                <div><span class="font-semibold">Request ID</span>: <code class="bg-gray-100 px-2 py-0.5 rounded">{{ $rid }}</code></div>
                <div class="mt-2">
                    Admin diagnostics: open
                    <code class="bg-gray-100 px-2 py-0.5 rounded">/error-diagnostics.php?rid={{ $rid }}&amp;secret=YOUR_SECRET</code>
                </div>
            @endif
        </div>
    </div>
</body>
</html>

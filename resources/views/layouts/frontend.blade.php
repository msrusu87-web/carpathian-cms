<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Carpathian CMS Demo')</title>
    <meta name="description" content="@yield('description', 'Modern CMS with AI integration')">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-slate-900 text-gray-100 min-h-screen flex flex-col">
    @include('partials.navigation')
    
    <main class="flex-grow">
        @yield('content')
    </main>
    
    @php
        $footerWidget = new stdClass();
        $footerWidget->settings = [];
    @endphp
    @include('widgets.footer', ['widget' => $footerWidget])
    
    @stack('scripts')
</body>
</html>

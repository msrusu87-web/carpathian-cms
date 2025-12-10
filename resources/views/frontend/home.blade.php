<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    @if(isset($templateColors))
    <style>
        :root {
            --color-primary: {{ $templateColors['primary'] ?? '#9333ea' }};
            --color-secondary: {{ $templateColors['secondary'] ?? '#6b21a8' }};
            --color-accent: {{ $templateColors['accent'] ?? '#a855f7' }};
            --color-background: {{ $templateColors['background'] ?? '#ffffff' }};
            --color-text: {{ $templateColors['text'] ?? '#1f2937' }};
        }
        
        /* Apply template colors */
        .bg-primary { background-color: var(--color-primary) !important; }
        .bg-secondary { background-color: var(--color-secondary) !important; }
        .text-primary { color: var(--color-primary) !important; }
        .text-secondary { color: var(--color-secondary) !important; }
        .border-primary { border-color: var(--color-primary) !important; }
        
        /* Override Tailwind purple with template colors */
        .bg-purple-600 { background-color: var(--color-primary) !important; }
        .bg-purple-700 { background-color: var(--color-secondary) !important; }
        .text-purple-600 { color: var(--color-primary) !important; }
        .hover\:bg-purple-700:hover { background-color: var(--color-secondary) !important; }
        .hover\:text-purple-600:hover { color: var(--color-primary) !important; }
        .bg-purple-50 { background-color: {{ $templateColors['primary'] ?? '#9333ea' }}10 !important; }
    </style>
    @endif
    
    @if(isset($templateTypography))
    <style>
        body {
            font-family: {{ $templateTypography['font_family'] ?? 'Inter, system-ui, sans-serif' }};
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: {{ $templateTypography['heading_font'] ?? 'Inter, sans-serif' }};
        }
    </style>
    @endif
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <!-- Widgets Area -->
    @if(isset($widgets) && $widgets->count() > 0)
        @foreach($widgets as $widget)
            @if($widget->type === 'hero')
                @include('widgets.hero', ['widget' => $widget])
            @elseif($widget->type === 'features')
                @include('widgets.features', ['widget' => $widget])
            @elseif($widget->type === 'products')
                @include('widgets.products', ['widget' => $widget])
            @elseif($widget->type === 'blog')
                @include('widgets.blog', ['widget' => $widget])
            @elseif($widget->type === 'documentation')
                @include('widgets.documentation', ['widget' => $widget])
            @elseif($widget->type !== 'footer')
                @include('widgets.' . $widget->type, ['widget' => $widget])
            @endif
        @endforeach
    @endif

    <!-- Footer -->
    @php
        $footerWidget = \App\Models\Widget::where('type', 'footer')->where('status', 'active')->first();
    @endphp
    @if($footerWidget)
        @include('widgets.footer', ['widget' => $footerWidget])
    @endif

    <!-- Initialize AOS -->
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    </script>
</body>
</html>

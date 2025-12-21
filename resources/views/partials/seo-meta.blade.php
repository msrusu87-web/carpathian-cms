{{-- Performance Optimization --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="dns-prefetch" href="//cdn.jsdelivr.net">
<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
<link rel="dns-prefetch" href="//unpkg.com">

{{-- Favicon & App Icons --}}
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

{{-- Theme Color --}}
<meta name="theme-color" content="#9333ea">
<meta name="msapplication-TileColor" content="#9333ea">

{{-- Google Analytics (Optional - configure GA_MEASUREMENT_ID in .env) --}}
@if(config('services.google.analytics_id'))
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.analytics_id') }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ config('services.google.analytics_id') }}');
</script>
@endif

{{-- Google Search Console (Optional - configure in .env) --}}
@if(config('services.google.search_console_verification'))
<meta name="google-site-verification" content="{{ config('services.google.search_console_verification') }}">
@endif

{{-- Bing Webmaster Tools (Optional) --}}
@if(config('services.bing.verification'))
<meta name="msvalidate.01" content="{{ config('services.bing.verification') }}">
@endif

{{-- Additional Performance Hints --}}
<link rel="preload" as="style" href="https://cdn.tailwindcss.com">
<meta http-equiv="x-dns-prefetch-control" content="on">

{{-- Security Headers --}}
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="referrer" content="origin-when-cross-origin">

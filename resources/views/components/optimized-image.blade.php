<!-- Optimized Image Component with WebP support and lazy loading -->
@props([
    'src',
    'alt' => '',
    'width' => null,
    'height' => null,
    'class' => '',
    'priority' => false,
    'sizes' => '100vw'
])

@php
    $extension = pathinfo($src, PATHINFO_EXTENSION);
    $webpSrc = str_replace('.' . $extension, '.webp', $src);
    $loading = $priority ? 'eager' : 'lazy';
    $fetchpriority = $priority ? 'high' : 'auto';
@endphp

<picture class="{{ $class }}">
    @if(file_exists(public_path($webpSrc)))
    <source type="image/webp" srcset="{{ asset($webpSrc) }}" sizes="{{ $sizes }}">
    @endif
    <img 
        src="{{ asset($src) }}" 
        alt="{{ $alt }}"
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
        loading="{{ $loading }}"
        decoding="async"
        fetchpriority="{{ $fetchpriority }}"
        class="optimized-image"
        onload="this.classList.add('loaded')"
    >
</picture>

@once
@push('styles')
<style>
    .optimized-image {
        transition: opacity 0.3s ease-in-out;
        opacity: 0;
    }
    .optimized-image.loaded {
        opacity: 1;
    }
</style>
@endpush
@endonce

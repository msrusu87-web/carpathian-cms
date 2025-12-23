<!-- Lazy Loading Images Component -->
<!-- Usage: <x-lazy-image src="/images/photo.jpg" alt="Description" class="w-full" /> -->

@props([
    'src',
    'alt' => '',
    'width' => null,
    'height' => null,
    'class' => '',
    'placeholder' => null,
    'eager' => false,
    'aspectRatio' => null
])

@php
    // Generate blur placeholder if not provided
    $placeholderSrc = $placeholder ?? 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1 1"%3E%3C/svg%3E';
    
    // Determine if this should be eager loaded (above fold)
    $loading = $eager ? 'eager' : 'lazy';
    
    // Calculate aspect ratio padding if provided
    $paddingBottom = null;
    if ($aspectRatio) {
        $parts = explode(':', $aspectRatio);
        if (count($parts) === 2) {
            $paddingBottom = ($parts[1] / $parts[0] * 100) . '%';
        }
    }
@endphp

<div class="relative {{ $class }}" @if($paddingBottom) style="padding-bottom: {{ $paddingBottom }};" @endif>
    <img
        src="{{ $placeholderSrc }}"
        data-src="{{ $src }}"
        alt="{{ $alt }}"
        loading="{{ $loading }}"
        decoding="async"
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
        class="@if($paddingBottom) absolute inset-0 @endif w-full h-full object-cover lazy-image"
        onload="this.classList.add('loaded')"
    >
</div>

@once
@push('scripts')
<script>
    // Intersection Observer for lazy loading
    document.addEventListener('DOMContentLoaded', function() {
        const lazyImages = document.querySelectorAll('img.lazy-image[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });
            
            lazyImages.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for older browsers
            lazyImages.forEach(img => {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            });
        }
    });
</script>

<style>
    .lazy-image {
        transition: opacity 0.3s ease-in-out;
        opacity: 0;
    }
    
    .lazy-image.loaded {
        opacity: 1;
    }
</style>
@endpush
@endonce

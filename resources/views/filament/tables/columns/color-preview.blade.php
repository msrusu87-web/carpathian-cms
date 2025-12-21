<div class="flex gap-1 items-center">
    @if(isset($colors['primary']))
        <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $colors['primary'] }}" title="Primary: {{ $colors['primary'] }}"></div>
    @endif
    @if(isset($colors['secondary']))
        <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $colors['secondary'] }}" title="Secondary: {{ $colors['secondary'] }}"></div>
    @endif
    @if(isset($colors['accent']))
        <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $colors['accent'] }}" title="Accent: {{ $colors['accent'] }}"></div>
    @endif
</div>

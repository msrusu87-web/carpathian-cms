@if($items->count() > 0)
<div class="menu menu-{{ $location }}">
    <ul class="flex {{ in_array($location, ['top', 'top_left', 'top_right']) ? 'space-x-6' : 'flex-col space-y-2' }}">
        @foreach($items as $item)
        <li class="{{ $item->css_class }} {{ $item->children->count() > 0 ? 'relative group' : '' }}">
            @if($item->type === 'page' && $item->page)
                <a href="/pages/{{ $item->page->slug }}" 
                   target="{{ $item->target }}"
                   class="hover:text-blue-600 transition-colors">
                    @if($item->icon)
                        <i class="{{ $item->icon }}"></i>
                    @endif
                    {{ $item->title }}
                </a>
            @elseif($item->url)
                <a href="{{ $item->url }}" 
                   target="{{ $item->target }}"
                   class="hover:text-blue-600 transition-colors">
                    @if($item->icon)
                        <i class="{{ $item->icon }}"></i>
                    @endif
                    {{ $item->title }}
                </a>
            @endif
            
            @if($item->children->count() > 0)
                <ul class="hidden group-hover:block absolute left-0 top-full bg-white shadow-lg rounded-lg py-2 min-w-[200px] z-50">
                    @foreach($item->children as $child)
                    <li class="px-4 py-2 hover:bg-gray-50">
                        @if($child->type === 'page' && $child->page)
                            <a href="/pages/{{ $child->page->slug }}" 
                               target="{{ $child->target }}"
                               class="text-gray-700 hover:text-blue-600">
                                {{ $child->title }}
                            </a>
                        @elseif($child->url)
                            <a href="{{ $child->url }}" 
                               target="{{ $child->target }}"
                               class="text-gray-700 hover:text-blue-600">
                                {{ $child->title }}
                            </a>
                        @endif
                    </li>
                    @endforeach
                </ul>
            @endif
        </li>
        @endforeach
    </ul>
</div>
@endif

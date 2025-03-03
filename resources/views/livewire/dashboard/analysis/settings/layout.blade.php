<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $title }}
    </h2>
</x-slot>

<x-slot name="header_tab">
    <div class="flex space-x-8  border-t border-gray-200">
{{--        <a href="{{ route('dashboard.analysis.settings.watermark.sponsored.index') }}" class="text-black font-medium border-b py-3 @if(request()->routeIs('dashboard.analysis.settings.watermark.sponsored.index')) border-pgreen-700 @endif ">Sponsored</a>--}}
        <a href="{{ route('dashboard.analysis.settings.watermark.thumbnail.index') }}" class="text-black font-medium border-b py-3 @if(request()->routeIs('dashboard.analysis.settings.watermark.thumbnail.index')) border-pgreen-700 @endif">Thumbnail</a>
        <a href="{{ route('dashboard.analysis.settings.watermark.free.index') }}" class="text-black font-medium border-b py-3 @if(request()->routeIs('dashboard.analysis.settings.watermark.free.index')) border-pgreen-700 @endif">Free</a>
    </div>
</x-slot>

<div>
    {{$slot}}
</div>




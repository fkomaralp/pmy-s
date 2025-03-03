<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $title }}
    </h2>
</x-slot>

<x-slot name="header_tab">
    <div class="flex space-x-8  border-t border-gray-200">
        <a href="{{route("dashboard.analysis.upload_manager")}}" class="text-black font-medium border-b py-3 @if(request()->routeIs('dashboard.analysis.upload_manager')) border-pgreen-700 @endif ">Upload Manager</a>
        <a href="{{route("dashboard.analysis.image_analysis")}}" class="text-black font-medium border-b py-3 @if(request()->routeIs('dashboard.analysis.image_analysis')) border-pgreen-700 @endif">Image Analysis</a>
        <a href="{{route("dashboard.analysis.manual_tagging.index")}}" class="text-black font-medium border-b py-3 @if(request()->routeIs('dashboard.analysis.manual_tagging.index') || request()->routeIs('dashboard.analysis.manual_tagging.edit')) border-pgreen-700 @endif">Manual Tagging</a>
        <a href="{{route("dashboard.analysis.manual_tagging.multiple")}}" class="text-black font-medium border-b py-3 @if(request()->routeIs('dashboard.analysis.manual_tagging.multiple')) border-pgreen-700 @endif">Multiple Tagging</a>
    </div>
</x-slot>

<div>
    {{$slot}}
</div>




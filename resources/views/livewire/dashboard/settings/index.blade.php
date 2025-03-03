<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Settings') }}
    </h2>
</x-slot>

<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        @livewire('dashboard.settings.website')

        <x-jet-section-border />

        @livewire('dashboard.settings.events')

        <x-jet-section-border />

        @livewire('dashboard.settings.upload-manager')

        <x-jet-section-border />

        @livewire('dashboard.settings.image-analysis')

        <x-jet-section-border />

    </div>
</div>

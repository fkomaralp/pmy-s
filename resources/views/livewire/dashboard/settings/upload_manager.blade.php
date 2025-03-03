<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Settings') }}
    </h2>
</x-slot>

<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <x-jet-form-section submit="updateUploadManagerSettings">
            <x-slot name="title">
                {{ __('Upload Manager Settings') }}
            </x-slot>

            <x-slot name="description">
{{--                <div class="text-gray-900 mt-5">--}}
{{--                    {{ __('General site settings.') }}--}}
{{--                </div>--}}
            </x-slot>

            <x-slot name="form">
                <div class="col-span-9 sm:col-span-4">
                    <x-jet-label for="MAX_PARALLEL_COUNT" value="{{ __('Max parallel proccess count') }}"/>
                    <x-jet-input id="MAX_PARALLEL_COUNT" type="text" class="mt-1 block w-full" wire:model.defer="state.MAX_PARALLEL_COUNT"
                                 autofocus/>
                    <x-jet-input-error for="MAX_PARALLEL_COUNT" class="mt-2"/>
                    <p class="text-red-500">High values can create insufficient ram issues</p>
                </div> 
            </x-slot>

            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-jet-button>
                    {{ __('Save') }}
                </x-jet-button>
            </x-slot>
        </x-jet-form-section>
    </div>
</div>

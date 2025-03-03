<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Settings') }}
    </h2>
</x-slot>

<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <x-jet-form-section submit="updateSettings">
            <x-slot name="title">
                {{ __('Website Settings') }}
            </x-slot>

            <x-slot name="description">
{{--                <div class="text-gray-900 mt-5">--}}
{{--                    {{ __('General site settings.') }}--}}
{{--                </div>--}}
            </x-slot>

            <x-slot name="form">
                    <div class="col-span-9 sm:col-span-4">
                        <x-jet-label for="TITLE" value="{{ __('Title') }}"/>
                        <x-jet-input id="TITLE" type="text" class="mt-1 block w-full" wire:model.defer="state.TITLE"
                                     autofocus/>
                        <x-jet-input-error for="TITLE" class="mt-2"/>
                    </div>
                    <div class="col-span-9 sm:col-span-4">
                        <x-jet-label for="EMAIL" value="{{ __('Email') }}"/>
                        <x-jet-input id="EMAIL" type="text" class="mt-1 block w-full" wire:model.defer="state.EMAIL"
                                     autofocus/>
                        <x-jet-input-error for="EMAIL" class="mt-2"/>
                    </div>
                    <div class="col-span-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="user_avatar">{{ __('Logo') }}</label>
                        <input wire:model.defer="state.LOGO"
                               class="p-2 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="user_avatar_help" id="user_avatar" type="file">
                        <x-jet-input-error for="LOGO" class="mt-2"/>
                    </div>
                    <div class="col-span-1 flex items-center justify-end">
                        <img class="" src="{{$LOGO}}" width="100">
                    </div>
                    <div class="col-span-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="user_avatar">{{ __('Favicon') }}</label>
                        <input wire:model.defer="state.FAVICON"
                               class="p-2 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="user_avatar_help" id="user_avatar" type="file">
                        <x-jet-input-error for="FAVICON" class="mt-2"/>
                    </div>
                    <div class="col-span-1 flex items-center justify-end">
                        <img class="" src="{{$FAVICON}}" width="100">
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

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <x-jet-form-section submit="updateEventSettings">
            <x-slot name="title">
                {{ __('Event Settings') }}
            </x-slot>

            <x-slot name="description">
{{--                <div class="text-gray-900 mt-5">--}}
{{--                    {{ __('General site settings.') }}--}}
{{--                </div>--}}
                You can update status of events security.
            </x-slot>

            <x-slot name="form">

{{--                <div class="col-span-6 sm:col-span-4">--}}
{{--                    <x-jet-label for="title" value="{{ __('Protect events by password') }}"/>--}}
{{--                    <div class="col-span-6 sm:col-span-4 mt-3">--}}
{{--                        <label--}}
{{--                            for="status"--}}
{{--                            class="flex items-center cursor-pointer"--}}
{{--                        >--}}
{{--                            <div class="relative">--}}
{{--                                <input id="status" type="checkbox" class="sr-only" wire:model="state.EVENTS_PASSWORD_PROTECTED"/>--}}
{{--                                <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>--}}
{{--                                <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>--}}
{{--                            </div>--}}
{{--                            <div class="ml-3 text-gray-700 font-medium">--}}
{{--                                Events are--}}
{{--                                @if($state["EVENTS_PASSWORD_PROTECTED"])--}}
{{--                                    <span class="text-green-500">Protecting</span>--}}
{{--                                @else--}}
{{--                                    <span class="text-red-500">Not Protecting</span>--}}
{{--                                @endif--}}
{{--                                by password--}}
{{--                            </div>--}}
{{--                        </label>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="col-span-6 sm:col-span-4 mt-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="user_avatar">{{ __('Default event image') }}</label>
                    <input wire:model="DEFAULT_EVENT_IMAGE"
                           class="p-2 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="user_avatar_help" id="user_avatar" type="file">
                    {{--                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="user_avatar_help">Event preview</div>--}}
                    <x-jet-input-error for="DEFAULT_EVENT_IMAGE" class="mt-2"/>


                </div>
                <div class="col-span-1 flex items-center justify-end">
                    <img class="" src="{{($DEFAULT_EVENT_IMAGE !== "") ? $DEFAULT_EVENT_IMAGE : ""}}" width="100">
                </div>
            </x-slot>

            <x-slot name="actions">
                <div class="mr-3" wire:target="DEFAULT_EVENT_IMAGE" wire:loading>
                    {{ __('Please wait for prepairing image...') }}
                </div>
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-jet-button wire:loading.attr="disabled" wire:target="DEFAULT_EVENT_IMAGE">
                    {{ __('Save') }}
                </x-jet-button>
            </x-slot>
        </x-jet-form-section>
    </div>

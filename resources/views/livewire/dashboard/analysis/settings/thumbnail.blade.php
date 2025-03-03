<x-watermark-settings>

    <x-slot name="title">
        Thumbnail
    </x-slot>

    <div class="w-3/4 mx-auto py-10 sm:px-6 lg:px-8">

        <div class="flex mb-6 flex-row space-x-8 bg-white rounded-l-full rounded-r-md">
            <div class="flex-1 inline-flex">
                <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-info-50 sm:mx-0 sm:h-10 sm:w-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-info-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="self-center pl-3">You can edit your bib number thumbnail's watermark. <b>Default position</b> center <b>Corner space</b> 10 px <b>Width</b> 100 px</p>
            </div>
        </div>

        <form wire:submit.prevent="update" >
            <div class="px-4 py-5 bg-white sm:p-6 ">
                <div class="container flex sm:justify-start">
                    <div class="flex-1 mb-5 h-48">
                        <p class="pl-2">Current watermark image is</p><br>
                        <img src="{{ (is_string($image)) ? $image : $image->temporaryUrl() }}" width="150">
                    </div>
                    <div class="flex-1 mt-3 self-center">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                               for="image">{{ __('Image') }}</label>
                        <input wire:model="image"
                               class="p-2 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                               aria-describedby="user_avatar_help" id="user_avatar" type="file">
                        <x-jet-input-error for="image" class="mt-2"/>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end px-4 py-3 bg-gray-200 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-jet-button wire:loading.attr="disabled" wire:target="image">
                    {{ __('Save') }}
                </x-jet-button>
            </div>
        </form>
    </div>

</x-watermark-settings>

<x-watermark-settings>

    <style>
        .preview {
            background-image: url('/img/horizontal_sample.jpg');
            height: 439px;
        }
        .vertical-preview {
            background-image: url('/img/vertical_sample.jpg');
            height: 1000px;
        }
    </style>

    <x-slot name="title">
        Sponsored
    </x-slot>

    <script>
        function component() {
            return {
                tab: 0,
                showTab(tab) {
                    this.tab = tab
                }

            }
        }
    </script>

    <div class="w-full mx-auto py-6 sm:px-4">
        <div class="flex flex-row space-x-8 bg-white rounded-l-full rounded-r-md">
            <div class="flex-1 inline-flex">
                <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-info-50 sm:mx-0 sm:h-10 sm:w-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-info-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="self-center pl-3">You can edit Sponsored typed image watermark.</p>
            </div>
        </div>
    </div>

    <div class="flex sm:px-4"  x-data="component()">
        <div class="flex-1 w-48">
            <form wire:submit.prevent="update">
            <div class="px-4 py-5 bg-white sm:p-6 shadow">
                <div class="col-span-6 sm:col-span-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                           for="image">{{ __('Watermark image for Vertical image') }}</label>
                    <input wire:model="vertical_image"
                           class="p-2 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                           aria-describedby="vertical_image" id="user_avatar" type="file">
                    <x-jet-input-error for="vertical_image" class="mt-2"/>
                </div>
                <div class="col-span-6 sm:col-span-4 mt-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                           for="image">{{ __('Watermark image for Horizontal image') }}</label>
                    <input wire:model="horizontal_image"
                           class="p-2 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                           aria-describedby="horizontal_image" id="user_avatar" type="file">
                    <x-jet-input-error for="horizontal_image" class="mt-2"/>
                </div>
                <div class="col-span-6 sm:col-span-4 mt-5">
                    <label class="flex items-center">
                        <x-jet-checkbox wire:model="fit_image_to_width" :value="$fit_image_to_width"/>
                        <span class="ml-2 mt-2 block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Fit watermark width to image width') }}</span>
                    </label>

                </div>
{{--                <div class="col-span-6 sm:col-span-4 mt-2">--}}
{{--                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"--}}
{{--                           for="orientation">{{ __('Orientation') }}</label>--}}
{{--                    <x-jet-input id="orientation" type="range"--}}
{{--                                 max="360"--}}
{{--                                 min="0"--}}
{{--                                 step="90" class="mt-2 block w-full" wire:model="orientation"--}}
{{--                    />--}}
{{--                    <x-jet-input-error for="orientation" class="mt-2"/>--}}
{{--                </div>--}}
{{--                <div class="col-span-6 sm:col-span-4 mt-2">--}}
{{--                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"--}}
{{--                           for="width">{{ __('Width') }}</label>--}}
{{--                    <x-jet-input id="width" type="range"--}}
{{--                                 max="1000"--}}
{{--                                 min="10"--}}
{{--                                 step="10" class="mt-2 block w-full" wire:model="width"--}}
{{--                    />--}}
{{--                    <x-jet-input-error for="width" class="mt-2"/>--}}
{{--                </div>--}}
                <div class="col-span-6 sm:col-span-4 mt-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                           for="opacity">{{ __('Opacity') }}</label>
                    <x-jet-input id="opacity" type="range"
                                 max="1"
                                 min="0.1"
                                 step="0.1" class="mt-2 block w-full" wire:model="opacity"
                    />
                    <x-jet-input-error for="opacity" class="mt-2"/>
                </div>
                <div class="col-span-6 sm:col-span-4 mt-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                           for="position">{{ __('Position') }}</label>
                    <div class="grid grid-rows-3 gap-3 h-48 bg-gray-100 w-48 mx-auto border border-gray-300 rounded overflow-hidden">
                        <div class="w-full relative flex justify-between" style="height: 3.5rem">
                            <div wire:click="updatePosition('top-0 left-0')" class="cursor-pointer hover:bg-gray-400  bg-gray-500 w-14 h-14 flex-1">
                            </div>
                            <div wire:click="updatePosition('inset-0  mx-auto')"  class="cursor-pointer hover:bg-gray-400  bg-gray-500 w-14 h-14 ml-1 mr-1 flex-1">
                            </div>
                            <div wire:click="updatePosition('top-0 right-0')"  class="cursor-pointer hover:bg-gray-400 bg-gray-500 w-14 h-14 flex-1">
                            </div>
                        </div>
                        <div class="w-full relative flex" style="height: 3.5rem">
                            <div wire:click="updatePosition('inset-y-0 left-0 my-auto')"  class="cursor-pointer hover:bg-gray-400  bg-gray-500 w-14 h-14 flex-1">
                            </div>
                            <div wire:click="updatePosition('inset-0 my-auto mx-auto')"  class="cursor-pointer hover:bg-gray-400  bg-gray-500 w-14 h-14 ml-1 mr-1 flex-1">
                            </div>
                            <div wire:click="updatePosition('inset-y-0 my-auto right-0')"  class="cursor-pointer hover:bg-gray-400 bg-gray-500 w-14 h-14 flex-1">
                            </div>
                        </div>
                        <div class="w-full relative flex" style="height: 3.5rem">
                            <div wire:click="updatePosition('bottom-0 left-0')"  class="cursor-pointer hover:bg-gray-400  bg-gray-500 w-14 h-14 flex-1">
                            </div>
                            <div wire:click="updatePosition('bottom-0 inset-x-0 mx-auto')"  class="cursor-pointer hover:bg-gray-400  bg-gray-500 w-14 h-14 ml-1 mr-1 flex-1">
                            </div>
                            <div wire:click="updatePosition('bottom-0 right-0')"  class="cursor-pointer hover:bg-gray-400 bg-gray-500 w-14 h-14 flex-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>
                <x-jet-button>
                    {{ __('Save') }}
                </x-jet-button>
            </div>
        </form>
        </div>
        <div class="flex-1 w-48 bg-white">
            <div class="flex flex-row">
                <button class="flex-1 px-3 py-3" @click="showTab(0)" :class="{'bg-pgreen-800 text-white' : tab === 0}">Horizontal</button>
                <button class="flex-1 px-3 py-3" @click="showTab(1)" :class="{'bg-pgreen-800 text-white' : tab === 1}">Vertical</button>
            </div>
            <div class="flex-1 w-full preview bg-cover w-full overflow-hidden bg-no-repeat relative" x-show="tab === 0">
                <div class="absolute w-full h-full bg-white opacity-50" wire:loading wire:target="horizontal_image"></div>
                @if ($horizontal_image)
                    <img class="absolute {{$position}}"
                         style="transform: rotate({{$orientation}}deg); width:100%;opacity:{{$opacity}}"
                         id="image" src="{{ (is_string($horizontal_image)) ? $horizontal_image : $horizontal_image->temporaryUrl() }}">

                @endif
            </div>
            <div class="flex-1 w-full vertical-preview bg-contain w-full overflow-hidden bg-no-repeat relative" x-show="tab === 1">
                <div class="absolute w-full h-full bg-white opacity-50" wire:loading wire:target="vertical_image"></div>
                @if ($vertical_image)
                    <img class="absolute {{$position}}"
                         style="transform: rotate({{$orientation}}deg); width:100%;opacity:{{$opacity}}"
                         id="image" src="{{ (is_string($vertical_image)) ? $vertical_image : $vertical_image->temporaryUrl() }}">

                @endif
            </div>
        </div>
    </div>


</x-watermark-settings>

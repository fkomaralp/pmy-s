<div>
<script>
    function component() {
        return {
            sponsored: @entangle('sponsored'),
            tab: 0,
            filters: @entangle('filters'),
            init(){
                this.filters.push({"from" : 0, "to": 0})
            },
            showTab(tab) {
                this.tab = tab
            },
            addFilter() {
                this.filters.push({"from" : 0, "to": 0})
            },
            removeFilter(index) {
                this.filters = this.filters.filter(function(item, key) {
                    return key !== index
                })
            }
        }
    }
</script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Event') }}
        </h2>
    </x-slot>

        <style>
            .preview {
                background-image: url('/img/horizontal_sample.jpg');
                height: 308px;
            }
            .vertical-preview {
                background-image: url('/img/vertical_sample.jpg');
                height: 673px;
            }
        </style>

        <div class="w-3/4 mx-auto py-10 sm:px-6 lg:px-8" x-data="component()">
                <div class="px-4 py-5 bg-white sm:p-6 ">

                        <div class="col-span-6 sm:col-span-4">
                            <x-jet-label for="title" value="{{ __('Status') }}"/>
                            <div class="col-span-6 sm:col-span-4 mt-3">
                                <label
                                    for="status"
                                    class="flex items-center cursor-pointer"
                                >
                                    <div class="relative">
                                        <input id="status" type="checkbox" class="sr-only" wire:model.defer="status"/>
                                        <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                        <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
                                    </div>
                                    <div class="ml-3 text-gray-700 font-medium">
                                        Event is
                                        @if($status)
                                            <span class="text-green-500">Online</span>
                                        @else
                                            <span class="text-red-500">Offline</span>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-4 mt-5">
                            <x-jet-label for="title" value="{{ __('Password Protected') }}"/>
                            <div class="col-span-6 sm:col-span-4 mt-3">
                                <label
                                    for="protected"
                                    class="flex items-center cursor-pointer"
                                >
                                    <div class="relative">
                                        <input id="protected" type="checkbox" class="sr-only" wire:model.defer="protected"/>
                                        <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                        <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
                                    </div>
                                    <div class="ml-3 text-gray-700 font-medium">
                                        Event is
                                        @if($protected)
                                            <span class="text-green-500">Protecting</span>
                                        @else
                                            <span class="text-red-500">Not Protecting</span>
                                        @endif
                                        by password.
                                    </div>
                                </label>
                            </div>
                            <div class="flex mb-6 mt-6 flex-row space-x-8 bg-white rounded-l-full rounded-r-md">
                                <div class="flex-1 inline-flex">
                                    <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-info-50 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-info-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="self-center pl-3">Event passwords will show in the event list table after creating event</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-4  mt-5">
                            <x-jet-label for="title" value="{{ __('Is Sponsored') }}"/>
                            <div class="col-span-6 sm:col-span-4 mt-3">
                                <label
                                    for="sponsored"
                                    class="flex items-center cursor-pointer"
                                >
                                    <div class="relative">
                                        <input id="sponsored" type="checkbox" class="sr-only" wire:model="sponsored"/>
                                        <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                        <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-4  mt-5 min-h-fit" x-show="sponsored">
                            <div class="flex flex-row">
                                <div class="flex-1 w-80">
                                    <div class="px-4 py-5 bg-white sm:p-6">
                                        <div class="col-span-6 sm:col-span-4 mt-5">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                   for="horizontal_image">{{ __('Sponsors frame for Horizontal image') }}</label>
{{--                                            <input wire:model.defer="horizontal_image"--}}
{{--                                                   class="p-2 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"--}}
{{--                                                   type="file" id="horizontal_image">--}}
                                            <x-jet-input id="horizontal_image" type="file" class="p-2 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                         wire:model.defer="horizontal_image"/>
                                            <x-jet-input-error for="horizontal_image" class="mt-2"/>
                                        </div>
                                        <div class="col-span-6 sm:col-span-4 mt-5">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                   for="image">{{ __('Sponsors frame for Vertical image') }}</label>
                                            <input wire:model.defer="vertical_image"
                                                   class="p-2 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                   id="vertical_image" type="file">
                                            <x-jet-input-error for="vertical_image" class="mt-2"/>
                                        </div>

                                        <div class="flex mb-6 mt-6 flex-row space-x-8 bg-white rounded-l-full rounded-r-md">
                                            <div class="flex-1 inline-flex">
                                                <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-info-50 sm:mx-0 sm:h-10 sm:w-10">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-info-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <p class="self-center pl-3">For best result upload 1080p wide png images</p>
                                            </div>
                                        </div>

                                        <div class="col-span-6 sm:col-span-4 mt-5">
                                            <label class="flex items-center">
                                                <x-jet-checkbox wire:model="fit_image_to_width" :value="$fit_image_to_width"/>
                                                <span class="ml-2 mt-2 block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Fit Sponsors frame width to image width') }}</span>
                                            </label>

                                        </div>
                                        <div class="col-span-6 sm:col-span-4 mt-5">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                   for="opacity">{{ __('Opacity') }}</label>
                                            <x-jet-input id="opacity" type="range"
                                                         max="1"
                                                         min="0.1"
                                                         step="0.1" class="mt-2 block w-full" wire:model.defer="opacity"
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
                                </div>

                                <div class="flex-1 w-80">
                                    <div class="flex-1 w-full bg-white">
                                        <div class="flex flex-row">
                                            <button type="button" class="flex-1 px-3 py-3" @click="showTab(0)" :class="{'bg-pgreen-800 text-white' : tab === 0}">Horizontal</button>
                                            <button type="button" class="flex-1 px-3 py-3" @click="showTab(1)" :class="{'bg-pgreen-800 text-white' : tab === 1}">Vertical</button>
                                        </div>
                                        <div class="flex-1 w-full p-5" x-show="tab === 0">
                                            <div class="text-red-500 w-full text-center text-sm mb-3">
                                                You can download your sample after create an event!
                                            </div>
                                        </div>
                                        <div class="flex-1 w-full p-5" x-show="tab === 1">
                                            <div class="text-red-500 w-full text-center text-sm mb-3">
                                                You can download your sample after create an event!
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-4 mt-5">
                            <x-jet-label for="title" value="{{ __('Title') }}"/>
                            <x-jet-input id="title" type="text" class="mt-1 block w-full" wire:model.defer="title"
                                     autofocus/>
                            <x-jet-input-error for="title" class="mt-2"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mt-5">
                            <x-jet-label for="note" value="{{ __('Note') }}"/>
                            <x-jet-input id="note" type="text" class="mt-1 block w-full" wire:model.defer="note"
                                         autofocus/>
                            <x-jet-input-error for="note" class="mt-2"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mt-5">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="user_avatar">{{ __('Image') }}</label>
                            <input wire:model="image"
                                   class="p-2 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="user_avatar_help" id="user_avatar" type="file">
                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="user_avatar_help">Event preview</div>
                            <x-jet-input-error for="image" class="mt-2"/>
                        </div>
{{--                        <div class="col-span-6 sm:col-span-4 mt-5">--}}
{{--                            <x-jet-label for="country" value="{{ __('Country') }}"/>--}}
{{--                            <x-jet-input id="country" type="text" class="mt-1 block w-full" wire:model.defer="country"--}}
{{--                                         autofocus/>--}}
{{--                            <div class="flex-initial mt-2">--}}
{{--                                <div class="w-full relative inline-block text-left" @click.away="showDropdown=false">--}}
{{--                                    <div>--}}
{{--                                        <button type="button" @click="showDropdown=!showDropdown;$nextTick(() => $refs.filterCountryByName.focus());"--}}
{{--                                                class="flex justify-between relative w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"--}}
{{--                                                id="menu-button" aria-expanded="true" aria-haspopup="true">--}}
{{--                                            <div>{{$selected_country->name}}</div>--}}
{{--                                            <div>--}}
{{--                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"--}}
{{--                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                                          d="M19 9l-7 7-7-7"/>--}}
{{--                                                </svg>--}}
{{--                                            </div>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                    <div x-cloak x-show="showDropdown"--}}
{{--                                         class="z-50 border border-indigo-400 origin-top-left absolute left-0 mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"--}}
{{--                                         role="menu" aria-orientation="vertical" aria-labelledby="menu-button"--}}
{{--                                         tabindex="-1">--}}
{{--                                        <div class="">--}}
{{--                                            <input--}}
{{--                                                wire:model="filterCountryByName"--}}
{{--                                                x-ref="filterCountryByName"--}}
{{--                                                class="w-full outline-none border-b border-gray-400 p-3 rounded rounded-b-none"--}}
{{--                                                placeholder="Search..."/>--}}
{{--                                        </div>--}}
{{--                                        <ul class="py-1 max-h-52 w-full overflow-auto" role="none">--}}
{{--                                            @foreach($countries as $country)--}}
{{--                                                <li class="border-b last:border-0">--}}
{{--                                                    <button wire:click="selectCountry({{$country->id}})" type="button" class="text-gray-700 w-full px-4 py-2 text-left">{{$country->name}}</button>--}}
{{--                                                </li>--}}
{{--                                            @endforeach--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <x-jet-input-error for="country" class="mt-2"/>--}}
{{--                        </div>--}}
{{--                        <div class="col-span-6 sm:col-span-4 mt-5">--}}
{{--                            <x-jet-label for="city" value="{{ __('City/Town') }}"/>--}}
{{--                            <x-jet-input id="city" type="text" class="mt-1 block w-full" wire:model.defer="city"--}}
{{--                                         autofocus/>--}}
{{--                            <div class="flex-initial mt-2">--}}
{{--                                <div class="w-full relative inline-block text-left" @click.away="showCityDropdown=false">--}}
{{--                                    <div>--}}
{{--                                        <button type="button" @click="showCityDropdown=!showCityDropdown;$nextTick(() => $refs.filterCityByName.focus());"--}}
{{--                                                class="flex justify-between relative w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"--}}
{{--                                                id="menu-button" aria-expanded="true" aria-haspopup="true">--}}
{{--                                            <div>{{($selected_city != null) ? $selected_city->name : "No results found"}}</div>--}}
{{--                                            <div>--}}
{{--                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"--}}
{{--                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                                          d="M19 9l-7 7-7-7"/>--}}
{{--                                                </svg>--}}
{{--                                            </div>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                    <div x-cloak x-show="showCityDropdown"--}}
{{--                                         class="z-50 border border-indigo-400 origin-top-left absolute left-0 mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"--}}
{{--                                         role="menu" aria-orientation="vertical" aria-labelledby="menu-button"--}}
{{--                                         tabindex="-1">--}}
{{--                                        <div class="">--}}
{{--                                            <input--}}
{{--                                                wire:model="filterCityByName"--}}
{{--                                                x-ref="filterCityByName"--}}
{{--                                                class="w-full outline-none border-b border-gray-400 p-3 rounded rounded-b-none"--}}
{{--                                                placeholder="Search..."/>--}}
{{--                                        </div>--}}
{{--                                        <ul class="py-1 max-h-52 w-full overflow-auto" role="none">--}}
{{--                                            @if($cities->count() < 1)--}}
{{--                                                <li>--}}
{{--                                                    <span class="text-gray-700 w-full px-4 py-2 text-left">No result found</span>--}}
{{--                                                </li>--}}
{{--                                            @endif--}}
{{--                                            @foreach($cities as $city)--}}
{{--                                                <li class="border-b last:border-0">--}}
{{--                                                    <button wire:click="selectCity({{$city->id}})" type="button" class="text-gray-700 w-full px-4 py-2 text-left">{{$city->name}}</button>--}}
{{--                                                </li>--}}
{{--                                            @endforeach--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <x-jet-input-error for="city" class="mt-2"/>--}}
{{--                        </div>--}}
                        <div class="col-span-6 sm:col-span-4 mt-5">
                            <x-select
                                label="{{ __('Price List') }}"
                                placeholder="{{ __('Select') }}"
                                multiselect
                                :options="$price_list"
                                option-label="title"
                                option-value="id"
                                wire:model.defer="selected_prices"
                            />
                        </div>
                        <div class="col-span-6 sm:col-span-4 mt-5">
                            <x-datetime-picker
                                label="{{ __('Event Date') }}"
                                placeholder="{{ __('Event Date') }}"
                                without-time
                                display-format="MMM D, YYYY"
                                wire:model.defer="event_date"
                            />
                        </div>

                        @include('components.event-filter-component')
                </div>
                <div class="flex items-center justify-end px-4 py-3 bg-gray-200 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                    <div class="mr-3" wire:target="image" wire:loading>
                        {{ __('Please wait for prepairing image...') }}
                    </div>
                    <x-jet-secondary-button class="mr-3" wire:click="back()" >
                        {{ __('Cancel') }}
                    </x-jet-secondary-button>
                    <x-jet-button type="button" wire:click="createEvent" >
                        {{ __('Create') }}
                    </x-jet-button>
                </div>
        </div>
</div>

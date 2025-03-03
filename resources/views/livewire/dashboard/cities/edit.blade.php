<div x-data="{ showDropdown: @entangle('showDropdown') }">
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="left-0 font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit City') }}
            </h2>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <x-jet-form-section submit="editCity">
                <x-slot name="title">
                    {{--        {{ __('Event Details') }}--}}
                </x-slot>

                <x-slot name="description">
                    {{--        {{ __('Create a new team to collaborate with others on projects.') }}--}}
                </x-slot>

                <x-slot name="form">
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="name" value="{{ __('Name') }}"/>
                        <x-jet-input wire:model="name" id="name" type="text" class="mt-1 block w-full"
                                     wire:model.defer="state.name" autofocus/>
                        <x-jet-input-error for="name" class="mt-2"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="country" value="{{ __('Country') }}"/>
                        <div class="flex-initial w-60 mt-2">
                            <div class="w-full relative inline-block text-left" @click.away="showDropdown=false">
                                <div>
                                    <button type="button" @click="showDropdown=!showDropdown;$nextTick(() => $refs.filterCountryByName.focus());"
                                            class="flex justify-between relative w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                                            id="menu-button" aria-expanded="true" aria-haspopup="true">
                                        <div>{{$selected_county->name}}</div>
                                        <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 9l-7 7-7-7"/>
                                        </svg>
                                        </div>
                                    </button>
                                </div>
                                <div x-cloak x-show="showDropdown"
                                     class="border border-indigo-400 origin-top-left absolute left-0 mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                     role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
                                     tabindex="-1">
                                    <div class="">
                                        <input
                                            wire:model="filterCountryByName"
                                            x-ref="filterCountryByName"
                                            class="w-full outline-none border-b border-gray-400 p-3 rounded rounded-b-none"
                                            placeholder="Search..."/>
                                    </div>
                                    <ul class="py-1 max-h-52 w-full overflow-auto" role="none">
                                        @foreach($countries as $country)
                                            <li>
                                                <button wire:click="selectCountry({{$country->id}})" type="button" class="text-gray-700 w-full px-4 py-2 text-left border-b">{{$country->name}}</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <x-jet-input-error for="country" class="mt-2"/>
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <x-jet-button>
                        {{ __('Update') }}
                    </x-jet-button>
                </x-slot>
            </x-jet-form-section>
        </div>
    </div>
</div>

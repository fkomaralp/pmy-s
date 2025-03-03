

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Create Price') }}
    </h2>
</x-slot>

<div class="w-3/4 mx-auto py-10 sm:px-6 lg:px-8">
    <form wire:submit.prevent="createPrice" class="w-full">
        <div class="px-4 py-5 bg-white sm:p-6 ">
                <div class="col-span-6 sm:col-span-4 mt-3">
                    <x-jet-label for="title" value="{{ __('Type') }}"/>
                    <div class="col-span-6 sm:col-span-4 mt-3">
                        <div class="form-check form-check-inline">
                            <input wire:model="state.type" class="form-check-input form-check-input appearance-none
                            rounded-full h-4 w-4 border border-gray-300 bg-white checked:bg-blue-600
                            checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top
                            bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="radio"
                                   name="type" id="hd" value="0">
                            <label class="form-check-label inline-block text-gray-800" for="hd">HD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input wire:model="state.type" class="form-check-input form-check-input appearance-none
                            rounded-full h-4 w-4 border border-gray-300 bg-white checked:bg-blue-600
                            checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top
                            bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="radio"
                                   name="type" id="4k" value="1">
                            <label class="form-check-label inline-block text-gray-800" for="4k">4K</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input wire:model="state.type" class="form-check-input form-check-input appearance-none
                            rounded-full h-4 w-4 border border-gray-300 bg-white checked:bg-blue-600
                            checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top
                            bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="radio"
                                   name="type" id="sponsored" value="2">
                            <label class="form-check-label inline-block text-gray-800" for="sponsored">Sponsored</label>
                        </div>
                        </div>
                </div>
                <div class="col-span-6 sm:col-span-4 mt-3">
                    <x-jet-label for="title" value="{{ __('Status') }}"/>
                    <div class="col-span-6 sm:col-span-4 mt-3">
                        <label
                            for="status"
                            class="flex items-center cursor-pointer"
                        >
                            <div class="relative">
                                <input id="status" type="checkbox" class="sr-only" wire:model="state.status"/>
                                <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
                            </div>
                            <div class="ml-3 text-gray-700 font-medium">
                                Price is
                                @if($state["status"])
                                    <span class="text-green-500">Online</span>
                                @else
                                    <span class="text-red-500">Offline</span>
                                @endif
                            </div>
                        </label>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-4 mt-3">
                    <x-jet-label for="title" value="{{ __('Title') }}"/>
                    <x-jet-input id="title" type="text" class="mt-1 block w-full" wire:model.defer="state.title"
                                 autofocus/>
                    <x-jet-input-error for="title" class="mt-2"/>
                </div>
                <div class="col-span-6 sm:col-span-4 mt-3">
                    <x-jet-label for="price" value="{{ __('Price') }}"/>
                    <x-jet-input id="price" type="text" class="mt-1 block w-full" wire:model.defer="state.price"
                                 autofocus/>
                    <x-jet-input-error for="price" class="mt-2"/>
                </div>
            </div>

        <div class="flex items-center justify-end px-4 py-3 bg-gray-200 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <x-jet-secondary-button class="mr-3" wire:click="back()">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>
                <x-jet-button>
                    {{ __('Create') }}
                </x-jet-button>
        </div>
    </form>
</div>

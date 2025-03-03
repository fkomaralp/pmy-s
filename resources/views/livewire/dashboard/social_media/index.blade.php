<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Social Media') }}
    </h2>
</x-slot>

<div class="w-3/4 mx-auto py-10 sm:px-6 lg:px-8">
    <form wire:submit.prevent="updateConfiguration" >
        <div class="px-4 py-5 bg-white sm:p-6 ">
                    <div class="col-span-9 sm:col-span-4">
                        <x-jet-label for="LINKEDIN" value="{{ __('Linkedin') }}"/>
                        <x-jet-input id="LINKEDIN" type="text" class="mt-1 block w-full" wire:model.defer="state.LINKEDIN"
                                     autofocus/>
                        <x-jet-input-error for="LINKEDIN" class="mt-2"/>
                    </div>
                    <div class="col-span-9 sm:col-span-4 mt-3">
                        <x-jet-label for="TWITTER" value="{{ __('Twitter') }}"/>
                        <x-jet-input id="TWITTER" type="text" class="mt-1 block w-full" wire:model.defer="state.TWITTER"
                                     autofocus/>
                        <x-jet-input-error for="TWITTER" class="mt-2"/>
                    </div>
                    <div class="col-span-9 sm:col-span-4 mt-3">
                        <x-jet-label for="FACEBOOK" value="{{ __('Facebook') }}"/>
                        <x-jet-input id="FACEBOOK" type="text" class="mt-1 block w-full" wire:model.defer="state.FACEBOOK"
                                     autofocus/>
                        <x-jet-input-error for="FACEBOOK" class="mt-2"/>
                    </div>
                    <div class="col-span-9 sm:col-span-4 mt-3">
                        <x-jet-label for="INSTAGRAM" value="{{ __('Instagram') }}"/>
                        <x-jet-input id="INSTAGRAM" type="text" class="mt-1 block w-full" wire:model.defer="state.INSTAGRAM"
                                     autofocus/>
                        <x-jet-input-error for="INSTAGRAM" class="mt-2"/>
                    </div>
        </div>

        <div class="flex items-center justify-end px-4 py-3 bg-gray-200 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <x-jet-button>
                    {{ __('Update') }}
                </x-jet-button>
        </div>
    </form>
</div>

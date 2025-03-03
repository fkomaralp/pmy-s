<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Update Question') }}
    </h2>
</x-slot>


<div class="w-3/4 mx-auto py-10 sm:px-6 lg:px-8">
    <form wire:submit.prevent="updateQuestion" >
        <div class="px-4 py-5 bg-white sm:p-6 shadow">
            <div class="col-span-6 sm:col-span-4 mt-3">
                <x-jet-label for="question" value="{{ __('Question') }}"/>
                <x-jet-input id="question" type="text" class="mt-1 block w-full" wire:model.defer="state.question"
                             autofocus/>
                <x-jet-input-error for="question" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4 mt-3">
                <x-jet-label for="answer" value="{{ __('Answer') }}"/>
                <x-jet-input id="answer" type="text" class="mt-1 block w-full" wire:model.defer="state.answer"
                             autofocus/>
                <x-jet-input-error for="answer" class="mt-2"/>
            </div>
        </div>

        <div
            class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
            <x-jet-secondary-button class="mr-3" wire:click="back()">
                {{ __('Cancel') }}
            </x-jet-secondary-button>
            <x-jet-button>
                {{ __('Update') }}
            </x-jet-button>
        </div>
    </form>
</div>


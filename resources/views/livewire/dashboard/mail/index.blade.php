<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mail Settings') }}
        </h2>
</x-slot>

<div class="w-3/4 mx-auto py-10 sm:px-6 lg:px-8" >

    <x-notifications />

    <script>
        function component(){
            return {
                copyShortcut($event) {
                    let copyTextarea = $event.target;

                    document.execCommand("copy");
                    var range = document.createRange();
                    range.selectNode(copyTextarea);
                    window.getSelection().removeAllRanges();
                    window.getSelection().addRange(range);
                    document.execCommand("copy");
                    window.getSelection().removeAllRanges();

                    window.$wireui.notify({
                        title: 'Copied',
                        description: copyTextarea.innerText,
                        icon: 'success'
                    })

                }
            }
        }
    </script>
    <form wire:submit.prevent="updateMailSettings" x-data="component()">
            <div class="px-4 py-5 bg-white sm:p-6 ">

                <div class="w-full md:w-1/2 px-3 mb-6 mt-3">
                    <x-jet-label for="host" value="{{ __('Host') }}"/>
                    <x-jet-input id="host" type="text" class="mt-1 block w-full" wire:model.defer="state.host"
                                 autofocus/>
                    <x-jet-input-error for="host" class="mt-2"/>
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 mt-3">
                    <x-jet-label for="username" value="{{ __('Username') }}"/>
                    <x-jet-input id="username" type="text" class="mt-1 block w-full" wire:model.defer="state.username"
                                 autofocus/>
                    <x-jet-input-error for="username" class="mt-2"/>
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 mt-3">
                    <x-jet-label for="password" value="{{ __('Password') }}"/>
                    <x-jet-input id="password" type="text" class="mt-1 block w-full" wire:model.defer="state.password"
                                 autofocus/>
                    <x-jet-input-error for="password" class="mt-2"/>
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 mt-3">
                    <x-jet-label for="port" value="{{ __('Port') }}"/>
                    <x-jet-input id="port" type="text" class="mt-1 block w-full" wire:model.defer="state.port"
                                 autofocus/>
                    <x-jet-input-error for="port" class="mt-2"/>
                </div>

{{--                <div class="w-full md:w-2/2 px-3 mb-6 mt-3">--}}
{{--                    <x-jet-label for="template" value="{{ __('Mail Template') }}"/>--}}
{{--                    <textarea id="template" class="mt-2 w-full sm:h-48 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" wire:model.defer="state.template">--}}
{{--                    </textarea>--}}
{{--                    <x-jet-input-error for="template" class="mt-2"/>--}}
{{--                </div>--}}
{{--                <x-card title="Mail template shortcuts" class="text-sm">--}}
{{--                    <code class="cursor-pointer" x-ref="shortcut" @click="copyShortcut($event)">{username}</code> Customer user name and lastname<br>--}}
{{--                    <code class="cursor-pointer" x-ref="shortcut" @click="copyShortcut($event)">{order_number}</code> Order number<br>--}}
{{--                    <code class="cursor-pointer" x-ref="shortcut" @click="copyShortcut($event)">{event_title}</code> Event title<br>--}}
{{--                    <code class="cursor-pointer" x-ref="shortcut" @click="copyShortcut($event)">{url}</code> Download url of zip file<br>--}}
{{--                </x-card>--}}

            </div>

        <div class="flex items-center justify-end px-4 py-3 bg-gray-200 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-jet-button>
                    {{ __('Save') }}
                </x-jet-button>
            </div>
    </form>
</div>

<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders') }}
        </h2>
</x-slot>
    <div>
        <script src="https://unpkg.com/underscore@1.13.3/underscore-min.js"></script>
    <script>
        function component() {
            return {
                error : "",
                confirmModal: false,
                deleting: false,
                showModalWithMessage(message) {
                    this.error = message
                    $openModal('showingErrors')
                },
                copyDownloadUrl(url){

                    navigator.clipboard.writeText(location.hostname + "/user_images/" + url);

                    window.$wireui.notify({
                        title: 'Copied',
                        description: 'Download url copied to your clipboard.',
                        icon: 'info'
                    })

                }
            }
        }
    </script>
<div x-data="component()">

{{--    @if($orders->count() > 0)--}}
            <div class="w-full mx-auto py-10 sm:px-6 lg:px-8">
                <div class="bg-gray-200 w-full p-3 rounded rounded-b-none">
                    <div class="grid grid-cols-3 gap-1">

                        <div class="">
                            <x-jet-input id="searchby_ordernumber" type="text" class="w-full" wire:model="searchby_ordernumber"
                                         placeholder="Order Number"
                                         autofocus/>
                        </div>

                        <div class="">
                            <x-jet-input id="searchby_email" type="text" class="w-full" wire:model="searchby_email"
                                         placeholder="E-mail"
                                         autofocus/>
                        </div>

                        <div class="">
                            <select wire:model="searchby_event" class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option value="0">Select an Event</option>
                                @foreach($events as $event)
                                    <option value="{{$event->id}}">{{$event->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                @if($orders->count() > 0)
                    @if($orders->hasPages())
                        <div class="bg-gray-50 w-full p-3 rounded rounded-t-none">
                            {{ $orders->links() }}
                        </div>
                    @endif
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Date
                            </th>
                            <th scope="col"
                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Order Number
                            </th>
                            <th scope="col"
                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Event
                            </th>
                            <th scope="col"
                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                E-Mail
                            </th>
                            <th scope="col"
                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Status
                            </th>
    {{--                        <th scope="col"--}}
    {{--                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
    {{--                            Event Title--}}
    {{--                        </th>--}}
                            <th scope="col"
                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Price
                            </th>
                            <th scope="col"
                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Price Title
                            </th>
                            <th scope="col"
                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Bib Number
                            </th>
                            <th scope="col"
                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Download Url
                            </th>
                            <th scope="col"
                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$order->created_at->format("Y/m/d")}}
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex h-10">
                                        @if($order->job_status === 1)
                                            <button @click="showModalWithMessage('{{str_replace('\'', '\\\'', $order->job_exception)}}')" type="button" class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </button>
                                        @endif
                                        <div class="self-center pl-3">{{$order->order_number}}</div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$order->event->title}}
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$order->user->email}}
                                </td>
                                @if($order->status)
                                    <td class="py-4 px-6 text-sm font-medium text-green-500 whitespace-nowrap dark:text-white">
                                        Processed
                                    </td>
                                @else

                                    @if($order->job_status === 1)
                                        <td class="py-4 px-6 text-sm font-medium text-red-500 whitespace-nowrap dark:text-white">
                                            Failed
                                        </td>
                                    @else
                                        <td class="py-4 px-6 text-sm font-medium text-info-800 whitespace-nowrap dark:text-white">
                                            Processing...
                                        </td>
                                    @endif
                                @endif
    {{--                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">--}}
    {{--                                {{$order->event_title}}--}}
    {{--                            </td>--}}
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                    {{(isset($order->orderPrice)) ? $order->orderPrice->price : "Error"}}
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{(isset($order->orderPrice)) ? $order->orderPrice->title : "Error"}}
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$order->bib_number}}
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <button @click="copyDownloadUrl('{{$order->generateDownloadUrl()}}')" type="button" class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600"  fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
                                        </svg>

                                    </button>
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($order->job_status === 1)
                                        <button class="py-2 px-4 text-sm font-medium text-positive-600 rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-positive-600 hover:bg-gray-100 focus:ring-4 focus:ring-gray-300" wire:click="retryJob('{{$order->job_uuid}}')">Retry</button>
                                    @endif
                                </td>
                                {{--                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">--}}
                                {{--                    <div class="inline-flex rounded-md shadow-sm" role="group">--}}
                                {{--                        <a href="{{route("dashboard.events.edit", ["event_id" => $event->id])}}"--}}
                                {{--                           class="py-2 px-4 text-sm font-medium text-positive-600 rounded-l-lg border border-gray-200 hover:bg-gray-100 hover:text-positive-600 hover:bg-gray-100 focus:ring-4 focus:ring-gray-300">--}}
                                {{--                            {{__("Edit")}}--}}
                                {{--                        </a>--}}
                                {{--                        <button type="button" wire:click="deleteId({{$event->id}})" @click="confirmModal=true"--}}
                                {{--                                class="py-2 px-4 text-sm font-medium text-red-700 rounded-r-lg border-b border-r border-t border-gray-200 hover:bg-gray-100 hover:text-red-700 hover:bg-gray-100 focus:ring-4 focus:ring-gray-300">--}}
                                {{--                            {{__("Delete")}}--}}
                                {{--                        </button>--}}
                                {{--                    </div>--}}
                                {{--                </td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if($orders->hasPages())
                        <div class="bg-gray-50 w-full p-3 rounded rounded-t-none">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @else
                    <div class="w-full mx-auto py-10 sm:px-6 lg:px-8 text-center">
                        <img src="/img/no_records.png" width="200" class="mx-auto">
                        <div class="text-xl p-5 pt-8 font-bold text-gray-700">
                            No results found
                        </div>
                    </div>
                @endif
            </div>
            <x-modal blur wire:model.defer="showingErrors">
                <x-card padding="" >
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>

                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg">
                                    Order Error
                                </h3>

                                <div class="mt-2">
                                    <span x-text="error"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right rounded-md rounded-t-none">
                        <x-button rose label="OK" @click="close" />
                    </div>
                </x-card>
            </x-modal>
{{--        @else--}}
{{--            <div class="w-full mx-auto py-10 sm:px-6 lg:px-8 text-center">--}}
{{--                <img src="/img/no_records.png" width="200" class="mx-auto">--}}
{{--                <div class="text-xl p-5 pt-8 font-bold text-gray-700">--}}
{{--                    No results found--}}
{{--                </div>--}}
{{--            </div>--}}
{{--    @endif--}}
</div>
</div>

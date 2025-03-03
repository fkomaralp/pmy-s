<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>

<div>
<script>
    function component(){
        return {
            currentEventsTab : 0,
            currentOrdersTab : 0,
            currentOrdersFilterTab : 0,
            showEventsTab(index) {
                this.currentEventsTab = index
            },
            showOrdersTab(index) {
                this.currentOrdersTab = index
            },
            showOrdersFilterTab(index) {
                this.currentOrdersFilterTab = index
            }
        }
    }
</script>

<div class="grid gap-4 grid-cols-2 m-3" x-data="component()">

    <div class="px-5 py-6 bg-white sm:p-5 sm:rounded-tl-md sm:rounded-tr-md">
        <div class="flex flex-row justify-between">
                    <span class="basis-1/4 text-pgreen-700 font-semibold text-xl flex items-center">
                        <x-icon name="view-grid-add" class="w-6 h-6 inline-flex" />
                        <span class="pl-2">EVENTS</span>
                    </span>
            <div class="basis-1/2 text-right">
                <ul class="block">
                    <li class="inline cursor-pointer text-sm py-2" :class="{'border-b-2 border-pgreen-700' : currentEventsTab === 0}" @click="showEventsTab(0)">CURRENT</li>
                    <li class="inline cursor-pointer ml-3 text-sm py-2" :class="{'border-b-2 border-pgreen-700' : currentEventsTab === 1}"  @click="showEventsTab(1)">UPCOMING</li>
                </ul>
            </div>
        </div>
        <div class="mt-5" x-show="currentEventsTab === 0">
            <table class="w-full">
                <thead class="">
                <tr>
                    <th scope="col"
                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                        Event Name
                    </th>
                    <th scope="col"
                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                        Date
                    </th>
{{--                    <th scope="col"--}}
{{--                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
{{--                        Organiser--}}
{{--                    </th>--}}
                    <th scope="col"
                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                        Revenue
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($events as $event)
                    <tr>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$event->title." #".$event->id}}
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$event->event_date->format("Y/m/d")}}
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            -
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                            <x-dropdown>
                                @foreach($event->price_list as $price)
                                    <x-dropdown.item label="{{$price->price->title}} £{{$price->price->price}}" />
                                @endforeach
                            </x-dropdown>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="px-5 py-6 bg-white sm:p-6 sm:rounded-tl-md sm:rounded-tr-md">
        <div class="flex flex-row justify-between">
                    <span class="basis-1/4 text-pgreen-700 font-semibold text-xl flex items-center">
                        <x-icon name="credit-card" class="w-6 h-6 inline-flex" />
                        <span class="pl-2">ORDERS</span>
                    </span>
            <div class="basis-1/2 text-right">
                <ul class="block">
                    <li class="inline cursor-pointer text-sm py-2" :class="{'border-b-2 border-pgreen-700' : currentOrdersTab === 0}" @click="showOrdersTab(0)">CURRENT</li>
                    <li class="inline cursor-pointer ml-3 text-sm py-2" :class="{'border-b-2 border-pgreen-700' : currentOrdersTab === 1}"  @click="showOrdersTab(1)">REVENUES</li>
                </ul>

            </div>
        </div>
        <div class="mt-5" x-show="currentOrdersTab === 0">

            <table class="w-full" >
                <thead class="">
                <tr>
                    <th scope="col"
                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                        Event Name
                    </th>
                    <th scope="col"
                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                        Order Date
                    </th>
                    <th scope="col"
                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                        Customer
                    </th>
                    <th scope="col"
                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                        Revenue
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$order->event_title." #".$order->event_id}}
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$order->created_at->format("Y/m/d")}}
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$order->user->name}}
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{(isset($order->orderPrice)) ? "£".$order->orderPrice->price : "Error"}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

        <div class="mt-8" x-show="currentOrdersTab === 1">
            <ul class="block">
                <li class="inline cursor-pointer text-sm py-2.5 px-2.5" :class="{'bg-pgreen-600 font-bold p-2 rounded-full' : currentOrdersFilterTab === 0}" @click="showOrdersFilterTab(0)">1 D</li>
                <li class="inline cursor-pointer ml-3 text-sm py-2.5 px-2.5" :class="{'bg-pgreen-600 font-bold p-2 rounded-full' : currentOrdersFilterTab === 1}"  @click="showOrdersFilterTab(1)">1 W</li>
                <li class="inline cursor-pointer ml-3 text-sm py-2.5 px-2.5" :class="{'bg-pgreen-600 font-bold p-2 rounded-full' : currentOrdersFilterTab === 2}"  @click="showOrdersFilterTab(2)">1 M</li>
                <li class="inline cursor-pointer ml-3 text-sm py-2.5 px-2.5" :class="{'bg-pgreen-600 font-bold p-2 rounded-full' : currentOrdersFilterTab === 3}"  @click="showOrdersFilterTab(3)">3 M</li>
                <li class="inline cursor-pointer ml-3 text-sm py-2.5 px-2.5" :class="{'bg-pgreen-600 font-bold p-2 rounded-full' : currentOrdersFilterTab === 4}"  @click="showOrdersFilterTab(4)">6 M</li>
                <li class="inline cursor-pointer ml-3 text-sm py-2.5 px-2.5" :class="{'bg-pgreen-600 font-bold p-2 rounded-full' : currentOrdersFilterTab === 5}"  @click="showOrdersFilterTab(5)">1 Y</li>
            </ul>

            <div class="flex flex-row mt-12" x-show="currentOrdersFilterTab === 0">
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">#{{$d1->count()}}</span>
                    </div>
                    <span class="block mt-3">Total Orders</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">£{{$d1->sum("orderPrice.price")}}</span>
                    </div>
                    <span class="block mt-3">Total Revenue</span>
                </div>
            </div>

            <div class="flex flex-row mt-12" x-show="currentOrdersFilterTab === 1">
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">#{{$w1->count()}}</span>
                    </div>
                    <span class="block mt-3">Total Orders</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">£{{$w1->sum("orderPrice.price")}}</span>
                    </div>
                    <span class="block mt-3">Total Revenue</span>
                </div>
            </div>

            <div class="flex flex-row mt-12" x-show="currentOrdersFilterTab === 2">
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">#{{$m1->count()}}</span>
                    </div>
                    <span class="block mt-3">Total Orders</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">£{{$m1->sum("orderPrice.price")}}</span>
                    </div>
                    <span class="block mt-3">Total Revenue</span>
                </div>
            </div>

            <div class="flex flex-row mt-12" x-show="currentOrdersFilterTab === 3">
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">#{{$m3->count()}}</span>
                    </div>
                    <span class="block mt-3">Total Orders</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">£{{$m3->sum("orderPrice.price")}}</span>
                    </div>
                    <span class="block mt-3">Total Revenue</span>
                </div>
            </div>

            <div class="flex flex-row mt-12" x-show="currentOrdersFilterTab === 4">
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">#{{$m6->count()}}</span>
                    </div>
                    <span class="block mt-3">Total Orders</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">£{{$m6->sum("orderPrice.price")}}</span>
                    </div>
                    <span class="block mt-3">Total Revenue</span>
                </div>
            </div>

            <div class="flex flex-row mt-12" x-show="currentOrdersFilterTab === 5">
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">#{{$y1->count()}}</span>
                    </div>
                    <span class="block mt-3">Total Orders</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-48 h-48  border-8 border-pgreen-700 rounded-full inline-flex justify-center items-center">
                        <span class="text-6xl">£{{$y1->sum("orderPrice.price")}}</span>
                    </div>
                    <span class="block mt-3">Total Revenue</span>
                </div>
            </div>

        </div>
    </div>
    <div class="px-5 py-6 bg-white sm:p-6 sm:rounded-tl-md sm:rounded-tr-md">
        <div class="">
            <span class="text-pgreen-700 font-semibold text-xl flex items-center">
                <x-icon name="filter" class="w-6 h-6 inline-flex" />
                <span class="pl-2">UPLOAD & ANALYSIS</span>
            </span>
        </div>
        <div class="mt-5">
            <table class="w-full">
                <thead class="">
                    <tr>
                        <th scope="col"
                            class="py-3  text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Event Name
                        </th>
                        <th scope="col"
                            class="py-3  text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Date
                        </th>
                        <th scope="col"
                            class="py-3  text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            No. of Photos
                        </th>
                        <th scope="col"
                            class="py-3  text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Unique Tags (bibs)
                        </th>
                        <th scope="col"
                            class="py-3  text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($all_events as $event)
                    <tr>
                        <td class="py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white truncate">
                            {{$event->title." #".$event->id}}
                        </td>
                        <td class="py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$event->event_date->format("Y/m/d")}}
                        </td>
                        <td class="py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            @if($event->photos) {{$event->photos->count()}} @endif
                        </td>
                        <td class="py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{($unique_cnt_collection->count() > 0) ? $unique_cnt_collection->get($event->id)->sum() : 0}}
                        </td>
                        <td class="py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            @if($dublicated_bib_numbers->has($event->id))
{{--                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{$dublicated_bib_numbers->get($event->id)->sum() . " Tag(s) repeated more than 10 times."}}" class="cursor-pointer text-sm bg-red-600 text-white font-bold rounded inline-flex justify-center items-center px-2">--}}
{{--                                    {{($dublicated_bib_numbers->get($event->id)->sum() > 0) ? $dublicated_bib_numbers->get($event->id)->count() . "/" . $dublicated_bib_numbers->get($event->id)->sum() : "0/0"}}--}}
{{--                                </span>--}}
                                <button></button>
                            @endif
                        </td>
{{--                        <td class="py-4 px-2 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">--}}
{{--                            <x-dropdown>--}}
{{--                                @foreach($event->price_list as $price)--}}
{{--                                    <x-dropdown.item label="{{$price->price->title}} £{{$price->price->price}}" />--}}
{{--                                @endforeach--}}
{{--                            </x-dropdown>--}}
{{--                        </td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

</div>

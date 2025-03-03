<x-slot name="header">
        <h2 class="left-0 font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cities') }}
        </h2>
</x-slot>

<div x-data="{ confirmModal: false, deleting: false }">
    <div class="w-full mx-auto py-10 sm:px-6 lg:px-8">
        <div class="w-full h-16 block">
            <a href="{{route("dashboard.locations.cities.create")}}"
               class="px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-positive-500 hover:bg-positive-600">Create
                City</a>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th scope="col"
                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    {{__("Country")}}
                </th>
                <th scope="col"
                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    {{__("Name")}}
                </th>
                <th scope="col"
                    class="py-3 px-6 text-xs font-medium tracking-wider text-center text-gray-700 uppercase dark:text-gray-400">
                    <div class="font-semibold">{{__("Edit")}}</div>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($cities as $city)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$city->country->name}}
                    </td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$city->name}}
                    </td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">

                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <a href="{{route("dashboard.locations.cities.edit", ["city_id" => $city->id])}}"
                               class="py-2 px-4 text-sm font-medium text-positive-600 rounded-l-lg border border-gray-200 hover:bg-gray-100 hover:text-positive-600 hover:bg-gray-100 focus:ring-4 focus:ring-gray-300">
                                {{__("Edit")}}
                            </a>
                            <button type="button" wire:click="deleteId({{$city->id}})" @click="confirmModal=true"
                                    class="py-2 px-4 text-sm font-medium text-red-700 rounded-r-lg border-b border-r border-t border-gray-200 hover:bg-gray-100 hover:text-red-700 hover:bg-gray-100 focus:ring-4 focus:ring-gray-300">
                                {{__("Delete")}}
                            </button>
                        </div>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="bg-gray-50 w-full p-3 rounded rounded-t-none">
            {{ $cities->links() }}
        </div>

        <div x-cloak x-show="confirmModal"
             class="left-0 top-0 fixed w-full bg-gray-400 bg-opacity-50 grid place-items-center h-screen">
            <div class="rounded bg-white w-2/7 object-none object-center -mt-11 border border-gray-400"
                 id="popup-modal">
                <div class="relative px-4 w-full max-w-md h-full md:h-auto">
                    <!-- Modal content -->
                    <!-- Modal header -->
                    <div class="flex justify-end p-2">
                        <button type="button" @click="confirmModal=false"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 pt-0 text-center">
                        <svg class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Data will be deleted. Are
                            you sure?</h3>
                        <button type="button" wire:click.prevent="delete()" @click="deleting=true"
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            <svg x-show="deleting" class="animate-spin -ml-2 mr-1 h-5 w-5 text-white inline-block"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Yes, I'm sure
                        </button>
                        <button type="button" @click="confirmModal=false"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                            No, cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

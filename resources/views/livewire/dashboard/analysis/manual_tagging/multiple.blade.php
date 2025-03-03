<x-image-analysis>

    <x-slot name="title">
        Multiple Tag Editing
    </x-slot>

    <script>
        function component() {
            return {}
        }
    </script>

    <div x-data="component()">
        <div class="flex">
            <div class="w-2/4 py-5 sm:px-6 lg:px-8">
                <div class="px-4 py-5 bg-white sm:p-6 ">


                    <div class="col-span-6 sm:col-span-4 mt-3 border-b border-gray-100 pb-10">
                        <x-jet-label for="bib_numbers" value="{{ __('CHOOSE EVENT TO TAG') }}"/>
                        <div class="col-span-6 sm:col-span-4 mt-3">

                            <div class="flex-none">
                                <div class="w-full inline-flex">
                                    <select class="form-select appearance-none
          w-full
          sm:pr-8
          py-1.5
          text-base
          font-normal
          text-gray-700
          bg-white bg-clip-padding bg-no-repeat
          border border-solid border-gray-300
          rounded
          transition
          ease-in-out
          m-0
          cursor-pointer
          focus:text-gray-700 focus:bg-white focus:border-black focus:outline-none" wire:model="event_id">
                                        <option value="0">Select an Event</option>
                                        @foreach($events as $event)
                                            <option value="{{$event->id}}">{{$event->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-span-6 sm:col-span-4 mt-10 border-b border-gray-100 pb-10">
                        <x-jet-label for="bib_numbers" value="{{ __('Delete Tags below from all images') }}"/>
                        <div class="col-span-6 sm:col-span-4 mt-3">

                            <div class="max-w-lg ">
                                <div class="relative">
                                    <div class="block grid grid-cols-2 gap-2">
                                        <div class="col-start-1 col-end-3">
                                            <input wire:model="delete_bib_number"
                                                   class="w-full bg-white text-gray-700 border border-gray-200 rounded-md py-2 px-4 h-10 leading-tight focus:outline-none focus:ring-1 ring-inset focus:ring-pgreen-800 focus:border-pgreen-800"
                                                   placeholder="Enter tags">
                                            <p class="text-sm text-gray-500 pt-3">You can delete all tags by writing
                                                <code class="text-red-500">all</code></p>
                                        </div>
                                        <div class="col-end-7 col-span-2">
                                            <button type="button"
                                                    class="rounded-md -ml-1.5 inline inline-flex items-center px-5 py-2 h-10 bg-pgreen-800 hover:bg-pgreen-900 border border-transparent font-semibold text-xs text-white uppercase tracking-widest active:bg-pgreen-900 focus:outline-none focus:border-pgreen-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                                                    wire:click="DeleteOnce">DELETE ONCE
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-span-6 sm:col-span-4 mt-10">
                        <x-jet-label for="bib_numbers" value="{{ __('Add Tags below to all images') }}"/>
                        <div class="col-span-6 sm:col-span-4 mt-3">

                            <div class="max-w-lg ">
                                <div class="relative">
                                    <div class="block grid grid-cols-2 gap-2">
                                        <div class="col-start-1 col-end-3">
                                            <input wire:model="new_bib_number"
                                                   class="w-full bg-white text-gray-700 border border-gray-200 rounded-md py-2 px-4 h-10 leading-tight focus:outline-none focus:ring-1 ring-inset focus:ring-pgreen-800 focus:border-pgreen-800"
                                                   placeholder="Enter tags">
                                            <p class="text-sm text-gray-500 pt-3">You can write more tags using comma
                                                (,) between tags.</p>
                                            {{--                                            <p class="text-sm text-red-500">Requested bib number will not add if there any same bib number found.</p>--}}
                                        </div>
                                        <div class="col-end-7 col-span-2">
                                            <button type="button"
                                                    class="rounded-md -ml-1.5 inline inline-flex items-center px-5 py-2 h-10 bg-pgreen-800 hover:bg-pgreen-900 border border-transparent font-semibold text-xs text-white uppercase tracking-widest active:bg-pgreen-900 focus:outline-none focus:border-pgreen-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                                                    wire:click="AddNewBibNumber">ADD ONCE
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="w-2/4 py-5 sm:px-6 lg:px-8">
                <div class="px-4 py-5 bg-white sm:p-6 h-full">

                    <table class="w-full">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Bib Number
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Count
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dublicated_bib_numbers as  $bib_number => $count)

                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{$bib_number}}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{$count}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</x-image-analysis>

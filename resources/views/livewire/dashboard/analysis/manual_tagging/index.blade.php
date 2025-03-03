<x-image-analysis>

    <x-slot name="title">
        Manual Tagging
    </x-slot>

    <script type="text/javascript" src="/frontend/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/blowup.min.js"></script>

    <script>
        window.addEventListener('update_blowup', () => {
            $(".blowup").each(function () {
                $(this).blowup({
                    "scale": 4
                });
            })
        })

        function component() {
            return {
                init() {
                    window.addEventListener('update_counter', (e) => {
                    })

                    window.Echo.channel(`image.status`)
                        .listen('ImageStatusUpdated', (e) => {
                            this.percentage = e.percentage
                            {{--if(e.completed_label != ""){--}}
                            {{--    this.counter = e.completed_label--}}
                            {{--    this.is_finished_all = e.is_finished_all--}}

                            {{--    if(this.is_finished_all){--}}
                            {{--        @this.analysisStarted = false--}}
                            {{--    }--}}
                            {{--}--}}
                            if(e.percentage === 100 && !this.analysisFinished){
                                this.analysisFinished = true
                                window.$wireui.notify({
                                    title: 'Finished',
                                    description: 'Reanalysis finished...',
                                    icon: 'info'
                                })
                            }
                        });

                    {{--if(this.ANALYSING_MANUAL_TAGGING_STATUS){--}}
                    {{--    @this.analysisStarted = true--}}
                    {{--}--}}

                },
                {{--confirmModal: @entangle('confirmModal'),--}}
                {{--deleting: @entangle('deleting'),--}}
                percentage: 0,
                analysisStarted: false,
                is_finished_all: false,
                image: Array(),
                ANALYSING_MANUAL_TAGGING_STATUS: {{$ANALYSING_MANUAL_TAGGING_STATUS}},
                {{--deleteBibNumber: (file_name, bib_number) => {--}}
                {{--    window.$wireui.confirmDialog({--}}
                {{--        title: 'Are you Sure?',--}}
                {{--        description: ' These bib number(s) ('+bib_number+') will be deleted after you are confirm.',--}}
                {{--        icon: 'question',--}}
                {{--        accept: {--}}
                {{--            label: 'Yes, delete it',--}}
                {{--            execute: () => {--}}
                {{--                @this.--}}
                {{--            },--}}
                {{--            params: 'deleteBibNumber'--}}
                {{--        },--}}
                {{--        reject: {--}}
                {{--            label: 'No, cancel'--}}
                {{--        },--}}
                {{--    }, @this)--}}
                {{--},--}}
                downloadRealImage: (photo_id) => {
                    window.$wireui.notify({
                        title: 'Please wait...',
                        description: 'Downloading...',
                        icon: 'info'
                    })

                    @this.downloadRealImage(photo_id)
                },
            }
        }
    </script>

    <div x-data="component()">

        <div class="w-full mx-auto py-6 sm:px-8 lg:px-8"
        >

            <div class="flex mb-6 flex-row space-x-8 bg-white rounded-md py-5 px-5 ">
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
                            <option value="0" selected>Select an Event</option>
                            @foreach($events as $event)
                                <option value="{{$event->id}}">{{$event->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex-none">
                    <div class="block grid grid-cols-2 gap-2">
                        <div class="col-start-1 col-end-3 relative">
                            <input wire:model="search" placeholder="Search bib number"
                                   class="w-full bg-white text-gray-700 border border-gray-200 rounded-md py-2 px-4 h-10 leading-tight focus:outline-none focus:ring-1 ring-inset focus:ring-pgreen-800 focus:border-pgreen-800">
                            <x-icon wire:click="clearSearch" x-show="$wire.search.length > 0" name="x"
                                    class="w-5 h-5 absolute top-0 right-0 mx-2 my-2.5 bg-white text-pgreen-800 cursor-pointer"
                                    x-clock/>
                        </div>
                    </div>
                </div>
                <div class="flex-none">
                    <div class="block grid grid-cols-2 gap-2">
                        <div class="col-start-1 col-end-3 relative">
                            <input wire:model="searchFileName" placeholder="Search file name"
                                   class="w-full bg-white text-gray-700 border border-gray-200 rounded-md py-2 px-4 h-10 leading-tight focus:outline-none focus:ring-1 ring-inset focus:ring-pgreen-800 focus:border-pgreen-800">
                            <x-icon wire:click="clearSearch" x-show="$wire.searchFileName.length > 0" name="x"
                                    class="w-5 h-5 absolute top-0 right-0 mx-2 my-2.5 bg-white text-pgreen-800 cursor-pointer"
                                    x-clock/>
                        </div>
                    </div>
                </div>
                <div class="flex-none my-auto">
                    <div class="form-check">
                        <input class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" wire:model="showAllUntagged" id="show_all_untagged">
                        <label class="form-check-label inline-block text-gray-800" for="show_all_untagged">
                            Show all untagged
                        </label>
                    </div>
                </div>
{{--                    <div class="flex-none">--}}
{{--                        @if($photos->count() > 0)--}}
{{--                                    <div class="w-full inline-flex">--}}
{{--                                        <x-jet-label class="mr-3 my-auto" for="sort_by" value="{{ __('Sort By') }}"/>--}}
{{--                                        <select class="form-select appearance-none--}}
{{--              w-2/2--}}
{{--              sm:pr-8--}}
{{--              py-1.5--}}
{{--              text-base--}}
{{--              font-normal--}}
{{--              text-gray-700--}}
{{--              bg-white bg-clip-padding bg-no-repeat--}}
{{--              border border-solid border-gray-300--}}
{{--              rounded--}}
{{--              transition--}}
{{--              ease-in-out--}}
{{--              m-0--}}
{{--              cursor-pointer--}}
{{--              focus:text-gray-700 focus:bg-white focus:border-black focus:outline-none" wire:model="sort_by">--}}
{{--                                            <option value="0" selected>Sort by filename</option>--}}
{{--                                            <option value="1" selected>Sort by datetime</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
            </div>



            @if($photos->count() > 0)
                <div class="w-full p-3 mb-6 rounded-t-md bg-white select-none">
                    <div class="flex">
                        <div class="flex-none">
                            <button type="button" x-on:confirm="{
        title: 'Are you sure?',
        icon: 'warning',
        method: 'tagUntaggedImages'
                                }" class="px-4 py-1.5 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-pgreen-800 hover:bg-pgreen-900 disabled:cursor-not-allowed">
                                 <span x-show="!analysisStarted || is_finished_all">Reanalyse Untagged Images</span>
                                 <span x-show="analysisStarted && !is_finished_all">Analysing...</span>
                            </button>
                        </div>
{{--                        <div class="flex-none px-3 my-auto" x-text="counter">--}}

{{--                        </div>--}}
                    </div>
                </div>

                <div class="flex mb-6 flex-row space-x-8 bg-white rounded-md py-5 px-5 " x-show="percentage < 100 && percentage > 0"  x-cloak>
                    {{--            ng-clock x-show="percentage > 0">--}}
                    <div class="w-full">
                        {{--                <table class="w-full">--}}
                        {{--                    <tbody>--}}
                        {{--                        <tr class="bg-white border-b">--}}
                        {{--                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" >Total</td>--}}
                        {{--                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap" >12</td>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr class="bg-white border-b">--}}
                        {{--                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" >Analysing</td>--}}
                        {{--                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap" >12</td>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr class="bg-white border-b">--}}
                        {{--                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" >Analysed</td>--}}
                        {{--                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap" >12</td>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr class="bg-white border-b">--}}
                        {{--                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" >Error</td>--}}
                        {{--                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap" >12</td>--}}
                        {{--                        </tr>--}}
                        {{--                    </tbody>--}}
                        {{--                </table>--}}

                        <div class="relative pt-1">
                            <div class="flex mb-5 items-center justify-between">
                                <div>
      <span class="text-xs font-medium inline-block py-1 px-2 uppercase rounded-full text-white bg-pgreen-900">
        Progress
      </span>
                                </div>
                                <div class="text-right">
      <span class="text-xs font-semibold inline-block text-pgreen-900" x-text="Math.trunc(percentage) + '%'">
      </span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full" >
                                <div class="bg-pgreen-900 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" :style="{ width: percentage+'%'}"> </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            @if($photos->hasPages())
                <div class="w-full p-3 rounded-t-md bg-white select-none">
                    <div class="flex">
                        <div class="flex-none">
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
          focus:text-gray-700 focus:bg-white focus:border-black focus:outline-none" wire:model="show_records">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="flex-1 ml-3">
                            <div class="w-full">
                            {{$photos->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{--                <table class="w-full">--}}
            {{--                    <thead class="bg-gray-50 dark:bg-gray-700">--}}
            {{--                    <tr>--}}
            {{--                        <th scope="col"--}}
            {{--                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
            {{--                            Preview--}}
            {{--                        </th>--}}
            {{--                        <th scope="col"--}}
            {{--                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
            {{--                            Filename--}}
            {{--                        </th>--}}
            {{--                        <th scope="col"--}}
            {{--                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
            {{--                            Bib Numbers--}}
            {{--                        </th>--}}
            {{--                        <th scope="col"--}}
            {{--                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
            {{--                            EDIT--}}
            {{--                        </th>--}}
            {{--                    </tr>--}}
            {{--                    </thead>--}}
            {{--                    <tbody>--}}
            {{--                    @if($photos->count() > 0)--}}
            {{--                        @foreach($photos as $photo)--}}
            {{--                            @php--}}
            {{--                                $bib_number_list = [];--}}

            {{--                                    foreach($photo->bib_numbers as $_bib_number){--}}
            {{--                                        $bib_number_list[] = $_bib_number->bib_number;--}}
            {{--                                    }--}}
            {{--                            @endphp--}}
            {{--                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">--}}
            {{--                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">--}}
            {{--                                    <img src="{{$photo->thumbnail}}" width="100">--}}
            {{--                                </td>--}}
            {{--                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">--}}
            {{--                                    {{$photo->file_name}}--}}
            {{--                                </td>--}}
            {{--                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">--}}
            {{--                                    {{implode(" - ", $bib_number_list)}}--}}
            {{--                                </td>--}}
            {{--                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">--}}
            {{--                                    <div class="inline-flex rounded-md shadow-sm" role="group">--}}
            {{--                                        <a href="{{route("dashboard.analysis.manual_tagging.edit", ["bib_number_file" => $photo->file_name])}}"--}}
            {{--                                           class="py-2 px-4 text-sm font-medium text-positive-600 rounded-l-lg border border-gray-200 hover:bg-gray-100 hover:text-positive-600 hover:bg-gray-100 focus:ring-4 focus:ring-gray-300">--}}
            {{--                                            {{__("Edit")}}--}}
            {{--                                        </a>--}}
            {{--                                        <button type="button" wire:click="deleteId('{{$photo->file_name}}')" @click="confirmModal=true"--}}
            {{--                                                class="py-2 px-4 text-sm font-medium text-red-700 rounded-r-lg border-b border-r border-t border-gray-200 hover:bg-gray-100 hover:text-red-700 hover:bg-gray-100 focus:ring-4 focus:ring-gray-300">--}}
            {{--                                            {{__("Delete")}}--}}
            {{--                                        </button>--}}
            {{--                                    </div>--}}
            {{--                                </td>--}}
            {{--                            </tr>--}}
            {{--                        @endforeach--}}

            {{--                    @else--}}
            {{--                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">--}}
            {{--                            <td colspan="4" class="text-center">--}}
            {{--                                <img class="mx-auto" src="/img/no-data-found.png" width="400">--}}
            {{--                                <span class="mx-auto inline-block font-medium text-2xl text-gray-500 mb-5">No data found</span>--}}
            {{--                            </td>--}}
            {{--                        </tr>--}}
            {{--                    @endif--}}
            {{--                    </tbody>--}}
            {{--                    <tfoot class="bg-gray-50 dark:bg-gray-700">--}}
            {{--                    <tr>--}}
            {{--                        <th scope="col"--}}
            {{--                             class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
            {{--                            Preview--}}
            {{--                        </th>--}}
            {{--                        <th scope="col"--}}
            {{--                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
            {{--                            Filename--}}
            {{--                        </th>--}}
            {{--                        <th scope="col"--}}
            {{--                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
            {{--                            Bib Numbers--}}
            {{--                        </th>--}}
            {{--                        <th scope="col"--}}
            {{--                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
            {{--                            EDIT--}}
            {{--                        </th>--}}
            {{--                    </tr>--}}
            {{--                    </tfoot>--}}
            {{--                </table>--}}

            {{--                <div class="w-1/4 py-10 sm:px-6 lg:px-8">--}}
            {{--                    <div class="flex items-center justify-center px-4 py-3 bg-gray-200 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">--}}
            {{--                        resim--}}
            {{--                    </div>--}}
            {{--                    <div class="px-4 py-5 bg-white sm:p-6 ">--}}
            {{--                        <img class="rounded blowup" src="{{$photo->thumbnail}}">--}}
            {{--                    </div>--}}
            {{--                    <div class="flex items-center justify-center px-4 py-3 bg-gray-200 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">--}}
            {{--                        <button type="button" @click="downloadRealImage()" class="inline-flex items-center px-4 py-2 bg-pgreen-800 hover:bg-pgreen-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-pgreen-900 focus:outline-none focus:border-pgreen-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">Download real image</button>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            @if($photos->count() > 0)
                <div class="flex flex-wrap justify-center">
                @foreach($photos as $photo)
                    <div class="w-2/3">
                        <div class="py-10 sm:px-6 lg:px-8">
                                <div class="w-full bg-white rounded-t p-3 text-center">{{$photo->file_name}}</div>
                                <div class="relative">
                                    <div class="absolute w-full bottom-0">
                                        <div class="flex justify-between">
                                        <div>
                                            <button type="button" @click="downloadRealImage({{$photo->id}})" title="Download real size of image" class="p-3 bg-pgreen-800 text-white">
                                                <x-icon name="download" class="w-5 h-5" />
                                            </button>
                                        </div>
                                        <div class="">
                                            <button type="button" wire:click="prevImage({{$photo->event_id}}, '{{$photo->file_name}}')" title="Previous Image" class="p-3 bg-pgreen-800 text-white">
                                                <x-icon name="chevron-left" class="w-5 h-5" />
                                            </button>
                                            <button type="button" wire:click="returnToRealImage({{$photo->id}})" title="Return to Active Image" class=" p-3 bg-pgreen-1000 text-white">
                                                <x-icon name="refresh" class="w-5 h-5" />
                                            </button>
                                            <button type="button" wire:click="nextImage({{$photo->event_id}},  '{{$photo->file_name}}')" title="Next Image" class=" p-3 bg-pgreen-800 text-white">
                                                <x-icon name="chevron-right" class="w-5 h-5" />
                                            </button>
                                        </div>
                                        <div>
                                            <button type="button"
                                                    x-on:confirm="{
                title: 'Are you sure?',
                description: 'This image will be permanently deleted',
                icon: 'warning',
                method: 'deletePhoto',
                params: {{$photo->id}}
            }"
                                                    title="Remove image" class="p-3 bg-red-600 text-white">
                                                <x-icon name="trash" class="w-5 h-5" />
                                            </button>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="absolute bg-white w-full h-full opacity-25" x-cloak x-show="$wire.loading[{{$photo->id}}]">

                                    </div>
                                    <img class="blowup w-full" src="{{isset($image_bag[$photo->file_name]["image"]) ? $image_bag[$photo->file_name]["image"] : $photo->thumbnail}}">
                                </div>
                                <div class="px-4 py-5 bg-white sm:p-6 ">
                                    <div class="col-span-6 sm:col-span-4 mt-3">
                                        {{--                                        <x-jet-label for="bib_numbers" value="{{ __('Bib Numbers') }}"/>--}}
                                        <div class="col-span-6 sm:col-span-4 mt-3">

                                            <div class="w-full">
                                                <div class="relative">
                                                    <div class="block grid grid-cols-2 gap-2">
                                                        <div class="col-start-1 col-end-3">
                                                            <input wire:model="new_bib_number"
                                                                   class="w-full bg-white text-gray-700 border border-gray-200 rounded-md py-2 px-4 h-10 leading-tight focus:outline-none focus:ring-1 ring-inset focus:ring-pgreen-800 focus:border-pgreen-800"
                                                                   placeholder="Enter tags" wire:keydown.enter="AddNewBibNumber({{$photo->id}})">
                                                        </div>
                                                    </div>

                                                    @foreach($photo->bib_numbers as $bib_number)
                                                        <div
                                                            class="bg-gray-100 inline-flex items-center text-sm rounded mt-4 mr-1 overflow-hidden">
                                                            <span
                                                                class="ml-2 mr-1 leading-relaxed truncate max-w-xs px-1">{{$bib_number->bib_number}}</span>
                                                            <button
                                                                class="w-6 h-8 inline-block align-middle text-gray-500 bg-gray-200 focus:outline-none"
                                                                type="button"
                                                                wire:click="deleteBibNumber('{{$photo->id}}', '{{$bib_number->bib_number}}')">
                                                                <x-icon name="x" class="w-5 h-5 fill-current mx-auto"/>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                {{--                        <div class="flex items-center justify-end px-4 py-3 bg-gray-200 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">--}}
                                {{--                            <x-jet-secondary-button class="mr-3" wire:click="back()">--}}
                                {{--                                {{ __('Cancel') }}--}}
                                {{--                            </x-jet-secondary-button>--}}
                                {{--                            <x-jet-button>--}}
                                {{--                                {{ __('Update') }}--}}
                                {{--                            </x-jet-button>--}}
                                {{--                        </div>--}}
                        </div>
                    </div>
                @endforeach
                </div>
            @endif

            @if($photos->hasPages())
                <div class="w-full p-3 rounded-t-md bg-white select-none">
                    <div class="flex">
                        <div class="flex-none">
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
          focus:text-gray-700 focus:bg-white focus:border-black focus:outline-none" wire:model="show_records">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="flex-1 ml-3">
                            <div class="w-full">
                                {{$photos->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</x-image-analysis>

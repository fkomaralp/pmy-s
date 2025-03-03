<x-image-analysis>

    <x-slot name="title">
        Image Analysis
    </x-slot>


        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://unpkg.com/async@3.2.3/dist/async.min.js"></script>

        <script>
            function component() {
                return {
                    images: @entangle('images'),
                    axios_bag: {},
                    status: {},
                    _status: {},
                    event_id: 0,
                    image_status_interval: {},
                    analysisFinished: false,
                    analysisStarted: false,
                    skipFinished: false,
                    skipStarted: false,
                    {{--IMAGE_ANALYSIS_MAX_PARALLEL_COUNT: {{$IMAGE_ANALYSIS_MAX_PARALLEL_COUNT}},--}}
                    {{--ANALYSING_STATUS: {{$ANALYSING_STATUS}},--}}
                    {{--SKIP_ANALYSING_STATUS: {{$SKIP_ANALYSING_STATUS}},--}}
                    pageNumber: 0,
                    size: 20,
                    total: 0,
                    start: 0,
                    end: 5,
                    counter: "0/0",
                    percentage: 0,
                    init() {
                        // setInterval(() => {
                        //
                        //     let count = 0
                        //
                        //     this.progress.forEach(function(value, index){
                        //         if(value >= 100){
                        //             count++
                        //         }
                        //     })
                        //
                        //     if(count === this.images.length){
                        //         this.allImagesUploaded = true
                        //         this.upload_started = false
                        //     }
                        //
                        // }, 500);

                        Livewire.on('updatePageVariables', (data) => {
                            this.total = data.images.length
                            this.analysisFinished = false
                            this.status = data.status
                            // this.counter = data.completed_label

                            // if(this.ANALYSING_STATUS){
                            //     this.startAnalysis()
                            // }
                            //
                            // if(this.SKIP_ANALYSING_STATUS){
                            //     this.skipToManualTagging()
                            // }

                        })



                        window.Echo.channel(`image.status`)
                            .listen('ImageStatusUpdated', (e) => {

                                // let result = ""
                                // let is_finished = false
                                //
                                switch (e.image.status){
                                    case -1:
                                        result = "In Progress";
                                        break;
                                    case 0:
                                        result = "In Progress";
                                        break;
                                    case 1:
                                        result = "In Progress";
                                        break;
                                    case 2:
                                        result = "Finished";
                                        is_finished = true;
                                        break;
                                    case 3:
                                        result = "Error";
                                        is_finished = true;
                                        break;
                                }

                                this.status[e.image.file_name] = result
                                this._status[e.image.file_name] = result

                                this.percentage = e.percentage

                                if(e.percentage === 100 && !this.analysisFinished){
                                    this.analysisFinished = true
                                    window.$wireui.notify({
                                        title: 'Finished',
                                        description: 'Analysis finished...',
                                        icon: 'info'
                                    })
                                }

                                {{--if(this.skipStarted) {--}}
                                {{--    this.skipFinished = e.is_finished_all--}}
                                {{--    this.skipStarted = !e.is_finished_all--}}
                                {{--} else if(this.analysisStarted){--}}
                                {{--    this.analysisFinished = e.is_finished_all--}}
                                {{--    this.analysisStarted = !e.is_finished_all--}}
                                {{--}--}}

                                {{--if (e.is_finished_all) {--}}
                                {{--    --}}{{--axios.post('{{route("api.image_analysis.analysis_finished")}}', {--}}
                                {{--    --}}{{--    event_id: @this.event_id--}}
                                {{--    --}}{{--})--}}
                                {{--}--}}

                                // if(is_finished){
                                //     if(typeof this.axios_bag[e.image.file_name] === "function"){
                                //         this.axios_bag[e.image.file_name]()
                                //     }
                                //
                                //     this.counter = e.completed_label
                                // }

                            });
                    },
                    pages() {
                        return Array.from({
                            length: Math.ceil(this.total / this.size),
                        });
                    },
                    //Next Page
                    nextPage() {
                        this.pageNumber++

                        if(this.pageNumber < 1){
                            this.pageNumber = 1
                        } else if(this.pageNumber > this.pages().length-1) {
                            this.pageNumber = this.pages().length-1
                        }
                    },

                    //Previous Page
                    prevPage() {
                        if(this.pageNumber <= 0){
                            this.pageNumber = 0
                        } else {
                            this.pageNumber--
                        }
                    },
                    //Total number of pages
                    pageCount() {
                        return Math.ceil(this.total / this.size);
                    },
                    //Link to navigate to page
                    viewPage(index) {
                        if(index < 0){
                            index = 0
                        } else if(index > this.pages().length-1){
                            index = this.pages().length-1
                        }
                        this.pageNumber = index;
                    },
                    getData() {
                        this.start = this.pageNumber * this.size,
                            this.end = this.start + this.size;

                        return this.images.slice(this.start, this.end);
                    },
                    startAnalysis() {
                        axios.post('{{route("api.image_analysis.start")}}', {
                            event_id: @this.event_id
                        }).then((result) => {
                            window.$wireui.notify({
                                title: 'Info',
                                description: result.data.result,
                                icon: 'info'
                            })
                        })
                    },
                    skipToManualTagging() {
                        axios.post('{{route("api.image_analysis.without_analysis")}}', {
                            event_id: @this.event_id
                        }).then((result) => {
                            window.$wireui.notify({
                                title: 'Info',
                                description: result.data.result,
                                icon: 'info'
                            })
                        })
                    },
                    removeFile(i){
                        this.images.splice(i, 1);
                    }
                }
            }
        </script>

    <div class="w-full mx-auto py-6 sm:px-8 lg:px-8" x-data="component()"
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
                        <option value="0">Select an Event</option>
                        @foreach($events as $event)
                            <option value="{{$event->id}}">{{$event->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex-none">
                <button type="button"
                        :disabled="images.length <= 0 || analysisStarted || skipStarted"
                        @click="startAnalysis()"
                        class="px-4 py-1.5 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-pgreen-800 hover:bg-pgreen-900 disabled:cursor-not-allowed">
                    <span x-show="!analysisStarted" >Start Analysis</span>
                    <span x-show="analysisStarted" x-cloak>Analysing...</span>
                </button>
            </div>
            <div class="flex-none" >
                <button type="button"
                        :disabled="images.length <= 0"
                        @click="skipToManualTagging()"
                        class="px-4 py-1.5 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-pgreen-800 hover:bg-pgreen-900 disabled:cursor-not-allowed">
                    <span >Skip to Manual Tagging</span>
                </button>
            </div>
{{--            <div class="flex-none my-auto" x-show="images.length > 0 || analysisStarted || skipStarted" x-cloak>--}}
{{--                ( <span x-text="counter"></span> )--}}
{{--            </div>--}}
            <div class="flex-1 w-64 self-center text-green-400" x-cloak x-show="analysisFinished || skipFinished" >
                Analysis finished.
            </div>

        </div>

        <div class="flex mb-6 flex-row space-x-8 bg-white rounded-md py-5 px-5 " x-show="percentage < 100 && percentage > 0" x-cloak>
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

{{--        <div class="flex mb-6 flex-row space-x-8 bg-white rounded-l-full rounded-r-md">--}}
{{--            <div class="flex-1 inline-flex">--}}
{{--                <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">--}}
{{--                    <svg class="h-6 w-6 text-orange-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>--}}
{{--                    </svg>--}}
{{--                </div>--}}
{{--                <p class="self-center pl-3">Selected images are will be upload and analysis immediately.</p>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="bg-gray-50 w-full p-3 rounded-t-md  select-none" x-show="images.length > size" x-cloak>
            <div class="flex justify-between">
                <div class="self-center">
                        <span class="text-sm text-gray-700 leading-5">
                            Showing <span x-text="(start === 0) ? 1 : start"></span> to <span x-text="end"></span> of <span x-text="total"></span> image(s)
                        </span>
                </div>

                <div>
                    <div class="flex shadow-sm" >
                        <p
                            @click="prevPage()"
                            class="cursor-pointer relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </p>
                        <p
                            @click="viewPage(0)"
                            class="cursor-pointer relative items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 w-16 text-center">
                            1
                        </p>

                        <input @input="viewPage($event.target.value-1)"  :value="pageNumber+1" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 w-16 text-center self-center" />

                        <p
                            @click="viewPage(total)"
                            class="cursor-pointer relative items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 w-16 text-center" x-text="pages().length">

                        </p>
                        <p
                            @click="nextPage()"
                            class="cursor-pointer relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md leading-5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th scope="col"
                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Filename
                </th>
                <th scope="col"
                    class="py-3 px-6 text-xs font-medium tracking-wider text-gray-700 uppercase dark:text-gray-400 text-center">
                    AI
                </th>
{{--                <th scope="col"--}}
{{--                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
{{--                    Action--}}
{{--                </th>--}}
            </tr>
            </thead>
            <tbody>
                <template x-for="image in getData()">
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"
                            x-text="image.file_name"
                        >

                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white text-center"
                            x-text="status[image.file_name] || 'Waiting'"
                        >
                        </td>
{{--                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white" >--}}
{{--                            <button--}}
{{--                                x-show="_status[image.file_name] === 1 || _status[image.file_name] === 0"--}}
{{--                                class="bg-red-600 p-2 text-white rounded-md shadow-md" @click="removeFile(image.file_name)">Stop and Delete</button>--}}
{{--                        </td>--}}
                    </tr>
                </template>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" x-show="images.length <= 0">
                    <td colspan="3" class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="flex flex-col text-center p-6">
                            <div class="w-full mt-5 mb-5">
                                <x-icon name="information-circle" class="w-20 h-20 text-gray-200 mx-auto" />
                            </div>
                            <div class="text-gray-500 m">
                                Please select an event to show data.
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="bg-gray-50 w-full p-3 rounded rounded-t-none select-none" x-show="images.length > size" x-cloak>
            <div class="flex justify-between">
                <div class="self-center">
                        <span class="text-sm text-gray-700 leading-5">
                            Showing <span x-text="(start === 0) ? 1 : start"></span> to <span x-text="end"></span> of <span x-text="total"></span> image(s)
                        </span>
                </div>

                <div>
                    <div class="flex shadow-sm" >
                        <p
                            @click="prevPage()"
                            class="cursor-pointer relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </p>
                        <p
                            @click="viewPage(0)"
                            class="cursor-pointer relative items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 w-16 text-center">
                            1
                        </p>

                        <input @input="viewPage($event.target.value-1)"  :value="pageNumber+1" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 w-16 text-center self-center" />

                        <p
                            @click="viewPage(total)"
                            class="cursor-pointer relative items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 w-16 text-center" x-text="pages().length">

                        </p>
                        <p
                            @click="nextPage()"
                            class="cursor-pointer relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md leading-5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </p>
                    </div>
                </div>
            </div>
        </div>
{{--        <div class="bg-gray-50 w-full p-3 rounded rounded-t-none">--}}
{{--            {{ $events->links() }}--}}
{{--        </div>--}}
    </div>

</x-image-analysis>

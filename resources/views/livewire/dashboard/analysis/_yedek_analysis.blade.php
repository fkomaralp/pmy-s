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
                    status: {},
                    _status: {},
                    event_id: 0,
                    image_status_interval: {},
                    analysisFinished: false,
                    analysisStarted: false,
                    IMAGE_ANALYSIS_MAX_PARALLEL_COUNT: {{$IMAGE_ANALYSIS_MAX_PARALLEL_COUNT}},
                    pageNumber: 0,
                    size: 5,
                    total: 0,
                    start: 0,
                    end: 5,
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
                        })
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
                        this.analysisFinished = false
                        this.analysisStarted = true

                        async.mapLimit(this.images, this.IMAGE_ANALYSIS_MAX_PARALLEL_COUNT, (file, cb) => {

                            if(file.status === 0) {
                                axios.post('{{route("api.image_analysis.start")}}', {
                                    file_name: file.file_name,
                                    event_id: @this.event_id
                                }).then((result) => {

                                    let interval_function = () => {
                                        axios.post('{{route("api.image_status")}}', {
                                            "event_id": @this.event_id,
                                            "file_name": file.file_name
                                        }).then((result) => {
                                            if (result.data.status) {
                                                this.status[file.file_name] = result.data.result
                                                this._status[file.file_name] = result.data._result

                                                this.analysisFinished = result.data.is_finished_all
                                                this.analysisStarted = !result.data.is_finished_all

                                                if (result.data.is_finished_all) {
                                                    {{--axios.post('{{route("api.image_analysis.clear_finished")}}', {--}}
                                                    {{--    event_id: @this.event_id--}}
                                                    {{--})--}}
                                                }

                                                if (result.data.is_finished) {
                                                    cb()
                                                } else {
                                                    setTimeout(interval_function, 1000);
                                                }
                                            }
                                        })
                                    };

                                    interval_function()
                                })
                            } else {
                                cb()
                            }
                        }, (err, contents) => {
                            if (err) throw err
                        })

                        {{--async.mapLimit(this.images, 5, async file => {--}}

                        {{--    axios.post('{{route("api.image_analysis.start")}}', {--}}
                        {{--        file_name: file.file_name,--}}
                        {{--        event_id: @this.event_id--}}
                        {{--    }).then((result) => {--}}

                        {{--        this.image_status_interval[file.file_name] = setInterval(() => {--}}
                        {{--            axios.post('{{route("api.image_status")}}', {--}}
                        {{--                "event_id": @this.event_id,--}}
                        {{--                "file_name": file.file_name--}}
                        {{--            }).then((result) => {--}}
                        {{--                if(result.data.status){--}}
                        {{--                    this.status[file.file_name] = result.data.result--}}
                        {{--                    this._status[file.file_name] = result.data._result--}}

                        {{--                    this.analysisFinished = result.data.is_finished_all--}}
                        {{--                    this.analysisStarted = !result.data.is_finished_all--}}

                        {{--                    if(result.data.is_finished){--}}
                        {{--                        clearInterval(this.image_status_interval[file.file_name])--}}
                        {{--                    }--}}
                        {{--                }--}}
                        {{--            })--}}

                        {{--        },1000)--}}

                        {{--    })--}}

                        {{--})--}}
                    },
                    {{--addFiles(event){--}}
                    {{--    for(const file of event.target.files){--}}

                    {{--        let isAvailable = this.images.filter(a => a.name === file.name)--}}

                    {{--        if(isAvailable.length <= 0){--}}
                    {{--            this.images.push(file)--}}
                    {{--            let index = this.images.findIndex(a => a.name === file.name)--}}

                    {{--            this.progress[index] = 0--}}
                    {{--            this.status[index] = "Waiting"--}}

                    {{--            async.mapLimit(this.images, 10, async file => {--}}

                    {{--                var formData = new FormData();--}}
                    {{--                formData.append("image", file);--}}
                    {{--                formData.append("event_id", @this.event_id);--}}

                    {{--                axios.post('{{route("api.upload_manager")}}', formData, {--}}
                    {{--                    headers: {--}}
                    {{--                        'Content-Type': 'multipart/form-data'--}}
                    {{--                    },--}}
                    {{--                    onUploadProgress: (progressEvent) => {--}}
                    {{--                        let percentage = Math.round((progressEvent.loaded * 100) / progressEvent.total)--}}

                    {{--                        if((this.progress[index] !== percentage ||--}}
                    {{--                                this.progress[index] < percentage)){--}}
                    {{--                            this.progress[index] = percentage--}}
                    {{--                        }--}}
                    {{--                    }--}}
                    {{--                }).then(() => {--}}
                    {{--                    this.image_status_interval[index] = setInterval(() => {--}}

                    {{--                        axios.post('{{route("api.image_status")}}', {--}}
                    {{--                            "event_id": @this.event_id,--}}
                    {{--                            "file_name": file.name--}}
                    {{--                        }).then((result) => {--}}
                    {{--                            if(result.data.status){--}}
                    {{--                                this.status[index] = result.data.result--}}

                    {{--                                if(result.data.is_finished){--}}
                    {{--                                    clearInterval(this.image_status_interval[index])--}}
                    {{--                                }--}}

                    {{--                            }--}}
                    {{--                        })--}}

                    {{--                    },1000)--}}
                    {{--                })--}}

                    {{--            }, (err, contents) => {--}}
                    {{--                if (err) throw err--}}
                    {{--                console.log(contents)--}}
                    {{--            })--}}
                    {{--        }--}}
                    {{--    }--}}
                    {{--},--}}
                    removeFile(i){
                        this.images.splice(i, 1);
                    }
                }
            }
        </script>

    <div class="w-full mx-auto py-6 sm:px-8 lg:px-8" x-data="component()"
    >

        <div class="flex mb-6 flex-row space-x-8 bg-gray-50 rounded-md py-5 px-5 ">
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
                        :disabled="images.length <= 0 || analysisStarted"
                        @click="startAnalysis()"
                        class="px-4 py-1.5 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-pgreen-800 hover:bg-pgreen-900 disabled:cursor-not-allowed">
                    <span x-show="!analysisStarted" x-cloak >Start Analysis</span>
                    <span x-show="analysisStarted" x-cloak>Analysing...</span>
                </button>
            </div>
            <div class="flex-1 w-64 self-center text-green-400" x-cloak x-show="analysisFinished" >
                Analysis finished.
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

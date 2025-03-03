<x-image-analysis>

    <x-slot name="title">
        Upload Manager
    </x-slot>

        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://unpkg.com/async@3.2.3/dist/async.min.js"></script>
        <script src="https://unpkg.com/compress.js@1.2.2/demo/compress.js"></script>

    <script>
        function component() {
            return {
                images: [],
                progress: {},
                errors: {},
                show_errors: {},
                is_uploaded: {},
                upload_errors: {},
                allImagesUploaded: false,
                MAX_PARALLEL_COUNT: {{$MAX_PARALLEL_COUNT}},
                pageNumber: 0,
                minutes:0,
                seconds:1,
                size: 5,
                total: 0,
                start: 0,
                end: 5,
                upload_started: false,
                {{--old_images: {!! $uploaded_old_images !!},--}}
                init() {

                    this.$watch('is_uploaded', _value => {

                        let uploaded_count = Object.entries(this.is_uploaded).filter(([key, value]) => value === true).length
                        let upload_errors = Object.entries(this.upload_errors).filter(([key, value]) => value === true).length

                        if(uploaded_count >= this.images.length || (uploaded_count + upload_errors) >= this.images.length) {
                            this.allImagesUploaded = true
                            this.upload_started = false
                        }
                    })

                    this.$watch('upload_errors', _value => {

                        let uploaded_count = Object.entries(this.is_uploaded).filter(([key, value]) => value === true).length
                        let upload_errors = Object.entries(this.upload_errors).filter(([key, value]) => value === true).length

                        if(uploaded_count >= this.images.length || (uploaded_count + upload_errors) >= this.images.length) {
                            this.allImagesUploaded = true
                            this.upload_started = false
                        }
                    })

                    this.minutes = this.pad(0, 2)
                    this.seconds = this.pad(1, 2)
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
                addFiles(event){

                    let new_images = []

                    {{--let old_images = this.old_images[@this.event_id]--}}

                    let files = event.target.files

                    // if(Array.isArray(old_images))
                    // {
                    //     new_images = [...files].filter(function (item) {
                    //         return old_images.filter(function (_item) {
                    //             return item.name === _item.file_name;
                    //         }).length <= 0
                    //     });
                    // } else {
                    //     new_images = files
                    // }

                    new_images = files

                    for(const file of new_images){
                        let isAvailable = this.images.filter(a => a.name === file.name)

                        if(isAvailable.length <= 0){
                            this.images.push(file)
                            let index = this.images.findIndex(a => a.name === file.name)

                            this.progress[file.name] = 0
                            this.errors[file.name] = []
                            this.is_uploaded[file.name] = false
                        }
                    }

                    // console.log(this.progress)

                    this.total = this.images.length
                },
                removeFile(i){
                    this.images.splice(i, 1);
                },
                pad(num, size) {
                    num = num.toString();
                    while (num.length < size) num = "0" + num;
                    return num;
                },
                openDialog(){
                    let event_id = @this.event_id

                    if(event_id === 0){
                        alert("Please select an event title.")
                    } else {
                        this.$refs.images_input.click()
                    }
                },
                clearSelectedImages(){
                    this.$refs.images_input.value = null
                    this.images = []
                    this.allImagesUploaded = false

                    this.minutes = this.pad(0, 2)
                    this.seconds = this.pad(1, 2)

                },
                retryFailedUploads(){
                    this.allImagesUploaded = false

                    this.startUpload()

                    // for(const file of new_images){
                    //     let isAvailable = this.images.filter(a => a.name === file.name)
                    //
                    //     if(isAvailable.length <= 0){
                    //         this.images.push(file)
                    //         let index = this.images.findIndex(a => a.name === file.name)
                    //
                    //         this.progress[file.name] = 0
                    //         this.errors[file.name] = []
                    //         this.is_uploaded[file.name] = false
                    //     }
                    // }

                },
                getData() {
                    this.start = this.pageNumber * this.size,
                        this.end = this.start + this.size;

                    return this.images.slice(this.start, this.end);
                },
                showErrors(key){
                    $openModal(key)
                },
                startUpload() {

                    this.minutes = this.pad(0, 2)
                    this.seconds = this.pad(1, 2)

                    this.upload_started = true

                    setInterval(() => {
                        if(this.seconds > 58){
                            this.seconds = this.pad(0, 2)
                            this.minutes++
                            this.minutes = this.pad(this.minutes, 2)
                        } else {
                            this.seconds++
                            this.seconds = this.pad(this.seconds, 2)
                        }

                    }, 1000);

                    async.mapLimit(this.images, this.MAX_PARALLEL_COUNT, (file, cb) => {

                        if(this.is_uploaded[file.name] !== true){

                            // Initialization
                            let compress = new Compress()

                            const worker = new Worker('/js/worker.js');

                            compress.compress([file], {
                                size: 3, // the max size in MB, defaults to 2MB
                                quality: .95, // the quality of the image, max is 1,
                                maxWidth: 4000, // the max width of the output image, defaults to 1920px
                                maxHeight: 4000, // the max height of the output image, defaults to 1920px
                                resize: true, // defaults to true, set false if you do not want to resize the image width and height
                                rotate: false, // See the rotation section below
                            }).then((data) => {

                                worker.onmessage = (e) => {

                                    if(e.data.is_percentage && (this.progress[e.data.file_name] !== e.data.percentage ||
                                        this.progress[e.data.file_name] < e.data.percentage)) {

                                        this.progress[e.data.file_name] = e.data.percentage
                                    }

                                    if(e.data.is_error) {
                                        this.errors[e.data.file_name] = e.data.errors
                                    }

                                    if(e.data.is_uploaded) {
                                        this.is_uploaded[e.data.file_name] = true
                                        data = undefined
                                        compress = undefined
                                        file = undefined
                                        worker.terminate()
                                        cb()
                                    }

                                    if(e.data.upload_errors) {
                                        this.upload_errors[e.data.file_name] = true
                                        data = undefined
                                        compress = undefined
                                        file = undefined
                                        worker.terminate()
                                        cb()
                                    }

                                }
                                worker.onerror = (e) => {
                                    this.upload_errors[e.data.file_name] = true

                                    data = undefined
                                    compress = undefined
                                    file = undefined
                                    worker.terminate()
                                    cb()
                                }
                                worker.postMessage({'file_data': data[0], 'event_id' : @this.event_id, 'url': '{{route("api.upload_manager")}}'})
                            }).catch((e) => {

                                this.upload_errors[file.name] = true

                                compress = undefined
                                file = undefined
                                worker.terminate()
                                cb()
                            })
                         }

                    }, (err, contents) => {
                        if (err){
                            throw err
                        }
                    })
                }
            }
        }
    </script>
{{--    }).then((result) => {--}}
{{--    if(!result.data.status){--}}
{{--    this.errors[file_data.alt] = result.data.errors--}}
{{--    self.postMessage({'file_name' : file_data.alt, 'percentage' : percentage})--}}
{{--    }--}}

{{--    this.is_uploaded[file_data.alt] = true--}}

{{--    // file = null--}}
{{--    // data = null--}}
{{--    //--}}
{{--    // compress = null--}}
{{--    //--}}
{{--    // cb()--}}
{{--    }).catch((error) => {--}}

{{--    this.upload_errors[file_data.alt] = true--}}

{{--    // file = null--}}
{{--    // data = null--}}
{{--    //--}}
{{--    // compress = null--}}
{{--    });--}}
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
      focus:text-gray-700 active:border-transparent focus:bg-white focus:border-transparent focus:outline-none" aria-label="Default select example" wire:model="event_id">
                            <option value="0">Select an Event</option>
                            @foreach($events as $event)
                                <option value="{{$event->id}}">{{$event->title}}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="flex-none">
                <input type="file" x-ref="images_input" class="hidden" x-on:change="addFiles($event)" multiple>
                <button type="button" @click="openDialog()"
                   class="px-4 py-1.5 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-pgreen-1000 hover:bg-pgreen-1100">
                    Select image(s)
                </button>
            </div>
            <div class="flex-none">
                <button type="button" @click="startUpload()" :disabled="images.length <= 0 || upload_started"
                   class="px-4 py-1.5 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-pgreen-800 hover:bg-pgreen-900 disabled:cursor-not-allowed">
                    <span x-show="!upload_started">Start Upload</span>
                    <span x-show="upload_started" x-cloak>Uploading... ( <span x-text="is_uploaded ? Object.entries(is_uploaded).filter(([key, value]) => value === true).length : 0"></span> / <span x-text="images.length"></span> )</span>

                </button>
            </div>
            <div class="flex-none m-auto" x-show="upload_started" x-cloak>
                <span ><span x-text="minutes+':'+seconds"></span></span>
            </div>
            <div class="flex-1 w-64 self-center text-green-400" x-cloak x-show="allImagesUploaded">
                Upload finished.
            </div>

            <div class="flex-1 text-right">
                <button type="button" @click="clearSelectedImages()" x-show="allImagesUploaded" x-cloak
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Clear current finished process."
                        class="px-4 py-1.5 border border-transparent disabled:bg-red-600 disabled:cursor-not-allowed rounded-md shadow-sm text-base font-medium text-white bg-red-500 hover:bg-red-600">
                    Clear uploaded image(s)
                </button>
            </div>

        </div>

        <div class="flex mb-6 flex-row space-x-8 bg-gray-50 rounded-md py-5 px-5 " x-show="allImagesUploaded" x-cloak>
            <div class="flex-none w-full">
                    <div class="text-black font-medium text-xl w-full border-b-2 pb-3 block">Results</div>
                    <div class="w-full block pt-3">
                        Success : <span x-text="Object.entries(is_uploaded).filter(([key, value]) => value === true).length"></span>
                        Failed : <span x-text="Object.entries(upload_errors).filter(([key, value]) => value === true).length"></span>
                    </div>
{{--                    <div class="w-full block pt-3" x-cloak x-show="Object.entries(upload_errors).filter(([key, value]) => value === true).length > 0">--}}
{{--                        <button type="button" class="px-4 py-1.5 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-pgreen-1000 hover:bg-pgreen-1100" @click="retryFailedUploads()">Retry failed uploads</button>--}}
{{--                    </div>--}}
            </div>
        </div>
        <div class="flex flex-col text-center bg-gray-50 p-14 rounded-lg border-dashed border-2 border-gray-300" x-show="images.length <= 0" x-cloak>
            <div class="w-full ">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-48 w-48 text-gray-200 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="text-gray-500 ">
                <span>There is no image(s) selected.</span>
            </div>
        </div>

        <div x-show="images.length > 0" x-cloak>
            <div class="bg-gray-50 w-full p-3 rounded-t-md  select-none" x-cloak x-show="images.length > size">
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
                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                        Status
                    </th>
{{--                    <th scope="col"--}}
{{--                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">--}}
{{--                        Action--}}
{{--                    </th>--}}
                </tr>
                </thead>
                <tbody>
                    <template x-for="(image, i) in getData()">
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex h-10">
                                    <button @click="show_errors = errors[image.name];$openModal('showingErrors')" type="button" class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10" x-show="errors[image.name].length > 0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </button>
{{--                                    <button type="button" class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-pgreen-300/25 sm:mx-0 sm:h-10 sm:w-10" x-show="errors[image.name].length === 0 && progress[image.name] >= 100">--}}
{{--                                        <x-icon name="check" style="solid" class="w-6 h-6 text-pgreen-700" />--}}
{{--                                    </button>--}}
                                    <div class="self-center pl-3" x-text="image.name"></div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white" >

                                <div class="flex-1 w-64 self-center">
                                    <div class="w-full rounded-full bg-gray-200">
                                        <div class=" text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" :class="{'bg-gray-800' : progress[image.name] < 100  || !is_uploaded[image.name], 'bg-positive-500' : progress[image.name] >= 100 && is_uploaded[image.name], 'bg-red-500' : progress[image.name] >= 100 && upload_errors[image.name]}" :style="{width: progress[image.name]+'%'}"></div>
                                    </div>
                                </div>

                            </td>
{{--                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">--}}
{{--                                <button class="bg-red-600 p-2 text-white rounded-md shadow-md" @click="removeFile(i)">Stop/Delete</button>--}}
{{--                            </td>--}}
                        </tr>
                    </template>
                </tbody>
            </table>
            <div class="bg-gray-50 w-full p-3 rounded rounded-t-none select-none" x-cloak x-show="images.length > size">
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
                                Upload Errors
                            </h3>

                            <div class="mt-2">
                                <template x-for="error in show_errors">
                                    <span x-text="error"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right rounded-md rounded-t-none">
                    <x-button rose label="OK" x-on:click="close" />
                </div>
            </x-card>
        </x-modal>

    </div>

</x-image-analysis>

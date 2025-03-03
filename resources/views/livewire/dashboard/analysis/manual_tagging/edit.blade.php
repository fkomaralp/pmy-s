<x-image-analysis>

    <x-slot name="title">
        Edit Bib Number
    </x-slot>

    <script type="text/javascript" src="/frontend/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/blowup.min.js"></script>

    <script>
        $(function () {
            $(".blowup").blowup({
                "scale" : 4
            });
        })
        function component() {
            return {
                bib_numbers: {!! $bib_numbers !!},
                downloadRealImage: () => {
                    window.$wireui.notify({
                        title: 'Please wait...',
                        description: 'Requested image is preparing from Google Storage.',
                        icon: 'info'
                    })

                    @this.downloadRealImage()
                },
                deleteBibNumber: (file_name, bib_number) => {
                    window.$wireui.confirmDialog({
                        title: 'Are you Sure?',
                        description: ' These bib number(s) ('+bib_number+') will be deleted after you are confirm.',
                        icon: 'question',
                        accept: {
                            label: 'Yes, delete it',
                            execute: () => {
                            @this.deleteBibNumber(file_name, bib_number)
                            },
                            params: 'deleteBibNumber'
                        },
                        reject: {
                            label: 'No, cancel'
                        },
                    }, @this)
                }
            }
        }
    </script>

    <div class="flex justify-center" x-data="component()" >
        <div class="w-1/4 py-10 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center px-4 py-3 bg-gray-200 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                {{$photo->file_name}}
            </div>
            <div class="px-4 py-5 bg-white sm:p-6 ">
                <img class="rounded blowup" src="{{$photo->thumbnail}}">
            </div>
            <div class="flex items-center justify-center px-4 py-3 bg-gray-200 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <button type="button" @click="downloadRealImage()" class="inline-flex items-center px-4 py-2 bg-pgreen-800 hover:bg-pgreen-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-pgreen-900 focus:outline-none focus:border-pgreen-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">Download real image</button>
            </div>
        </div>
        <div class="w-2/4 py-10 sm:px-6 lg:px-8">
            <form wire:submit.prevent="updateBibNumber" class="w-full">
                <div class="px-4 py-5 bg-white sm:p-6 ">

                    <div class="col-span-6 sm:col-span-4 mt-3">
                        <x-jet-label for="bib_numbers" value="{{ __('Bib Numbers') }}"/>
                        <div class="col-span-6 sm:col-span-4 mt-3">

                            <div class="max-w-lg ">
                                <div class="relative">
                                    <div class="block grid grid-cols-2 gap-2">
                                        <div class="col-start-1 col-end-3">
                                            <input wire:model="new_bib_number" class="w-full bg-white text-gray-700 border border-gray-200 rounded-md py-2 px-4 h-10 leading-tight focus:outline-none focus:ring-1 ring-inset focus:ring-pgreen-800 focus:border-pgreen-800" placeholder="Enter tags">
                                        </div>
                                        <div class="col-end-7 col-span-2">
                                            <button type="button" class="rounded-md -ml-1.5 inline inline-flex items-center px-5 py-2 h-10 bg-pgreen-800 hover:bg-pgreen-900 border border-transparent font-semibold text-xs text-white uppercase tracking-widest active:bg-pgreen-900 focus:outline-none focus:border-pgreen-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" wire:click="AddNewBibNumber">Add New</button>
                                        </div>
                                    </div>

                                    @foreach($bib_numbers as $bib_number)
                                        <div class="bg-gray-100 inline-flex items-center text-sm rounded mt-2 mr-1 overflow-hidden" >
                                            <span class="ml-2 mr-1 leading-relaxed truncate max-w-xs px-1">{{$bib_number->bib_number}}</span>
                                            <button class="w-6 h-8 inline-block align-middle text-gray-500 bg-gray-200 focus:outline-none" type="button" @click="deleteBibNumber('{{$photo->file_name}}', {{$bib_number->bib_number}})">
                                                <x-icon name="x" class="w-5 h-5 fill-current mx-auto" />
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex items-center justify-end px-4 py-3 bg-gray-200 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                    <x-jet-secondary-button class="mr-3" wire:click="back()">
                        {{ __('Cancel') }}
                    </x-jet-secondary-button>
                    <x-jet-button>
                        {{ __('Update') }}
                    </x-jet-button>
                </div>
            </form>
        </div>
    </div>
</x-image-analysis>

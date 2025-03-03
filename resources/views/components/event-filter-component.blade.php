<div class="col-span-6 sm:col-span-4 mt-5">
    <x-jet-label value="{{ __('Bib number filter range') }}" class="mb-5"/>

    <button @click="addFilter" type="button" class=" bottom-0 h-11 items-center px-4 py-2 bg-pgreen-800 hover:bg-pgreen-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-pgreen-900 focus:outline-none focus:border-pgreen-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
        <x-icon name="plus" class="w-5 h-5 inline-flex" /> <span class="inline-flex">Add filter</span>
    </button>

    <template x-for="(filter, index) in filters">
        <div class="flex mb-5 mt-5" >
            <div class="flex-1 my-auto">
                <x-jet-label for="from" value="{{ __('From') }}" />
                <x-jet-input id="from" type="text" class="mt-1 block w-full" x-model.lazy="filter.from"
                             autofocus/>
            </div>
            <div class="flex-1 ml-4 my-auto">
                <x-jet-label for="to" value="{{ __('To') }}"/>
                <x-jet-input id="to" type="text" class="mt-1 block w-full" x-model.lazy="filter.to"
                             autofocus/>
            </div>
{{--            <div class="flex-none ml-4 w-12 self-end" x-show="index === 0">--}}
{{--                <button type="button" class="bottom-0 h-11 items-center px-4 py-2 bg-pgreen-800 hover:bg-pgreen-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-pgreen-900 focus:outline-none focus:border-pgreen-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">--}}
{{--                    <x-icon name="plus" class="w-5 h-5" />--}}
{{--                </button>--}}
{{--            </div>--}}
            <div class="flex-none ml-4 self-end">
                <button @click="removeFilter(index)" type="button" class=" bottom-0 h-11 items-center px-4 py-2 bg-red-600 hover:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-red-900 focus:outline-none focus:border-red-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                    <x-icon name="trash" class="w-5 h-5" />
                </button>
            </div>
        </div>
    </template>
</div>

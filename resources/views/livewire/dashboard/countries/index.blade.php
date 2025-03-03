<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="left-0 font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Countries') }}
            </h2>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col"
                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                        Flag
                    </th>
                    <th scope="col"
                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                        Name
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($countries as $country)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {!! $country->emoji !!}
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$country->name}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="bg-gray-50 w-full p-3 rounded rounded-t-none">
                {{ $countries->links() }}
            </div>

        </div>
    </div>
</div>

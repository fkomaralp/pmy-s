<div class="flex-none w-60 bg-white p-3 shadow-md">
        <div class="mt-3 mb-6 text-center">
            <a href="{{ route('dashboard.index') }}" >
                <x-jet-application-mark class="block h-9" />
            </a>
        </div>
        <div class="block w-full h-5"></div>
        <div class="">
            <a href="{{ route('dashboard.index') }}" class="flex items-center space-x-3 text-black p-2 rounded-md font-semibold hover:bg-gray-200 focus:shadow-outline @if(request()->routeIs('dashboard.index')) bg-gray-100 @endif">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                {{ __('Dashboard') }}
            </a>
        </div>
        <div class="mt-3">
            <span class="flex items-center text-gray-500 p-2 font-semibold focus:shadow-outline pb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{ __('Photos') }}
            </span>

            <div class="w-full space-y-2">
                <a href="{{ route('dashboard.analysis.upload_manager') }}" class=" flex items-center text-black p-2  font-semibold hover:bg-gray-200 rounded-md focus:shadow-outline @if(request()->routeIs('dashboard.analysis.index')) bg-gray-100 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    {{ __('Upload & Analysis') }}
                </a>
                <a href="{{ route('dashboard.analysis.settings.watermark.thumbnail.index') }}" class=" flex items-center text-black p-2  font-semibold hover:bg-gray-200 rounded-md focus:shadow-outline @if(request()->routeIs('dashboard.analysis.settings.watermark.*')) bg-gray-100 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    {{ __('Watermark') }}
                </a>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('dashboard.events.index') }}" class="flex items-center space-x-3 text-black p-2 rounded-md font-semibold hover:bg-gray-200 focus:shadow-outline @if(request()->routeIs('dashboard.events.*')) bg-gray-100 @endif">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                {{ __('Events') }}
            </a>
        </div>
        <div class="mt-3">
            <a href="{{ route('dashboard.orders.index') }}" class="flex items-center space-x-3 text-black p-2 rounded-md font-semibold hover:bg-gray-200 focus:shadow-outline @if(request()->routeIs('dashboard.orders.*')) bg-gray-100 @endif">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                {{ __('Orders') }}
            </a>
        </div>
        <div class="mt-3">
            <a href="{{ route('dashboard.priceses.index') }}" class="flex items-center space-x-3 text-black p-2 rounded-md font-semibold hover:bg-gray-200 focus:shadow-outline @if(request()->routeIs('dashboard.priceses.*')) bg-gray-100 @endif">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                {{ __('Prices') }}
            </a>
        </div>
{{--        <div class="mt-3">--}}
{{--            <span class=" flex items-center text-gray-500 p-2 font-semibold focus:shadow-outline pb-4">--}}
{{--                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">--}}
{{--                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />--}}
{{--                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />--}}
{{--                </svg>--}}
{{--                {{ __('Locations') }}--}}
{{--                <hr>--}}
{{--            </span>--}}

{{--            <div class="w-full space-y-2">--}}
{{--                <a href="{{ route('dashboard.locations.countries.index') }}" class="flex items-center text-black p-2 font-semibold hover:bg-gray-200 rounded-md focus:shadow-outline @if(request()->routeIs('dashboard.locations.countries.*')) bg-gray-100 @endif">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />--}}
{{--                    </svg>--}}
{{--                    {{ __('Countries') }}--}}
{{--                </a>--}}
{{--                <a href="{{ route('dashboard.locations.cities.index') }}" class="flex items-center text-black p-2 font-semibold hover:bg-gray-200 rounded-md focus:shadow-outline @if(request()->routeIs('dashboard.locations.cities.*')) bg-gray-100 @endif">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />--}}
{{--                    </svg>--}}
{{--                    {{ __('Cities') }}--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="mt-3">
            <span class="flex items-center text-gray-500 p-2 font-semibold focus:shadow-outline pb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                {{ __('Pages') }}
            </span>

            <div class="w-full space-y-2">
                <a href="{{ route('dashboard.pages.faq.index') }}" class=" flex items-center text-black p-2  font-semibold hover:bg-gray-200 rounded-md focus:shadow-outline @if(request()->routeIs('dashboard.pages.faq.*')) bg-gray-100 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    {{ __('Faq') }}
                </a>
            </div>
        </div>

        <div class="mt-3 mb-5">
            <span class="flex items-center text-gray-500 p-2 font-semibold focus:shadow-outline pb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('Settings') }}
            </span>

            <div class="w-full space-y-2">
                <a href="{{ route('dashboard.settings.index') }}" class=" flex items-center text-black p-2 font-semibold hover:bg-gray-200 rounded-md focus:shadow-outline @if(request()->routeIs('dashboard.settings.index')) bg-gray-100 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    {{ __('General') }}
                </a>
                <a href="{{ route('dashboard.settings.social_media.index') }}" class=" flex items-center text-black p-2 font-semibold hover:bg-gray-200 rounded-md focus:shadow-outline @if(request()->routeIs('dashboard.settings.social_media.*')) bg-gray-100 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    {{ __('Social Media') }}
                </a>
                <a href="{{ route('dashboard.settings.mail.index') }}" class=" flex items-center text-black p-2 font-semibold hover:bg-gray-200 rounded-md focus:shadow-outline @if(request()->routeIs('dashboard.settings.mail.*')) bg-gray-100 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:text-gray-500 text-pgreen-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    {{ __('Mail') }}
                </a>
            </div>
        </div>

{{--            <div class="hidden sm:flex sm:items-center sm:ml-6">--}}

{{--                <a href="{{ route('frontend.index') }}" target="_blank" class="inline-flex items-center px-1 pt-1 border-0 text-sm font-medium leading-5 text-gray-500 focus:outline-none focus:border-indigo-700 transition">--}}
{{--                    {{ __('Show Website') }}--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" class=" ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">--}}
{{--                        <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />--}}
{{--                        <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />--}}
{{--                    </svg>--}}
{{--                </a>--}}

{{--                <!-- Teams Dropdown -->--}}
{{--                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())--}}
{{--                    <div class="ml-3 relative">--}}
{{--                        <x-jet-dropdown align="right" width="60">--}}
{{--                            <x-slot name="trigger">--}}
{{--                                <span class="inline-flex rounded-md">--}}
{{--                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">--}}
{{--                                        {{ Auth::user()->currentTeam->name }}--}}

{{--                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">--}}
{{--                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />--}}
{{--                                        </svg>--}}
{{--                                    </button>--}}
{{--                                </span>--}}
{{--                            </x-slot>--}}

{{--                            <x-slot name="content">--}}
{{--                                <div class="w-60">--}}
{{--                                    <!-- Team Management -->--}}
{{--                                    <div class="block px-4 py-2 text-xs text-gray-400">--}}
{{--                                        {{ __('Manage Team') }}--}}
{{--                                    </div>--}}

{{--                                    <!-- Team Settings -->--}}
{{--                                    <x-jet-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">--}}
{{--                                        {{ __('Team Settings') }}--}}
{{--                                    </x-jet-dropdown-link>--}}

{{--                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())--}}
{{--                                        <x-jet-dropdown-link href="{{ route('teams.create') }}">--}}
{{--                                            {{ __('Create New Team') }}--}}
{{--                                        </x-jet-dropdown-link>--}}
{{--                                    @endcan--}}

{{--                                    <div class="border-t border-gray-100"></div>--}}

{{--                                    <!-- Team Switcher -->--}}
{{--                                    <div class="block px-4 py-2 text-xs text-gray-400">--}}
{{--                                        {{ __('Switch Teams') }}--}}
{{--                                    </div>--}}

{{--                                    @foreach (Auth::user()->allTeams() as $team)--}}
{{--                                        <x-jet-switchable-team :team="$team" />--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                            </x-slot>--}}
{{--                        </x-jet-dropdown>--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--                <!-- Settings Dropdown -->--}}
{{--                <div class="ml-3 relative">--}}
{{--                    <x-jet-dropdown align="right" width="48">--}}
{{--                        <x-slot name="trigger">--}}
{{--                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())--}}
{{--                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">--}}
{{--                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />--}}
{{--                                </button>--}}
{{--                            @else--}}
{{--                                <span class="inline-flex rounded-md">--}}
{{--                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">--}}
{{--                                        {{ Auth::user()->name }}--}}

{{--                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">--}}
{{--                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />--}}
{{--                                        </svg>--}}
{{--                                    </button>--}}
{{--                                </span>--}}
{{--                            @endif--}}
{{--                        </x-slot>--}}

{{--                        <x-slot name="content">--}}
{{--                            <!-- Account Management -->--}}
{{--                            <div class="block px-4 py-2 text-xs text-gray-400">--}}
{{--                                {{ __('Manage Account') }}--}}
{{--                            </div>--}}

{{--                            <x-jet-dropdown-link href="{{ route('profile.show') }}">--}}
{{--                                {{ __('Profile') }}--}}
{{--                            </x-jet-dropdown-link>--}}

{{--                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())--}}
{{--                                <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">--}}
{{--                                    {{ __('API Tokens') }}--}}
{{--                                </x-jet-dropdown-link>--}}
{{--                            @endif--}}

{{--                            <div class="border-t border-gray-100"></div>--}}

{{--                            <!-- Authentication -->--}}
{{--                            <form method="POST" action="{{ route('logout') }}">--}}
{{--                                @csrf--}}

{{--                                <x-jet-dropdown-link href="{{ route('logout') }}"--}}
{{--                                         onclick="event.preventDefault();--}}
{{--                                                this.closest('form').submit();">--}}
{{--                                    {{ __('Log Out') }}--}}
{{--                                </x-jet-dropdown-link>--}}
{{--                            </form>--}}
{{--                        </x-slot>--}}
{{--                    </x-jet-dropdown>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <!-- Hamburger -->--}}
{{--            <div class="-mr-2 flex items-center sm:hidden">--}}
{{--                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">--}}
{{--                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">--}}
{{--                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />--}}
{{--                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />--}}
{{--                    </svg>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Responsive Navigation Menu -->--}}
{{--    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">--}}
{{--        <div class="pt-2 pb-3 space-y-1">--}}
{{--            <x-jet-responsive-nav-link href="{{ route('dashboard.index') }}" :active="request()->routeIs('dashboard')">--}}
{{--                {{ __('Dashboard') }}--}}
{{--            </x-jet-responsive-nav-link>--}}
{{--        </div>--}}

{{--        <!-- Responsive Settings Options -->--}}
{{--        <div class="pt-4 pb-1 border-t border-gray-200">--}}
{{--            <div class="flex items-center px-4">--}}
{{--                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())--}}
{{--                    <div class="shrink-0 mr-3">--}}
{{--                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--                <div>--}}
{{--                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>--}}
{{--                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="mt-3 space-y-1">--}}
{{--                <!-- Account Management -->--}}
{{--                <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">--}}
{{--                    {{ __('Profile') }}--}}
{{--                </x-jet-responsive-nav-link>--}}

{{--                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())--}}
{{--                    <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">--}}
{{--                        {{ __('API Tokens') }}--}}
{{--                    </x-jet-responsive-nav-link>--}}
{{--                @endif--}}

{{--                <!-- Authentication -->--}}
{{--                <form method="POST" action="{{ route('logout') }}">--}}
{{--                    @csrf--}}

{{--                    <x-jet-responsive-nav-link href="{{ route('logout') }}"--}}
{{--                                   onclick="event.preventDefault();--}}
{{--                                    this.closest('form').submit();">--}}
{{--                        {{ __('Log Out') }}--}}
{{--                    </x-jet-responsive-nav-link>--}}
{{--                </form>--}}

{{--                <!-- Team Management -->--}}
{{--                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())--}}
{{--                    <div class="border-t border-gray-200"></div>--}}

{{--                    <div class="block px-4 py-2 text-xs text-gray-400">--}}
{{--                        {{ __('Manage Team') }}--}}
{{--                    </div>--}}

{{--                    <!-- Team Settings -->--}}
{{--                    <x-jet-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">--}}
{{--                        {{ __('Team Settings') }}--}}
{{--                    </x-jet-responsive-nav-link>--}}

{{--                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())--}}
{{--                        <x-jet-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">--}}
{{--                            {{ __('Create New Team') }}--}}
{{--                        </x-jet-responsive-nav-link>--}}
{{--                    @endcan--}}

{{--                    <div class="border-t border-gray-200"></div>--}}

{{--                    <!-- Team Switcher -->--}}
{{--                    <div class="block px-4 py-2 text-xs text-gray-400">--}}
{{--                        {{ __('Switch Teams') }}--}}
{{--                    </div>--}}

{{--                    @foreach (Auth::user()->allTeams() as $team)--}}
{{--                        <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link" />--}}
{{--                    @endforeach--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    </div>

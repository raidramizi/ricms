@auth

@php
    $role = auth()->user()->role;
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">

    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
        <div class="flex justify-between h-28">

            <!-- LEFT -->
            <div class="flex items-center">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-logo class="block h-12 w-auto text-gray-800" />
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden sm:flex sm:items-center sm:space-x-14 sm:ms-12">

                    <x-nav-link class="text-base px-3 py-2"
                        :href="route('home')"
                        :active="request()->routeIs('home')">
                        Home
                    </x-nav-link>

                    <x-nav-link class="text-base px-3 py-2"
                        :href="route('handbook')" target="_blank">
                        CoRI Guide
                    </x-nav-link>

                    {{-- HEAD ROLE --}}
                   @if($role === 'Head')

    {{-- DOCUMENT REVIEW DASHBOARD --}}
         <x-nav-link class="text-base px-3 py-2"
          :href="route('head.dashboardreview')"
          :active="request()->routeIs('head.dashboardreview')">
           Head Dashboard
           </x-nav-link>
             <x-nav-link class="text-base px-3 py-2"
        :href="route('head.users.index')"
        :active="request()->routeIs('head.users.*')">
        Users Management
    </x-nav-link>
               <x-nav-link class="text-base px-3 py-2"
                 :href="route('admin.submissions.history')"
                 :active="request()->routeIs('admin.submissions.history*')">
                  History
                 </x-nav-link>


                    {{-- STAFF ROLE --}}
                    @elseif($role === 'R&I Staff')

<x-nav-link class="text-base px-3 py-2"
:href="route('admin.submissions.index')"
 :active="request()->routeIs('admin.submissions.index*')">
  Admin Dashboard
 </x-nav-link>

<x-nav-link class="text-base px-3 py-2"
    :href="route('admin.submissions.recommended')"
    :active="request()->routeIs('admin.submissions.recommended*')">
    Recommended Applications
</x-nav-link>


<x-nav-link class="text-base px-3 py-2"
    :href="route('admin.forms.index')"
    :active="request()->routeIs('admin.forms.index*')">
    Forms Management
</x-nav-link>
<x-nav-link class="text-base px-3 py-2"
    :href="route('admin.submissions.history')"
    :active="request()->routeIs('admin.submissions.history*')">
    History
</x-nav-link>


                    {{-- NORMAL USER --}}
                    @else

                        {{-- ✅ FIXED CLAIM (covers ALL claim modules) --}}
                        <x-nav-link class="text-base px-3 py-2"
                            :href="route('claim')"
                            :active="
                                request()->routeIs('claim')
                                || request()->routeIs('publication.*')
                                || request()->routeIs('grant.*')
                            ">
                            Claim
                        </x-nav-link>

                        <x-nav-link class="text-base px-3 py-2"
                            :href="route('conference')"
                            :active="request()->routeIs('conference*')">
                            Conference Grant
                        </x-nav-link>

                        <x-nav-link class="text-base px-3 py-2"
                            :href="route('claim.status')"
                            :active="request()->routeIs('claim.status')">
                            My Application
                        </x-nav-link>

                    @endif

                </div>
            </div>

           <!-- RIGHT USER -->
<div class="hidden sm:flex sm:items-center sm:ms-8">

@php
    $user = auth()->user();
    $role = $user->role;

    $unreadCount = ($role === 'Academician')
        ? $user->unreadNotifications()->count()
        : 0;
@endphp

<div class="flex items-center gap-5">


@if($role === 'Academician')
    <a href="{{ route('notifications.index') }}" class="flex items-center gap-1 group">
        {{-- BELL ICON --}}
        <svg class="w-6 h-6 text-gray-600 group-hover:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
        </svg>

        {{-- UNREAD COUNT --}}
        @if($unreadCount > 0)
            <span class="text-red-500 font-bold text-sm">
                ({{ $unreadCount }})
            </span>
        @endif
    </a>
@endif

    {{-- ================= USER DROPDOWN ONLY ================= --}}
    <x-dropdown align="right" width="48">

        <x-slot name="trigger">
            <button class="inline-flex items-center gap-2 text-base text-gray-600 hover:text-gray-800">

                {{-- USER NAME --}}
                <div>{{ $user->name }}</div>

                {{-- ARROW --}}
                <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                </svg>

            </button>
        </x-slot>

        <x-slot name="content">

            <x-dropdown-link :href="route('profile.edit')">
                Profile
            </x-dropdown-link>

            {{-- OPTIONAL: still keep notifications page --}}
            @if($role === 'Academician')
                <x-dropdown-link :href="route('notifications.index')">
                    Notifications
                    @if($unreadCount > 0)
                        <span class="text-red-500 font-bold">
                            ({{ $unreadCount }})
                        </span>
                    @endif
                </x-dropdown-link>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    Log Out
                </x-dropdown-link>
            </form>

        </x-slot>

    </x-dropdown>

</div>

</div>

            <!-- MOBILE BUTTON -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open" class="p-3 text-gray-500 hover:bg-gray-100 rounded-md">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor">
                        <path :class="{ 'hidden': open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />

                        <path :class="{ 'hidden': !open }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- MOBILE MENU -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">

        <div class="pt-3 pb-4 space-y-2">

            <x-responsive-nav-link
                :href="route('home')"
                :active="request()->routeIs('home')">
                Home
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('handbook')">
                CoRI Guide
            </x-responsive-nav-link>

            @if($role === 'Head')

                <x-responsive-nav-link
                    :href="route('head.dashboardreview')"
                    :active="request()->routeIs('head.dashboardreview*')">
                    Head Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link
        :href="route('head.users.index')"
        :active="request()->routeIs('head.users.*')">
        Users Management
    </x-responsive-nav-link>
                <x-responsive-nav-link
                :href="route('admin.submissions.history')"
                :active="request()->routeIs('admin.submissions.history*')">
                History
               </x-responsive-nav-link>



            @elseif($role === 'R&I Staff')

   <x-responsive-nav-link
    :href="route('admin.submissions.index')"
    :active="request()->routeIs('admin.submissions.index*')">
    Admin Dashboard
</x-responsive-nav-link>

<x-responsive-nav-link
    :href="route('admin.submissions.recommended')"
    :active="request()->routeIs('admin.submissions.recommended*')">
    Recommended Application
</x-responsive-nav-link>

<x-responsive-nav-link
    :href="route('admin.forms.index')"
    :active="request()->routeIs('admin.forms*')">
    Forms Management
</x-responsive-nav-link>
<x-responsive-nav-link
    :href="route('admin.submissions.history')"
    :active="request()->routeIs('admin.submissions.history*')">
    History
</x-responsive-nav-link>



@else

                {{-- ✅ FIXED MOBILE CLAIM --}}
                <x-responsive-nav-link
                    :href="route('claim')"
                    :active="
                        request()->routeIs('claim')
                        || request()->routeIs('publication.*')
                        || request()->routeIs('grant.*')
                    ">
                    Claim
                </x-responsive-nav-link>

                <x-responsive-nav-link
                    :href="route('conference')"
                    :active="request()->routeIs('conference*')">
                    Conference Grant
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('claim.status')">
                    My Application
                </x-responsive-nav-link>

            @endif

        </div>

        <!-- MOBILE USER -->
        <div class="pt-4 pb-2 border-t">

            <div class="px-4">
                <div class="text-gray-900 font-medium">{{ auth()->user()->name }}</div>
                <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-2">

                <x-responsive-nav-link :href="route('profile.edit')">
                    Profile
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>

            </div>

        </div>

    </div>

</nav>

@endauth

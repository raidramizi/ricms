<x-app-layout>
    <x-slot name="title">
    Profile
</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- ================= LEFT CARD ================= -->
                <div class="lg:col-span-1">

                    <div class="bg-white shadow-lg rounded-2xl p-6 text-center">


                        <div class="w-20 h-20 mx-auto rounded-full overflow-hidden border-4 border-indigo-500 shadow-md">

                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}"
                                     class="w-full h-full object-cover object-center scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-indigo-500 text-white text-2xl font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                        </div>

                        <!-- NAME -->
                        <h2 class="mt-4 text-xl font-bold text-gray-800">
                            {{ $user->name }}
                        </h2>

                        <!-- STAFF ID -->
                        <p class="text-indigo-600 font-semibold mt-1">
                            Staff ID: {{ $user->staff_id }}
                        </p>

                        <!-- ROLE -->
                        <p class="text-gray-500 text-sm">
                            Role: {{ $user->role }}
                        </p>

                        <!-- EMAIL -->
                        <p class="text-gray-400 text-sm">
                            {{ $user->email }}
                        </p>

                    </div>
                </div>

                <!-- ================= RIGHT SIDE ================= -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- PROFILE INFO -->
                    <div class="bg-white shadow-lg rounded-2xl p-6">

                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            Profile Information
                        </h3>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- NAME -->
                            <div>
                                <x-input-label for="name" value="Name" />
                                <x-text-input id="name" name="name" type="text"
                                    class="mt-1 block w-full"
                                    value="{{ old('name', $user->name) }}" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- EMAIL -->
                           <div class="mt-4">
                          <x-input-label for="email" value="Email" />
                          <x-text-input
                            id="email" name="email" type="email"  class="mt-1 block w-full bg-gray-100"
                          value="{{ old('email', $user->email) }}"  readonly/>

                       <x-input-error :messages="$errors->get('email')" class="mt-2" />
                         <p class="text-xs text-gray-500 mt-1">
                                    Email cannot be changed.
                        </p>
                      </div>

                            <div class="mt-4">
                                <x-input-label value="Staff ID" />
                                <x-text-input
                                    type="text"
                                    class="mt-1 block w-full bg-gray-100"
                                    value="{{ $user->staff_id }}"
                                    readonly
                                />
                                <p class="text-xs text-gray-500 mt-1">
                                    Staff ID cannot be changed.
                                </p>
                            </div>

                            <!-- PHOTO UPLOAD -->
                            <div class="mt-4">
                                <x-input-label for="photo" value="Profile Photo" />
                                <input type="file" name="photo" class="mt-1 block w-full">
                                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                            </div>

                            <!-- BUTTON -->
                            <div class="mt-6">
                                <x-primary-button>
                                    Save Changes
                                </x-primary-button>
                            </div>

                        </form>
                    </div>

                    <!-- PASSWORD -->
                    <div class="bg-white shadow-lg rounded-2xl p-6">

                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            Update Password
                        </h3>

                        @include('profile.partials.update-password-form')

                    </div>

                    <!-- DELETE ACCOUNT -->
                    <div class="bg-white shadow-lg rounded-2xl p-6 border border-red-200">

                        <h3 class="text-lg font-semibold text-red-600 mb-4">
                            Danger Zone
                        </h3>

                        @include('profile.partials.delete-user-form')

                    </div>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>

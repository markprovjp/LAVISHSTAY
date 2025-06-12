<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Users</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <!-- Filter button -->
                <x-dropdown-filter align="right" />

                <!-- Datepicker built with flatpickr -->
                <x-datepicker />

                <!-- Add view button -->
                <button
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Add View</span>
                </button>

            </div>

        </div>

        <!-- Cards -->
        <div class="grid grid-cols-12 gap-6">

                {{-- <form>
                        <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base/7 font-semibold text-gray-900">Profile</h2>
                                <p class="mt-1 text-sm/6 text-gray-600">This information will be displayed publicly so be
                                careful what you share.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-4">
                                        <label for="username" class="block text-sm/6 font-medium text-gray-900">Username</label>
                                        <div class="mt-2">
                                        <div
                                                class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                                <div class="shrink-0 text-base text-gray-500 select-none sm:text-sm/6">
                                                workcation.com/</div>
                                                <input type="text" name="username" id="username"
                                                class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6"
                                                placeholder="janesmith">
                                        </div>
                                        </div>
                                </div>

                                <div class="col-span-full">
                                        <label for="about" class="block text-sm/6 font-medium text-gray-900">About</label>
                                        <div class="mt-2">
                                        <textarea name="about" id="about" rows="3"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                                        </div>
                                        <p class="mt-3 text-sm/6 text-gray-600">Write a few sentences about yourself.</p>
                                </div>

                                <div class="col-span-full">
                                        <label for="photo" class="block text-sm/6 font-medium text-gray-900">Photo</label>
                                        <div class="mt-2 flex items-center gap-x-3">
                                        <svg class="size-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor"
                                                aria-hidden="true" data-slot="icon">
                                                <path fill-rule="evenodd"
                                                d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <button type="button"
                                                class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50">Change</button>
                                        </div>
                                </div>

                                <div class="col-span-full">
                                        <label for="cover-photo" class="block text-sm/6 font-medium text-gray-900">Cover
                                        photo</label>
                                        <div
                                        class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                                        <div class="text-center">
                                                <svg class="mx-auto size-12 text-gray-300" viewBox="0 0 24 24"
                                                fill="currentColor" aria-hidden="true" data-slot="icon">
                                                <path fill-rule="evenodd"
                                                        d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="mt-4 flex text-sm/6 text-gray-600">
                                                <label for="file-upload"
                                                        class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 focus-within:outline-hidden hover:text-indigo-500">
                                                        <span>Upload a file</span>
                                                        <input id="file-upload" name="file-upload" type="file"
                                                        class="sr-only">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs/5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                                        </div>
                                        </div>
                                </div>
                                </div>
                        </div>

                        <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base/7 font-semibold text-gray-900">Personal Information</h2>
                                <p class="mt-1 text-sm/6 text-gray-600">Use a permanent address where you can receive mail.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                        <label for="first-name" class="block text-sm/6 font-medium text-gray-900">First
                                        name</label>
                                        <div class="mt-2">
                                        <input type="text" name="first-name" id="first-name" autocomplete="given-name"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                        </div>
                                </div>

                                <div class="sm:col-span-3">
                                        <label for="last-name" class="block text-sm/6 font-medium text-gray-900">Last
                                        name</label>
                                        <div class="mt-2">
                                        <input type="text" name="last-name" id="last-name" autocomplete="family-name"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                        </div>
                                </div>

                                <div class="sm:col-span-4">
                                        <label for="email" class="block text-sm/6 font-medium text-gray-900">Email
                                        address</label>
                                        <div class="mt-2">
                                        <input id="email" name="email" type="email" autocomplete="email"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                        </div>
                                </div>

                                <div class="sm:col-span-3">
                                        <label for="country"
                                        class="block text-sm/6 font-medium text-gray-900">Country</label>
                                        <div class="mt-2 grid grid-cols-1">
                                        <select id="country" name="country" autocomplete="country-name"
                                                class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                <option>United States</option>
                                                <option>Canada</option>
                                                <option>Mexico</option>
                                        </select>
                                        <svg class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                                                viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                                                <path fill-rule="evenodd"
                                                d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        </div>
                                </div>

                                <div class="col-span-full">
                                        <label for="street-address" class="block text-sm/6 font-medium text-gray-900">Street
                                        address</label>
                                        <div class="mt-2">
                                        <input type="text" name="street-address" id="street-address"
                                                autocomplete="street-address"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                        </div>
                                </div>

                                <div class="sm:col-span-2 sm:col-start-1">
                                        <label for="city" class="block text-sm/6 font-medium text-gray-900">City</label>
                                        <div class="mt-2">
                                        <input type="text" name="city" id="city"
                                                autocomplete="address-level2"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                        </div>
                                </div>

                                <div class="sm:col-span-2">
                                        <label for="region" class="block text-sm/6 font-medium text-gray-900">State /
                                        Province</label>
                                        <div class="mt-2">
                                        <input type="text" name="region" id="region"
                                                autocomplete="address-level1"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                        </div>
                                </div>

                                <div class="sm:col-span-2">
                                        <label for="postal-code" class="block text-sm/6 font-medium text-gray-900">ZIP /
                                        Postal code</label>
                                        <div class="mt-2">
                                        <input type="text" name="postal-code" id="postal-code"
                                                autocomplete="postal-code"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                        </div>
                                </div>
                                </div>
                        </div>

                        <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base/7 font-semibold text-gray-900">Notifications</h2>
                                <p class="mt-1 text-sm/6 text-gray-600">We'll always let you know about important changes, but
                                you pick what else you want to hear about.</p>

                                <div class="mt-10 space-y-10">
                                <fieldset>
                                        <legend class="text-sm/6 font-semibold text-gray-900">By email</legend>
                                        <div class="mt-6 space-y-6">
                                        <div class="flex gap-3">
                                                <div class="flex h-6 shrink-0 items-center">
                                                <div class="group grid size-4 grid-cols-1">
                                                        <input id="comments" aria-describedby="comments-description"
                                                        name="comments" type="checkbox" checked
                                                        class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto">
                                                        <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                                                        viewBox="0 0 14 14" fill="none">
                                                        <path class="opacity-0 group-has-checked:opacity-100"
                                                                d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        <path class="opacity-0 group-has-indeterminate:opacity-100"
                                                                d="M3 7H11" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                </div>
                                                </div>
                                                <div class="text-sm/6">
                                                <label for="comments" class="font-medium text-gray-900">Comments</label>
                                                <p id="comments-description" class="text-gray-500">Get notified when
                                                        someones posts a comment on a posting.</p>
                                                </div>
                                        </div>
                                        <div class="flex gap-3">
                                                <div class="flex h-6 shrink-0 items-center">
                                                <div class="group grid size-4 grid-cols-1">
                                                        <input id="candidates" aria-describedby="candidates-description"
                                                        name="candidates" type="checkbox"
                                                        class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto">
                                                        <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                                                        viewBox="0 0 14 14" fill="none">
                                                        <path class="opacity-0 group-has-checked:opacity-100"
                                                                d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        <path class="opacity-0 group-has-indeterminate:opacity-100"
                                                                d="M3 7H11" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                </div>
                                                </div>
                                                <div class="text-sm/6">
                                                <label for="candidates"
                                                        class="font-medium text-gray-900">Candidates</label>
                                                <p id="candidates-description" class="text-gray-500">Get notified when a
                                                        candidate applies for a job.</p>
                                                </div>
                                        </div>
                                        <div class="flex gap-3">
                                                <div class="flex h-6 shrink-0 items-center">
                                                <div class="group grid size-4 grid-cols-1">
                                                        <input id="offers" aria-describedby="offers-description"
                                                        name="offers" type="checkbox"
                                                        class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto">
                                                        <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                                                        viewBox="0 0 14 14" fill="none">
                                                        <path class="opacity-0 group-has-checked:opacity-100"
                                                                d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        <path class="opacity-0 group-has-indeterminate:opacity-100"
                                                                d="M3 7H11" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                </div>
                                                </div>
                                                <div class="text-sm/6">
                                                <label for="offers" class="font-medium text-gray-900">Offers</label>
                                                <p id="offers-description" class="text-gray-500">Get notified when a
                                                        candidate accepts or rejects an offer.</p>
                                                </div>
                                        </div>
                                        </div>
                                </fieldset>

                                <fieldset>
                                        <legend class="text-sm/6 font-semibold text-gray-900">Push notifications</legend>
                                        <p class="mt-1 text-sm/6 text-gray-600">These are delivered via SMS to your mobile
                                        phone.</p>
                                        <div class="mt-6 space-y-6">
                                        <div class="flex items-center gap-x-3">
                                                <input id="push-everything" name="push-notifications" type="radio" checked
                                                class="relative size-4 appearance-none rounded-full border border-gray-300 bg-white before:absolute before:inset-1 before:rounded-full before:bg-white not-checked:before:hidden checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:before:bg-gray-400 forced-colors:appearance-auto forced-colors:before:hidden">
                                                <label for="push-everything"
                                                class="block text-sm/6 font-medium text-gray-900">Everything</label>
                                        </div>
                                        <div class="flex items-center gap-x-3">
                                                <input id="push-email" name="push-notifications" type="radio"
                                                class="relative size-4 appearance-none rounded-full border border-gray-300 bg-white before:absolute before:inset-1 before:rounded-full before:bg-white not-checked:before:hidden checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:before:bg-gray-400 forced-colors:appearance-auto forced-colors:before:hidden">
                                                <label for="push-email" class="block text-sm/6 font-medium text-gray-900">Same
                                                as email</label>
                                        </div>
                                        <div class="flex items-center gap-x-3">
                                                <input id="push-nothing" name="push-notifications" type="radio"
                                                class="relative size-4 appearance-none rounded-full border border-gray-300 bg-white before:absolute before:inset-1 before:rounded-full before:bg-white not-checked:before:hidden checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:before:bg-gray-400 forced-colors:appearance-auto forced-colors:before:hidden">
                                                <label for="push-nothing" class="block text-sm/6 font-medium text-gray-900">No
                                                push notifications</label>
                                        </div>
                                        </div>
                                </fieldset>
                                </div>
                        </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button type="button" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                        </div>
                </form> --}}



                <h1>Userrsss</h1>
        </div>

    </div>
</x-app-layout>
<style>
    .button-action {
        position: relative;
    }

    .menu-button-action {
        position: absolute;
        top: 0%;
        right: 130%;
        z-index: 50;
        width: 200px;
    }
</style>

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">User Management</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage all users in the system</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.users.create') }}">
                    <button class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="max-xs:sr-only">Add User</span>
                    </button>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Content -->
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @if ($users->count() > 0)
                    <div class="">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Avatar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Join Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($users as $user)
                                    <tr>
                                        <!-- Avatar -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($user->profile_photo_url)
                                                <img class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                                                     src="{{ $user->profile_photo_url }}"
                                                     alt="{{ $user->name }}">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center border-2 border-gray-200 dark:border-gray-600">
                                                    <span class="text-white font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Name -->
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            <div class="font-medium">{{ $user->name }}</div>
                                            @if ($user->address)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($user->address, 30) }}</div>
                                            @endif
                                        </td>

                                        <!-- Email -->
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            <div>{{ $user->email }}</div>
                                            <div class="text-xs mt-1">
                                                @if ($user->email_verified_at)
                                                    <span class="text-green-600 dark:text-green-400">Verified</span>
                                                @else
                                                    <span class="text-amber-600 dark:text-amber-400">Not verified</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Phone -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->phone ?: 'Not provided' }}
                                        </td>

                                        <!-- Role -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $roleConfig = [
                                                    'admin' => ['bg-red-100 text-red-800', 'Admin'],
                                                    'manager' => ['bg-blue-100 text-blue-800', 'Manager'],
                                                    'staff' => ['bg-green-100 text-green-800', 'Staff'],
                                                    'customer' => ['bg-gray-100 text-gray-800', 'Customer'],
                                                    'guest' => ['bg-purple-100 text-purple-800', 'Guest'],
                                                    'receptionist' => ['bg-yellow-100 text-yellow-800', 'Receptionist']
                                                ];
                                                $config = $roleConfig[$user->role] ?? ['bg-gray-100 text-gray-800', ucfirst($user->role)];
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config[0] }}">
                                                {{ $config[1] }}
                                            </span>
                                        </td>

                                        <!-- Join Date -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ optional($user->created_at)->format('d/m/Y H:i') ?? 'Unknown' }}
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                            <div class="relative inline-block text-left">
                                                <button type="button"
                                                    class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                                    onclick="toggleDropdown({{ $user->id }})"
                                                    id="dropdown-button-{{ $user->id }}">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                    </svg>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <div id="dropdown-menu-{{ $user->id }}"
                                                    class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                    <div class="py-1 z-500" role="menu">
                                                        <!-- View Details -->
                                                        {{-- <button
                                                            onclick="toggleDetails({{ $user->id }}); closeDropdown({{ $user->id }})"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                            role="menuitem">
                                                            View Details
                                                        </button> --}}

                                                        <!-- Edit -->
                                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                            role="menuitem">
                                                            View User
                                                        </a>
                                                        <!-- Edit -->
                                                        {{-- <a href="{{ route('admin.users.edit', $user->id) }}"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                            role="menuitem">
                                                            Edit User
                                                        </a> --}}

                                                        <!-- Divider -->
                                                        <div class="border-t border-gray-100 dark:border-gray-700"></div>

                                                        <!-- Delete -->
                                                        <button
                                                            onclick="deleteUser({{ $user->id }}); closeDropdown({{ $user->id }})"
                                                            class="flex mt-2 items-center w-full px-4 py-2 cursor-pointer text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                                            role="menuitem">
                                                            <svg style="width: 20px; align-items: center" class="mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Delete User
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Expandable Details Row -->
                                    <tr id="details-{{ $user->id }}" class="hidden bg-gray-50 dark:bg-gray-700/50">
                                        <td colspan="9" class="px-5 py-4">
                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                                <!-- Left Column -->
                                                <div class="space-y-4">
                                                    <!-- User Info -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">User Information</h4>
                                                        <div class="space-y-2">
                                                            <div>
                                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Full Name:</span>
                                                                <p class="text-sm text-green-600 dark:text-green-400">{{ $user->name }}</p>
                                                            </div>
                                                            @if ($user->address)
                                                                <div>
                                                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Address:</span>
                                                                    <p class="text-sm text-green-600 dark:text-green-400">{{ $user->address }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Contact Info -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Contact Information</h4>
                                                        <div class="space-y-2">
                                                            <div>
                                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                                                <div class="flex flex-wrap gap-1 mt-1">
                                                                    <span class="inline-flex items-center font-normal py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-400/30 text-blue-600 dark:text-blue-400">
                                                                        {{ $user->email }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @if ($user->phone)
                                                                <div>
                                                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Phone:</span>
                                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                                        <span class="inline-flex items-center font-normal py-1 rounded-full text-xs bg-green-100 dark:bg-green-400/30 text-green-600 dark:text-green-400">
                                                                            {{ $user->phone }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Right Column -->
                                                <div class="space-y-4">
                                                    <!-- Role & Status -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Role & Status</h4>
                                                        <div class="space-y-2">
                                                            <div>
                                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Role:</span>
                                                                <p class="text-sm text-purple-600 dark:text-purple-400">{{ ucfirst($user->role) }}</p>
                                                            </div>
                                                            <div>
                                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Email Status:</span>
                                                                <p class="text-sm {{ $user->email_verified_at ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                                    {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Timestamps -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Timestamps</h4>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                            <div>Created: {{ optional($user->created_at)->format('M d, Y H:i') ?? 'Unknown' }}</div>
                                                            <div>Updated: {{ optional($user->updated_at)->format('M d, Y H:i') ?? 'Unknown' }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($users->hasPages())
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                            <span class="text-4xl text-gray-400">👥</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No users found</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating the first user in the system.</p>
                        <a href="{{ route('admin.users.create') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-violet-600 hover:bg-violet-700 transition-colors duration-200">
                            Add New User
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Toggle user details
        // function toggleDetails(userId) {
        //     const detailsRow = document.getElementById(`details-${userId}`);
        //     const isHidden = detailsRow.classList.contains('hidden');

        //     // Close all other details first
        //     const allDetails = document.querySelectorAll('[id^="details-"]');
        //     allDetails.forEach(detail => {
        //         detail.classList.add('hidden');
        //     });

        //     // Toggle current details
        //     if (isHidden) {
        //         detailsRow.classList.remove('hidden');
        //         // Smooth scroll to the details
        //         setTimeout(() => {
        //             detailsRow.scrollIntoView({
        //                 behavior: 'smooth',
        //                 block: 'nearest'
        //             });
        //         }, 100);
        //     }
        // }

        // Toggle dropdown menu
        function toggleDropdown(userId) {
            const dropdown = document.getElementById(`dropdown-menu-${userId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            // Close all other dropdowns
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${userId}`) {
                    menu.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown
        function closeDropdown(userId) {
            const dropdown = document.getElementById(`dropdown-menu-${userId}`);
            dropdown.classList.add('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            const buttons = document.querySelectorAll('[id^="dropdown-button-"]');

            let clickedInsideDropdown = false;

            // Check if clicked inside any dropdown or button
            dropdowns.forEach(dropdown => {
                if (dropdown.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            buttons.forEach(button => {
                if (button.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            // If clicked outside, close all dropdowns
            if (!clickedInsideDropdown) {
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        // Delete user
        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone!')) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/destroy/${userId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Router Create') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        <div class="max-w-auto">
            <section>
                <header>
                    <h2 class="text-lg font-medium">
                        {{ __('Router Information') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Fill your router information') }}
                    </p>
                </header>

                <form method="post" action="{{ route('router.store') }}" class="mt-6 space-y-6">
                    @csrf

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 md:col-span-12 space-y-2">
                            <x-form.label for="session_name" :value="__('Session Name')" />
                            <x-form.input id="session_name" name="session_name" type="text" class="block w-full"
                                required autofocus />
                            <x-form.error :messages="$errors->get('session_name')" />
                        </div>

                        <div class="col-span-12 md:col-span-6 space-y-2">
                            <x-form.label for="ip_address" :value="__('URL Rest-API Mikrotik')" />
                            <x-form.input id="ip_address" name="ip_address" type="text" class="block w-full"
                                required />
                            <x-form.error :messages="$errors->get('ip_address')" />
                        </div>
                        <div class="col-span-12 md:col-span-6 space-y-2">
                            <x-form.label for="username_mikrotik" :value="__('Username')" />
                            <x-form.input id="username_mikrotik" name="username_mikrotik" type="text"
                                class="block w-full" required />
                            <x-form.error :messages="$errors->get('username_mikrotik')" />
                        </div>
                        <div class="col-span-12 md:col-span-6 space-y-2">
                            <x-form.label for="password" :value="__('Password')" />
                            <x-form.input id="password" name="password" type="password" class="block w-full"
                                required />
                            <x-form.error :messages="$errors->get('password')" />
                        </div>
                        <div class="col-span-12 md:col-span-6 space-y-2">
                            <x-form.label for="hotspot_name" :value="__('Hotspot Name')" />
                            <x-form.input id="hotspot_name" name="hotspot_name" type="text" class="block w-full"
                                required />
                            <x-form.error :messages="$errors->get('hotspot_name')" />
                        </div>
                        <div class="col-span-12 md:col-span-6 space-y-2">
                            <x-form.label for="dns_name" :value="__('DNS Name')" />
                            <x-form.input id="dns_name" name="dns_name" type="text" class="block w-full" required />
                            <x-form.error :messages="$errors->get('dns_name')" />
                        </div>
                        <div class="col-span-12 md:col-span-6 space-y-2">
                            <x-form.label for="live_report" :value="__('Live Report')" />
                            <x-form.select id="live_report" name="live_report" :options="['0' => 'Disable', '1' => 'Enable']" required />
                            <x-form.error :messages="$errors->get('live_report')" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-button>
                            {{ __('Save') }}
                        </x-button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>

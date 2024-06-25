<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Router') }}
            </h2>
            <a href="{{ route('router.create') }}" class="text-blue-600 hover:underline">
                {{ __('Create New Router') }}
            </a>
        </div>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        <div class="max-w-auto">
            <section>
                <header>
                    <h2 class="text-lg font-medium">
                        {{ __('Router List') }}
                    </h2>
                </header>
                <div class="grid grid-cols-12 gap-4 mt-4">
                    @foreach ($data as $index => $item)
                        <div class="col-span-12 md:col-span-2 space-y-2">
                            <x-router-card sessionName="{{ $item['session_name'] }}"
                                hotspotName="{{ $item['hotspot_name'] }}" index="{{ $index }}"
                                icon="heroicon-o-server" />
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</x-app-layout>

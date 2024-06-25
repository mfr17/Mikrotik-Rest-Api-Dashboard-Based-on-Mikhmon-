@php
    $sessionName = session('active_router')['session_name'] ?? 'Dashboard';
@endphp


<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">
    <div x-transition x-show="isSidebarOpen || isSidebarHovered"
        class="text-lg font-semibold text-center text-gray-800 mb-4">
        {{ $sessionName }}
    </div>

    <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.dropdown title="Hotspot" :active="Str::startsWith(request()->route()->uri(), 'hotspot')">
        <x-slot name="icon">
            <x-heroicon-o-wifi class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
        <x-sidebar.dropdown title="Users" :active="Str::startsWith(request()->route()->uri(), 'hotspot')">
            <x-slot name="icon">
                <x-heroicon-o-users class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
            {{-- href="{{ route('buttons.text') }}" :active="request()->routeIs('buttons.text')" --}}
            <x-sidebar.sublink title="User List" href="#" />
            <x-sidebar.sublink title="Add User" href="#" />
            <x-sidebar.sublink title="Generate" href="#" />
        </x-sidebar.dropdown>
        <x-sidebar.dropdown title="User Profile" :active="Str::startsWith(request()->route()->uri(), 'hotspot')">
            <x-slot name="icon">
                <x-heroicon-o-users class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
            {{-- href="{{ route('buttons.text') }}" :active="request()->routeIs('buttons.text')" --}}
            <x-sidebar.sublink title="User Profile List" href="#" />
            <x-sidebar.sublink title="Add User Profile" href="#" />
        </x-sidebar.dropdown>
        {{-- href="{{ route('buttons.text') }}" :active="request()->routeIs('buttons.text')" --}}
        <x-sidebar.sublink title="Hotspot Active" href="#" />
        <x-sidebar.sublink title="Host" href="#" />
        <x-sidebar.sublink title="IP Bindings" href="#" />
        <x-sidebar.sublink title="Cookies" href="#" />
    </x-sidebar.dropdown>
    <x-sidebar.dropdown title="PPP" :active="Str::startsWith(request()->route()->uri(), 'hotspot')">
        <x-slot name="icon">
            <x-heroicon-o-user-group class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
        <x-sidebar.dropdown title="User" :active="Str::startsWith(request()->route()->uri(), 'hotspot')">
            <x-slot name="icon">
                <x-heroicon-o-users class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
            {{-- href="{{ route('buttons.text') }}" :active="request()->routeIs('buttons.text')" --}}
            <x-sidebar.sublink title="User List" href="#" />
            <x-sidebar.sublink title="Add User" href="#" />
            <x-sidebar.sublink title="Generate" href="#" />
        </x-sidebar.dropdown>
        {{-- href="{{ route('buttons.text') }}" :active="request()->routeIs('buttons.text')" --}}
        <x-sidebar.sublink title="Hotspot Active" href="#" />
        <x-sidebar.sublink title="Host" href="#" />
        <x-sidebar.sublink title="IP Bindings" href="#" />
        <x-sidebar.sublink title="Cookies" href="#" />
    </x-sidebar.dropdown>

    <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        Settings
    </div>
    <x-sidebar.dropdown title="Routers" :active="Str::startsWith(request()->route()->uri(), 'hotspot')">
        <x-slot name="icon">
            <x-heroicon-o-server class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
        {{-- href="{{ route('buttons.text') }}" :active="request()->routeIs('buttons.text')" --}}
        <x-sidebar.sublink title="Router List" href="{{ route('router') }}" :active="request()->routeIs('router')" />
        <x-sidebar.sublink title="Add Router" href="{{ route('router.create') }}" :active="request()->routeIs('router.create')" />
    </x-sidebar.dropdown>

</x-perfect-scrollbar>

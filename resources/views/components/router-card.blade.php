<!-- resources/views/components/router-card.blade.php -->

@props(['sessionName', 'hotspotName', 'index', 'icon' => null])

@php
    // Array of possible background colors with their corresponding text colors
    $bgColors = [
        'bg-blue-100' => 'text-blue-900',
        'bg-green-100' => 'text-green-900',
        'bg-yellow-100' => 'text-yellow-900',
        'bg-pink-100' => 'text-pink-900',
        'bg-purple-100' => 'text-purple-900',
        'bg-indigo-100' => 'text-indigo-900',
        'bg-red-100' => 'text-red-900',
        'bg-orange-100' => 'text-orange-900',
    ];

    // Pick a random index from the array keys
    $randomIndex = array_rand($bgColors);
    $bgColorClass = $randomIndex;
    $textColorClass = $bgColors[$randomIndex];
@endphp

<div {{ $attributes->merge(['class' => 'shadow-md rounded-lg p-4 flex items-center ' . $bgColorClass]) }}>
    <div class="flex-1">
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <div class="flex items-center">
                    <h3 class="text-lg font-semibold mb-2 {{ $textColorClass }}">{{ $sessionName }}</h3>
                </div>
                <p class="text-sm text-gray-600 mb-2 {{ $textColorClass }}">{{ $hotspotName }}</p>
            </div>
            <div class="flex items-center justify-center">
                @if ($icon)
                    <x-dynamic-component :component="$icon" class="h-12 w-12 text-gray-600" />
                @endif
            </div>
        </div>

        <div class="flex space-x-2 mt-4">
            <form action="{{ route('router.use', $index) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-blue-600 hover:underline font-semibold">Open</button>
            </form>

            <a href="{{ route('router.edit', $index) }}" class="text-blue-600 hover:underline font-semibold">Edit</a>

            <form action="{{ route('router.delete', $index) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline font-semibold">Delete</button>
            </form>
        </div>
    </div>
</div>

@props(['type' => 'text', 'icon', 'title', 'desc', 'bgColor' => 'bg-white'])

<div {{ $attributes->merge(['class' => 'shadow-md rounded-lg p-4 flex items-center ' . $bgColor]) }}>
    <div class="flex-shrink-0 mr-4">
        @if ($icon)
            <x-dynamic-component :component="$icon" class="h-8 w-8 text-gray-600" />
        @endif
    </div>

    <div>
        @if ($type === 'stats')
            <div>
                <h3 class="text-lg font-semibold mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-600">{{ $desc }}</p>
            </div>
        @elseif ($type === 'text')
            <h3 class="text-lg font-semibold mb-2">{{ $title }}</h3>
            @if (is_array($desc))
                @foreach ($desc as $line)
                    <p class="text-sm text-gray-600">{{ $line }}</p>
                @endforeach
            @else
                <p class="text-sm text-gray-600">{{ $desc }}</p>
            @endif
        @endif
    </div>
</div>

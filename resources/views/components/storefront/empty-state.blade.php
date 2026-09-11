@props([
    'title' => 'No items available',
    'description' => 'We could not find any items matching your request right now.',
    'actionText' => null,
    'actionUrl' => null,
])

<div class="p-12 text-center bg-white rounded-3xl border border-slate-200/80 shadow-xs max-w-lg mx-auto my-8">
    <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-500 mx-auto flex items-center justify-center mb-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
    </div>
    <h3 class="text-xl font-bold text-slate-900 font-heading">{{ $title }}</h3>
    <p class="text-sm text-slate-500 mt-2 leading-relaxed">{{ $description }}</p>
    @if($actionText && $actionUrl)
        <div class="mt-6">
            <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-sm rounded-xl transition shadow-xs">
                {{ $actionText }}
            </a>
        </div>
    @endif
</div>

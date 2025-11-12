<div>
    <details class="group mb-2 rounded-lg border border-slate-200 bg-white shadow-sm">
        <summary class="cursor-pointer list-none px-4 py-3 flex justify-between items-center">
            <div class="flex justify-start gap-2 items-center">
                <span class="font-medium">{{ $title }}</span>
                @isset($badge)
                    <flux:badge color="primary">
                        {{ $badge }}
                    </flux:badge>
                @endisset
            </div>
            <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" viewBox="0 0 20 20"
                fill="none" stroke="currentColor">
                <path d="M6 8l4 4 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </summary>
        <div class="px-4 pb-4 text-slate-600">
            {{ $content }}
        </div>
    </details>
</div>
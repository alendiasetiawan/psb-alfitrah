<div
    x-data="{ toasts: [] }"
    x-on:toast.window="
        setTimeout(() => {
            toasts.push({ id: Date.now(), type: $event.detail.type, message: $event.detail.message });
        }, 500);
        setTimeout(() => toasts.shift(), 4000);
        $flux.modals().close();
    "
    class="fixed z-50 space-y-3 bottom-22 left-1/2 -translate-x-1/2 md:top-5 md:right-5 md:bottom-auto md:left-auto md:translate-x-0"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-8 opacity-0 md:translate-y-0 md:translate-x-20"
            x-transition:enter-end="translate-y-0 opacity-100 md:translate-x-0"
            x-transition:leave="transform ease-in duration-300 transition"
            x-transition:leave-start="opacity-100 translate-y-0 md:translate-x-0"
            x-transition:leave-end="opacity-0 translate-y-8 md:translate-y-0 md:translate-x-20"
            class="flex items-center w-72 max-w-sm p-3 bg-white rounded-lg shadow-lg border"
            :class="{
                'border-green-500/80 text-green-500': toast.type === 'success',
                'border-red-500/80 text-red-500': toast.type === 'error',
                'border-yellow-500/80 text-yellow-500': toast.type === 'warning',
            }"
        >
            <!-- Icon -->
            <div class="flex-shrink-0 mr-2">
                <template x-if="toast.type === 'success'">
                    <flux:icon.check-circle variant="mini" />
                </template>
                <template x-if="toast.type === 'error'">
                    <flux:icon.x-circle variant="mini" />
                </template>
                <template x-if="toast.type === 'warning'">
                    <flux:icon.exclamation-triangle variant="mini" />
                </template>
            </div>

            <!-- Pesan -->
            <div class="flex-1 text-sm font-medium" x-text="toast.message"></div>
        </div>
    </template>
</div>

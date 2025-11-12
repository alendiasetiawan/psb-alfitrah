<div
    x-data="{ toasts: [] }"
    x-on:toast.window="
        toasts.push({ id: Date.now(), type: $event.detail.type, message: $event.detail.message });
        setTimeout(() => toasts.shift(), 3000);
        $flux.modals().close();
    "
    class="fixed top-5 right-5 z-50 space-y-3"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-x-20 opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transform ease-in duration-300 transition"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-x-20"
            class="flex items-center w-72 max-w-sm p-4 bg-white rounded-lg shadow-lg border"
            :class="{
                'border-green-300 text-green-700': toast.type === 'success',
                'border-red-300 text-red-700': toast.type === 'error',
                'border-yellow-300 text-yellow-700': toast.type === 'warning',
            }"
        >
            <!-- Icon -->
            <div class="flex-shrink-0 mr-2">
                <span x-text="toast.type === 'success' ? '✅' : (toast.type === 'error' ? '❌' : '⚠️')"></span>
            </div>

            <!-- Pesan -->
            <div class="flex-1 text-sm font-medium" x-text="toast.message"></div>

            <!-- Tombol Close -->
            <button
                @click="toasts = toasts.filter(t => t.id !== toast.id)"
                class="ml-3 text-gray-400 hover:text-gray-600"
            >
                ✖
            </button>
        </div>
    </template>
</div>

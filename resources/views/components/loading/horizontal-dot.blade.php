<!-- Accessible three-dot loader -->
<div role="status" aria-live="polite" class="flex items-center space-x-2">
    <span class="sr-only">Loading...</span>

    <div class="w-2 h-2 rounded-full bg-primary-600" style="animation: dot 0.9s infinite ease-in-out; animation-delay: 0s;"></div>
    <div class="w-2 h-2 rounded-full bg-primary-600" style="animation: dot 0.9s infinite ease-in-out; animation-delay: 0.15s;"></div>
    <div class="w-2 h-2 rounded-full bg-primary-600" style="animation: dot 0.9s infinite ease-in-out; animation-delay: 0.3s;"></div>
</div>

<style>
@keyframes dot {
    0%, 80%, 100% { transform: translateY(0); opacity: 0.35; }
    40% { transform: translateY(-6px); opacity: 1; }
}
</style>
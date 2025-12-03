<li
{{ $attributes->merge([
    'class' => 'px-3 py-3'
]) }}>
    <div class="flex justify-between items-center">
        <flux:text size="lg" variant="bold">
            {{ $title }}
        </flux:text>
        {{ $action }}
    </div>
    @isset($description)
        <flux:text variant="soft">
            {{ $description }}
        </flux:text>
    @endisset
</li>
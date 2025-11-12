<li
{{ $attributes->merge([
    'class' => 'px-3 py-3'
]) }}>
    <div class="flex justify-between items-center">
        <flux:text size="lg" variant="strong">
            {{ $title }}
        </flux:text>
        {{ $action }}
    </div>
    @isset($description)
        <flux:text variant="subtle">
            {{ $description }}
        </flux:text>
    @endisset
</li>
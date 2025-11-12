<div 
{{ $attributes->merge([
    'class' => 'flex justify-between items-center py-1'
]) }}>
    <div>
        <flux:heading size="lg">{{ $title }}</flux:heading>
        <flux:text variant="subtle">{{ $subTitle }}</flux:text>
        {{ $slot }}
    </div>

    @isset($buttonAction)
        <div class="flex justify-center">
            {{ $buttonAction }}
        </div>
    @endisset
</div>

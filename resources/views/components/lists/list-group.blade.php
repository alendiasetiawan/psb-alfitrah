<div 
{{ $attributes->merge([
    'class' => 'flex justify-between items-center py-1'
]) }}>
    <div>
        <flux:heading size="lg" variant="bold">{{ $title }}</flux:heading>
        <flux:text variant="soft">{{ $subTitle }}</flux:text>
        {{ $slot }}
    </div>

    @isset($buttonAction)
        <div class="flex justify-center">
            {{ $buttonAction }}
        </div>
    @endisset
</div>

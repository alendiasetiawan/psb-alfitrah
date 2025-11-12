@props([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'resize' => 'vertical',
    'invalid' => null,
    'rows' => 4,
    'isFormObject' => false,
    'isValidate' => false,
    'fieldName' => null //Property name for validation method and only needed when isValidate = true
])

@php
$invalid ??= ($name && $errors->has($name));

$classes = Flux::classes()
    ->add('block p-3 w-full')
    ->add('shadow-xs disabled:shadow-none border rounded-lg')
    ->add('bg-white dark:bg-white/10 dark:disabled:bg-white/[7%]')
    ->add($resize ? 'resize-y' : 'resize-none')
    ->add('text-base sm:text-sm text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500')
    ->add($invalid ? 'border-red-500' : 'border-zinc-200 border-b-zinc-300/80 dark:border-white/10')
    ;

$resizeStyle = match ($resize) {
    'none' => 'resize: none',
    'both' => 'resize: both',
    'horizontal' => 'resize: horizontal',
    'vertical' => 'resize: vertical',
    'isFormObject' => false
};
@endphp

<flux:with-field :$attributes>
    <textarea
        {{ $attributes->class($classes) }}
        rows="{{ $rows }}"
        style="{{ $resizeStyle }}; {{ $rows === 'auto' ? 'field-sizing: content' : '' }}"
        @isset ($name) name="{{ $name }}" @endisset
        @if ($invalid) aria-invalid="true" data-invalid @endif
        @if ($isValidate) x-on:input="form.{{ $fieldName }} = $wire.{{ $isFormObject ? 'form.' : '' }}inputs.{{ $fieldName }}; validate('{{$fieldName}}')" @endif
        data-flux-control
        data-flux-textarea
    >{{ $slot }}</textarea>
    @if ($isValidate)
        <!--Show error message-->
        <template x-if="errors.{{ $fieldName }}">
            <flux:error name="{{ $fieldName }}">
                <x-slot:message>
                    <span x-text="errors.{{ $fieldName }}"></span>
                </x-slot:message>
            </flux:error>
        </template>
        <!--#Show error message-->
    @endif
</flux:with-field>

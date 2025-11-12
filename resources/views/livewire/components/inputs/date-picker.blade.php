@props([
    'model' => null,
    'value' => null,
    'format' => 'YYYY-MM-DD',
    'placeholder' => 'Pilih tanggal...',
])

<div
    x-data="datepickerComponent({
        model: '{{ $model }}',
        format: '{{ $format }}',
        value: '{{ $value }}',
    })"
    x-init="setTimeout(() => initPicker(), 500)"
    wire:ignore
    class="w-full"
>
    <flux:input
        x-ref="input"
        type="text"
        placeholder="{{ $placeholder }}"
        value="{{ $value }}"
    />
</div>


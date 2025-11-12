@props([
    'model' => null,
    'placeholder' => 'Pilih tanggal',   
    'format' => 'YYYY-MM-DD',
    'minDate' => null,
    'maxDate' => null,
    'fieldName' => '',
    'isValidate' => false
])

<div
    x-data="datepicker({
        format: '{{ $format }}',
        minDate: '{{ $minDate }}',
        maxDate: '{{ $maxDate }}'
    })"
    x-init="initPicker();"
>
    <flux:input
    {{ $attributes->merge([
        'x-ref' => 'input',
        'wire:model' => $model,
        'placeholder' => $placeholder,
        'fieldName' => $fieldName,
        'isValidate' => $isValidate,
        'readonly' => true
    ]) }}
    />
</div>



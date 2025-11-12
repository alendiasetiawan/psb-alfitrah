@props([
    'selectedId' => '',
    'selectedName' => '',
    'fieldName' => '',
    'selectedFieldName' => '',
    'isFormObject' => false
])

<li
    class="px-3 py-2 cursor-pointer hover:bg-gray-100"
    @click="$wire.{{ $isFormObject ? 'form.' : '' }}inputs.{{ $fieldName }} = '{{ addslashes($selectedName) }}'; $wire.{{ $isFormObject ? 'form.' : '' }}inputs.{{ $selectedFieldName }} = '{{ addslashes($selectedId) }}'; open = false; form.{{ $selectedFieldName }} = $wire.{{ $isFormObject ? 'form.' : '' }}inputs.{{$selectedFieldName}}; validate('{{ $selectedFieldName }}')"
>
    {{ $slot }}
</li>
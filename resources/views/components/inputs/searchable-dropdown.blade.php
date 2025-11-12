@props([
    'label' => '',
    'icon' => '',
    'placeholder' => '',
    'fieldName' => '',
    'selectedFieldName' => '',
    'initFunction' => '',
    'isFormObject' => false
])

<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <flux:input
        label="{{ $label }}"
        icon="{{ $icon }}"
        placeholder="{{ $placeholder }}"
        wire:model.live.debounce.500ms="{{ $isFormObject ? 'form.' : '' }}inputs.{{ $fieldName }}"
        wire:click="{{ $initFunction }}"
        @click="open = true"
        :loading="false"
    />

    <!--Field untuk menampung selected Id-->
    <flux:input type="hidden" wire:model="{{ $isFormObject ? 'form.' : '' }}inputs.{{ $selectedFieldName }}" x-effect="form.{{ $selectedFieldName }} = $wire.{{ $isFormObject ? 'form.' : '' }}inputs.{{ $selectedFieldName }}; validate('{{ $selectedFieldName }}')"/>
    <!--#Field untuk menampung selected Id-->

    <div x-show="open" x-transition class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-40 overflow-y-auto scrollbar-hidden">
        <ul>
            <!--Looping list data-->
            <!--Isi dengan components/inputs/dropdown-list.blade.php-->
            {{ $slot }}

            {{-- @forelse($results as $result)
                <li
                    class="px-3 py-2 cursor-pointer hover:bg-gray-100"
                    wire:click="selectItem({{ $result->id }})"
                    @click="$wire.inputs.{{ $fieldName }} = '{{ addslashes($result->name) }}'; open = false; form.{{ $selectedFieldName }} = $wire.inputs.{{$selectedFieldName}}; validate('{{ $selectedFieldName }}')"
                >
                    {{ $result->name }}
                </li>
            @empty
                <li class="px-3 py-2 text-gray-500">Data Tidak Ditemukan!</li>
            @endforelse --}}
        </ul>
    </div>
</div>
@props([
    'variant' => 'default',
])

<flux:with-field :$attributes>
    <flux:delegate-component :component="'select.variants.' . $variant">{{ $slot }}</flux:delegate-component>
</flux:with-field>

<!--Select Validation->
    {{-- x-on:input="form.gender = $wire.form.inputs.gender; validate('gender')" --}}

    <template x-if="errors.isParent">
        <flux:error name="isParent">
            <x-slot:message>
                <span x-text="errors.isParent"></span>
            </x-slot:message>
        </flux:error>
    </template>
<!--#Select Validation-->
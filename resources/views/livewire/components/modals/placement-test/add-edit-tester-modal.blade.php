<div>
    <flux:modal name="{{ $modalId }}" variant="flyout" position="{{ $isMobile ? 'bottom' : '' }}" class="{{ $isMobile ? 'w-11/12' : '' }}">
        <flux:heading size="xl">Manajemen Data Penguji</flux:heading>

        @if (session('error-update-tester'))
            <div class="grid grid-cols-1 mt-4">
                <x-notifications.basic-alert>
                    <x-slot:title>{{ session('error-update-tester') }}</x-slot:title>
                </x-notifications.basic-alert>
            </div>
        @endif

        <div class="my-3">
            <flux:separator text="Tambah Penguji"/>
        </div>

        <!--SECTION: FORM ADD TESTER-->
        <form
            wire:submit="saveTester"
            x-data="
            formValidation({
                testerName: ['required', 'minLength:3'],
            })"
            x-on:tester-saved.window="
            isSubmitActive = false
            ">
            <div class="grid grid-cols-1 mt-4 gap-3">
                <!--Tester Name-->
                <flux:input
                label="Nama Penguji"
                placeholder="Tulis nama lengkap"
                icon="user"
                wire:model="inputs.testerName"
                :isValidate="true"
                fieldName="testerName"/>
                <!--#Tester Name-->

                <!--Gender-->
                <flux:radio.group wire:model="inputs.gender" label="Jenis Kelamin" required
                    oninvalid="this.setCustomValidity('Silahkan pilih jenis kelamin')"
                    oninput="this.setCustomValidity('')">
                    <div class="flex items-center gap-2">
                        <flux:radio value="Laki-Laki" label="Laki-laki" />
                        <flux:radio value="Perempuan" label="Perempuan" />
                    </div>
                </flux:radio.group>
                <!--#Gender-->

                <!--Action Button-->
                <div class="flex items-center gap-2">
                    <flux:button
                        type="submit"
                        variant="primary"
                        x-bind:disabled="!isSubmitActive"
                        :loading="false">
                        <x-items.loading-indicator wireTarget="saveTester">
                            <x-slot:buttonName>Tambah</x-slot:buttonName>
                            <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                        </x-items.loading-indicator>
                    </flux:button>

                    <flux:modal.close>
                        <flux:button variant="filled">Batal</flux:button>
                    </flux:modal.close>
                </div>

                <!--#Action Button-->
            </div>
        </form>
        <!--#FORM ADD TESTER-->

        <div class="my-3">
            <flux:separator text="Daftar Penguji"/>
        </div>

        <div class="grid grid-cols-1 mt-4 gap-3"
            x-data="{
                editingTesterId: null,
                editName: '',
                editGender: ''
            }"
            x-on:tester-saved.window="
            editingTesterId = null
            $wire.getTesters()
            ">
            @forelse ($testers as $tester)
                <div class="col-span-1" wire:key="tester-{{ $tester['id'] }}">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="bg-transparent border-white border-1 px-3 py-1 rounded-lg">
                                <flux:text size="lg">{{ $loop->iteration }}</flux:text>
                            </div>
                            <div class="flex flex-col items-start" :class="editingTesterId === {{ $tester['id'] }} ? 'gap-1' : ''">
                                <!--Tester Name-->
                                <template x-if="editingTesterId === {{ $tester['id'] }}">
                                    <flux:input
                                    icon="user"
                                    size="sm"
                                    x-model="editName"
                                    @keydown.enter="$wire.set('editTesterName.{{ $tester['id'] }}', editName); $wire.set('editTesterGender.{{ $tester['id'] }}', editGender); $wire.saveEditTester({{ $tester['id'] }})"/>
                                </template>
                                <template x-if="editingTesterId !== {{ $tester['id'] }}">
                                    <flux:text variant="bold" class="truncate max-w-[200px]">{{ $tester['name'] }}</flux:text>
                                </template>
                                <!--#Tester Name-->

                                <!--Gender-->
                                <template x-if="editingTesterId !== {{ $tester['id'] }}">
                                    <flux:text variant="soft">{{ $tester['gender'] }}</flux:text>
                                </template>
                                <template x-if="editingTesterId === {{ $tester['id'] }}">
                                    <flux:radio.group x-model="editGender">
                                        <div class="flex items-center gap-2">
                                            <flux:radio value="Laki-Laki" label="Laki-laki" />
                                            <flux:radio value="Perempuan" label="Perempuan" />
                                        </div>
                                    </flux:radio.group>
                                </template>
                                <!--#Gender-->
                            </div>
                        </div>

                        <div class="flex items-center gap-1">
                            <!--Save Button-->
                            <template x-if="editingTesterId === {{ $tester['id'] }}">
                                <flux:icon.check-check class="text-primary hover:cursor-pointer" variant="mini" @click="$wire.set('editTesterName.{{ $tester['id'] }}', editName); $wire.set('editTesterGender.{{ $tester['id'] }}', editGender); $wire.saveEditTester({{ $tester['id'] }})"/>
                            </template>
                            <!--#Save Button-->

                            <!--Edit Button-->
                            <template x-if="editingTesterId !== {{ $tester['id'] }}">
                                <flux:icon.file-pen-line class="text-primary hover:cursor-pointer" variant="mini" @click="
                                    editName = '{{ addslashes($tester['name']) }}';
                                    editGender = '{{ addslashes($tester['gender']) }}';
                                    editingTesterId = {{ $tester['id'] }};
                                "/>
                            </template>
                            <!--#Edit Button-->

                            <!--Delete Button-->
                            <flux:icon.trash class="text-red-400 hover:cursor-pointer" variant="mini" @click="$wire.deleteTester({{ $tester['id'] }})"/>
                            <!--#Delete Button-->
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1">
                    <x-animations.not-found/>
                </div>
            @endforelse
        </div>
    </flux:modal>
</div>

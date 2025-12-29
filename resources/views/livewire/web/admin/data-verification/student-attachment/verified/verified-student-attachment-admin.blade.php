<div>
    <x-navigations.breadcrumb>
        <x-slot:title>Selesai Verifikasi Berkas</x-slot:title>
        <x-slot:activePage>Daftar Santri Dengan Berkas Valid</x-slot:activePage>
    </x-navigations.breadcrumb>

    <!--ANCHOR: STUDENT LISTS-->
    <div class="grid grid-cols-1 mt-4">
            <x-tables.basic-table :headers="['No', 'Nama Santri', 'Whatsapp', 'Cabang', 'Sekolah Asal', 'Tanggal Verifikasi']">
                <x-slot:heading>
                    Tabel Siswa Selesai Berkas
                </x-slot:heading>

                <x-slot:action>
                    <!-- Search and Filter -->
                    <div class="grid grid-cols-2 justify-between items-center mt-4 gap-2">
                        <div class="flex gap-2">
                            <div class="w-4/6">
                                <flux:input placeholder="Cari nama santri" wire:model.live.debounce.500ms="searchStudent" icon="search" />
                            </div>

                            <div class="w-2/6">
                                <flux:select wire:model.live="selectedAdmissionId">
                                    @foreach ($admissionYearLists as $key => $value)
                                        <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </div>
                        </div>

                        <div class="flex items-end justify-end gap-2">
                            <flux:badge variant="solid" color="primary" icon="user">
                                {{ $this->totalVerifiedAttachmentStudent }} Santri
                            </flux:badge>
                        </div>
                    </div>
                </x-slot:action>

                <!--NOTE: Student's Table-->
                @forelse ($this->verifiedStudentAttachments as $student)
                    <x-tables.row 
                        wire:key="{{ $student->id }}" 
                        wire:click="showStudentAttachment('{{ Crypt::encrypt($student->id) }}')"
                        :striped="true" 
                        :hover="true"
                        :loop=$loop>
                            <x-tables.cell>
                                <flux:text>{{ $setCount++ }}</flux:text>
                            </x-tables.cell>
                            <x-tables.cell>
                                <div class="flex gap-2 items-center">
                                    <div>
                                        <flux:avatar
                                            src="{{ !empty($student->user_photo) ? asset($student->user_photo) : '' }}"
                                            circle icon="user" />
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <flux:text>
                                            {{ $student->student_name }}
                                        </flux:text>
                                        <flux:text size="sm" variant="soft">
                                            {{ $student->gender }}
                                        </flux:text>
                                    </div>
                                </div>
                            </x-tables.cell>
                            <x-tables.cell>
                                <div class="flex items-center gap-1">
                                    <flux:text>
                                        0{{ $student->mobile_phone }}
                                    </flux:text>
                                    <x-items.wa-icon href="https://wa.me/{{ $student->country_code }}{{ $student->mobile_phone }}"/>
                                </div>
                            </x-tables.cell>
                            <x-tables.cell>
                                <flux:text>{{ $student->branch_name }}</flux:text>
                                <flux:text variant="soft" size="sm">{{ $student->program_name }}</flux:text>
                            </x-tables.cell>
                            <x-tables.cell>
                                <flux:text>
                                    {{ $student->old_school_name }}
                                </flux:text>
                            </x-tables.cell>
                            <x-tables.cell>
                                <flux:text>
                                    {{ \App\Helpers\DateFormatHelper::indoDateTime($student->attachment_verified_at) }}
                                </flux:text>
                            </x-tables.cell>
                    </x-tables.row>
                @empty
                    <x-tables.empty text="Tidak ada data yang ditemukan" :colspan="6" />
                @endforelse
                <!--#Student's Table-->

                @if ($this->verifiedStudentAttachments->hasPages())
                    <x-slot:pagination>
                        {{ $this->verifiedStudentAttachments->links(data: ['scrollTo' => false]) }}
                    </x-slot:pagination>
                @endif
            </x-tables.basic-table>
    </div>
</div>

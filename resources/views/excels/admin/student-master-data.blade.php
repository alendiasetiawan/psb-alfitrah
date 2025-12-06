<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Cabang</th>
        <th>Program</th>
        <th>Tahun Ajaran</th>
        <th>Status Beasiswa</th>
        <th>Nama Santri</th>
        <th>Jenis Kelamin</th>
        <th>Tempat Lahir</th>
        <th>Tanggal Lahir</th>
        <th>Tanggal Lahir (ID)</th>
        <th>Asal Sekolah</th>
        <th>Alamat Sekolah Asal</th>
        <th>NPSN</th>
        <th>NISN</th>
        <th>Alamat</th>
        <th>Provinsi</th>
        <th>Kabupaten</th>
        <th>Kecamatan</th>
        <th>Kelurahan</th>
        <th>Nama Ayah</th>
        <th>Tempat Lahir Ayah</th>
        <th>Tanggal Lahir Ayah</th>
        <th>Alamat Ayah</th>
        <th>WA Ayah</th>
        <th>Pendidikan Ayah</th>
        <th>Pekerjaan Ayah</th>
        <th>Penghasilan Ayah</th>
        <th>Nama Ibu</th>
        <th>Tempat Lahir Ibu</th>
        <th>Tanggal Lahir Ibu</th>
        <th>Alamat Ibu</th>
        <th>WA Ibu</th>
        <th>Pendidikan Ibu</th>
        <th>Pekerjaan Ibu</th>
        <th>Penghasilan Ibu</th>
        <th>Nama Wali</th>
        <th>Tempat Lahir Wali</th>
        <th>Tanggal Lahir Wali</th>
        <th>Alamat Wali</th>
        <th>WA Wali</th>
        <th>Pendidikan Wali</th>
        <th>Pekerjaan Wali</th>
        <th>Penghasilan Wali</th>
        <th>Photo</th>
        <th>Akte Kelahiran</th>
        <th>Kartu Keluarga</th>
        <th>KTP Orang Tua</th>
    </tr>
    </thead>
    <tbody>
    @foreach($studentLists as $student)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $student->branch_name }}</td>
            <td>{{ $student->program_name }}</td>
            <td>{{ $student->academic_year }}</td>
            <td>
                @if ($student->is_scholarship)
                    Beasiswa
                @else
                    Tidak Beasiswa
                @endif
            </td>
            <td>{{ $student->name }}</td>
            <td>{{ $student->gender }}</td>
            <td>{{ $student->birth_place }}</td>
            <td>{{ $student->birth_date }}</td>
            <td>
                {{ \Carbon\Carbon::parse($student->birth_date)->locale('id')->isoFormat('D MMMM YYYY'); }}
            </td>
            <td>{{ $student->old_school_name }}</td>
            <td>{{ $student->old_school_address }}</td>
            <td>{{ $student->npsn }}</td>
            <td>{{ $student->nisn }}</td>
            <td>{{ $student->address }}</td>
            <td>{{ $student->province_name }}</td>
            <td>{{ $student->regency_name }}</td>
            <td>{{ $student->district_name }}</td>
            <td>{{ $student->village_name }}</td>
            <td>{{ $student->parent->father_name ?? '' }}</td>
            <td>{{ $student->parent->father_birth_place ?? '' }}</td>
            <td>{{ $student->parent->father_birth_date ?? '' }}</td>
            <td>{{ $student->parent->father_address ?? '' }}</td>
            <td>{{ $student->parent->father_country_code ?? '' }}{{ $student->parent->father_mobile_phone ?? '' }}</td>
            <td>{{ $student->parent->educationFather->name ?? ''}}</td>
            <td>{{ $student->parent->jobFather->name ?? ''}}</td>
            <td>{{ $student->parent->sallaryFather->name ?? ''}}</td>
            <td>{{ $student->parent->mother_name ?? ''}}</td>
            <td>{{ $student->parent->mother_birth_place ?? ''}}</td>
            <td>{{ $student->parent->mother_birth_date ?? ''}}</td>
            <td>{{ $student->parent->mother_address ?? ''}}</td>
            <td>{{ $student->parent->mother_country_code ?? '' }}{{ $student->parent->mother_mobile_phone ?? '' }}</td>
            <td>{{ $student->parent->educationMother->name ?? ''}}</td>
            <td>{{ $student->parent->jobMother->name ?? ''}}</td>
            <td>{{ $student->parent->sallaryMother->name ?? ''}}</td>
            <td>{{ $student->parent->guardian_name ?? ''}}</td>
            <td>{{ $student->parent->guardian_birth_place ?? ''}}</td>
            <td>{{ $student->parent->guardian_birth_date ?? ''}}</td>
            <td>{{ $student->parent->guardian_address ?? ''}}</td>
            <td>{{ $student->parent->guardian_country_code ?? '' }}{{ $student->parent->guardian_mobile_phone ?? '' }}</td>
            <td>{{ $student->parent->educationGuardian->name ?? ''}}</td>
            <td>{{ $student->parent->jobGuardian->name ?? ''}}</td>
            <td>{{ $student->parent->sallaryGuardian->name ?? ''}}</td>
            <td>
                <a href="{{ env('APP_URL') . '/' .$student->photo }}">
                    {{ env('APP_URL') . '/' .$student->photo }}
                </a>
            </td>
            <td>
                <a href="{{ env('APP_URL') . '/' . $student->born_card }}">
                    {{ env('APP_URL') . '/' . $student->born_card }}
                </a>
            </td>
            <td>
                <a href="{{ env('APP_URL') . '/' . $student->family_card }}">
                    {{ env('APP_URL') . '/' . $student->family_card }}
                </a>
            </td>
            <td>
                <a href="{{ env('APP_URL') . '/' . $student->parent_card }}">
                    {{ env('APP_URL') . '/' . $student->parent_card }}
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

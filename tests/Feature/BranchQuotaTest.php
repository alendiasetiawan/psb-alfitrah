<?php

use App\Livewire\Visitor\StudentRegistration\BranchQuota;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;

beforeEach(function () {
    // Isolate these cases from the legacy MySQL migrations and local admission data.
    config([
        'database.default' => 'sqlite',
        'database.connections.sqlite.database' => ':memory:',
        'database.connections.sqlite.foreign_key_constraints' => false,
    ]);
    DB::purge('sqlite');

    Schema::create('admissions', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->string('status');
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('admission_batches', function (Blueprint $table): void {
        $table->id();
        $table->bigInteger('admission_id');
        $table->date('open_date');
        $table->date('close_date');
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('branches', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->string('address')->nullable();
        $table->string('mobile_phone')->nullable();
        $table->string('map_link')->nullable();
        $table->string('photo')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('education_programs', function (Blueprint $table): void {
        $table->id();
        $table->bigInteger('branch_id');
        $table->string('name');
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('admission_quotas', function (Blueprint $table): void {
        $table->id();
        $table->bigInteger('admission_id');
        $table->bigInteger('education_program_id');
        $table->integer('amount');
        $table->string('status');
        $table->timestamps();
    });

    DB::table('admissions')->insert(['id' => 1, 'name' => '2026/2027', 'status' => 'Buka']);
    DB::table('admission_batches')->insert([
        'admission_id' => 1,
        'open_date' => now()->subDay()->toDateString(),
        'close_date' => now()->addDay()->toDateString(),
    ]);
    DB::table('branches')->insert(['id' => 1, 'name' => 'Pondok Test']);
    DB::table('education_programs')->insert(['id' => 1, 'branch_id' => 1, 'name' => 'Jenjang Test']);
    DB::table('admission_quotas')->insert([
        'admission_id' => 1, 'education_program_id' => 1, 'amount' => 30, 'status' => 'Buka',
    ]);
});

test('quota page handles missing registration settings without a server error', function (string $table, string $message) {
    DB::table($table)->delete();

    $this->get(route('branch_quota'))
        ->assertOk()
        ->assertSee($message)
        ->assertDontSee('Isi Formulir')
        ->assertDontSee('Kuota Penerimaan : 0 Santri');
})->with([
    'admission year' => ['admissions', 'Informasi penerimaan santri baru belum tersedia.'],
    'pondok' => ['branches', 'Informasi pondok belum tersedia.'],
    'education program' => ['education_programs', 'Jenjang pendidikan belum tersedia.'],
    'quota' => ['admission_quotas', 'Kuota penerimaan belum tersedia.'],
]);

test('an entirely unconfigured quota page remains safe on livewire refresh', function () {
    foreach (['admissions', 'admission_batches', 'branches', 'education_programs', 'admission_quotas'] as $table) {
        DB::table($table)->delete();
    }

    Livewire::test(BranchQuota::class)
        ->assertSee('Informasi penerimaan santri baru belum tersedia.')
        ->assertDontSee('Isi Formulir')
        ->call('$refresh')
        ->assertSee('Informasi penerimaan santri baru belum tersedia.')
        ->assertDontSee('Isi Formulir');
});

test('quota from another admission year does not make registration available', function () {
    DB::table('admissions')->insert(['id' => 2, 'name' => '2025/2026', 'status' => 'Tutup']);
    DB::table('admission_quotas')->update(['admission_id' => 2]);

    $this->get(route('branch_quota'))
        ->assertOk()
        ->assertSee('Kuota penerimaan belum tersedia.')
        ->assertDontSee('Isi Formulir')
        ->assertDontSee('30 Santri');
});

test('a pondok cannot start registration while one of its programs lacks quota', function () {
    DB::table('education_programs')->insert(['branch_id' => 1, 'name' => 'Jenjang Belum Siap']);

    $this->get(route('branch_quota'))
        ->assertOk()
        ->assertSee('30 Santri')
        ->assertSee('Kuota penerimaan belum tersedia.')
        ->assertSee('Pendaftaran pondok ini belum tersedia.')
        ->assertDontSee('Isi Formulir');
});

test('an incomplete pondok does not disable registration for a fully configured pondok', function () {
    DB::table('branches')->insert(['id' => 2, 'name' => 'Pondok Belum Siap']);

    $response = $this->get(route('branch_quota'))
        ->assertOk()
        ->assertSee('Pondok Test')
        ->assertSee('Pondok Belum Siap')
        ->assertSee('Jenjang pendidikan belum tersedia.')
        ->assertSee('Isi Formulir');

    $dom = new DOMDocument;
    @$dom->loadHTML($response->getContent());
    $links = (new DOMXPath($dom))->query('//a[.//button[normalize-space(.)="Isi Formulir"]]');
    expect($links->length)->toBe(1);
    $encryptedBranchId = basename(parse_url($links->item(0)->getAttribute('href'), PHP_URL_PATH));
    expect(Crypt::decrypt(rawurldecode($encryptedBranchId)))->toBe(1);
});

test('configured quotas remain visible and registration opens normally', function () {
    $this->get(route('branch_quota'))
        ->assertOk()
        ->assertSee('2026/2027')
        ->assertSee('Pondok Test')
        ->assertSee('Jenjang Test')
        ->assertSee('30 Santri')
        ->assertSee('Isi Formulir')
        ->assertDontSee('belum tersedia');
});

test('closed registration still shows its closed message and hides the form button', function () {
    DB::table('admission_batches')->update(['close_date' => now()->subDay()->toDateString()]);

    $this->get(route('branch_quota'))
        ->assertOk()
        ->assertSee('pendaftaran sudah tutup')
        ->assertSee('Tutup')
        ->assertDontSee('Isi Formulir');
});

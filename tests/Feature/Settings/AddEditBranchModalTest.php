<?php

use App\Models\Core\Branch;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use App\Livewire\Components\Modals\Setting\AddEditBranchModal;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

//Test 1
test('can create branch with valid data and photo', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $this->actingAs($user);

    $file = UploadedFile::fake()->image('photo.jpg');
    
    Livewire::test(AddEditBranchModal::class)
        ->set('inputs.branchName', 'Cabang Test')
        ->set('inputs.mobilePhone', '08123456789')
        ->set('inputs.branchAddress', 'Jl. Testing')
        ->set('inputs.mapLink', 'https://maps.google.com')
        ->set('branchPhoto', $file)
        ->call('saveBranch');
    
    $this->assertDatabaseHas('branches', [
        'name' => 'Cabang Test',
        'mobile_phone' => '08123456789',
        'address' => 'Jl. Testing',
    ]);
});

//Test 2
test('branch update changes values and keeps/manages photo as appropriate', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create original branch (simulate existing DB row)
    $branch = Branch::create([
        'name' => 'Original',
        'mobile_phone' => '0888999911',
        'address' => 'Jl. Lama',
        'map_link' => null,
        'photo' => 'images/branch-logo/old-photo.jpg',
    ]);

    // Update branch without new photo (should retain old photo)
    Livewire::test(AddEditBranchModal::class)
        ->set('inputs.selectedBranchId', $branch->id)
        ->set('inputs.branchName', 'Baru')
        ->set('inputs.mobilePhone', '081234512345')
        ->set('inputs.branchAddress', 'Jl. Baru')
        ->set('branchPhoto', 'images/branch-logo/old-photo.jpg')
        ->call('saveBranch');

    $this->assertDatabaseHas('branches', [
        'id' => $branch->id,
        'name' => 'Baru',
        'photo' => 'images/branch-logo/old-photo.jpg',
    ]);

    // Update with new photo
    $file = UploadedFile::fake()->image('photo2.jpg');
    Livewire::test(AddEditBranchModal::class)
        ->set('inputs.selectedBranchId', $branch->id)
        ->set('inputs.branchName', 'Update With Photo')
        ->set('inputs.mobilePhone', '087654321099')
        ->set('inputs.branchAddress', 'Jl. Baru No.2')
        ->set('branchPhoto', $file)
        ->call('saveBranch');
    
    $this->assertDatabaseHas('branches', [
        'id' => $branch->id,
        'name' => 'Update With Photo',
    ]);
});

//Test 3
test('validation fails for missing or broken fields', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(AddEditBranchModal::class)
        ->set('inputs.branchName', '')
        ->set('inputs.mobilePhone', '012')
        ->set('inputs.branchAddress', '')
        ->call('saveBranch')
        ->assertHasErrors([
            'inputs.branchName' => 'required',
            'inputs.branchAddress' => 'required',
            'inputs.mobilePhone' => 'min',
        ]);
});

//Test 4
test('photo upload fails with wrong type', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $this->actingAs($user);

    // $file = UploadedFile::fake()->image('photo.pdf');
    $file = UploadedFile::fake()->create('malware.php', 100, 'application/octet-stream');

    Livewire::test(AddEditBranchModal::class)
        ->set('inputs.branchName', 'Branch with Malware')
        ->set('inputs.mobilePhone', '0811223344')
        ->set('inputs.branchAddress', 'Somewhere')
        ->set('branchPhoto', $file)
        ->call('saveBranch')
        ->assertHasErrors(['branchPhoto' => 'mimes']);
});

//Test 5
test('internal exception displays flash error', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $mock = \Mockery::mock('overload:App\\Services\\UploadFileService');
    $mock->shouldReceive('compressAndSavePhoto')->andThrow(new Exception('Failed!'));

    $file = UploadedFile::fake()->image('x.jpg');
    
    Livewire::test(AddEditBranchModal::class)
        ->set('inputs.branchName', 'Branch X')
        ->set('inputs.mobilePhone', '0812456789')
        ->set('inputs.branchAddress', 'Jl. Error')
        ->set('branchPhoto', $file)
        ->call('saveBranch');
    
    $this->assertSessionHas('save-failed', 'Data gagal disimpan, silahkan coba lagi!');
});

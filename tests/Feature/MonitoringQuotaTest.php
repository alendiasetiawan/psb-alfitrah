<?php

use App\Enums\RoleEnum;
use App\Models\User;
use Detection\MobileDetect;

test('admin can open monitoring quota on desktop and mobile', function (bool $mobile) {
    $this->mock(MobileDetect::class)
        ->shouldReceive('isMobile')
        ->andReturn($mobile);

    $admin = new User([
        'role_id' => RoleEnum::ADMIN,
        'username' => 'admin-test',
        'fullname' => 'Admin Test',
    ]);

    $this->actingAs($admin)
        ->withSession(['userCheck' => true, 'userData' => $admin])
        ->get(route('admin.master_data.monitoring_quota'))
        ->assertOk()
        ->assertSee('Monitoring Kuota');
})->with(['desktop' => false, 'mobile' => true]);

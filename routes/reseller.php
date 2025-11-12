<?php

use App\Const\RoleConst;
use App\Enums\RoleEnum;
use App\Livewire\Warehouse\Reseller\DashboardReseller;
use Illuminate\Support\Facades\Route;

Route::middleware('role:'.RoleEnum::RESELLER.'')->group(function () {
    Route::group(['prefix' => 'reseller', 'as' => 'reseller.'], function () {
        // Route::get('/dashboard', DashboardReseller::class)->name('dashboard');
    });
});
<?php

use App\Http\Controllers\Admin\LocalMyAccountController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () {
    Route::get('edit-account-info', [LocalMyAccountController::class, 'getAccountInfoForm'])->name('backpack.account.info');
    Route::post('edit-account-info', [LocalMyAccountController::class, 'postAccountInfoForm'])->name('backpack.account.info.store');
    Route::post('change-password', [LocalMyAccountController::class, 'postChangePasswordForm'])->name('backpack.account.password');
    Route::crud('part-template', 'PartTemplateCrudController');
    Route::crud('part-template/{parent_id}/part-template', 'PartTemplateCrudController');
    Route::crud('part', 'PartCrudController');
    Route::crud('part-item', 'PartItemCrudController');
    Route::crud('part-batch', 'PartBatchCrudController');
    Route::crud('equipment', 'EquipmentCrudController');
    Route::crud('tag', 'TagCrudController');
    Route::crud('storage-location', 'StorageLocationCrudController');
    Route::crud('storage-zone', 'StorageZoneCrudController');
    Route::crud('storage-movement', 'StorageMovementCrudController');
    Route::crud('stock-movement', 'StockMovementCrudController');
    Route::crud('write-off-reason', 'WriteOffReasonCrudController');
    Route::crud('part-item-return', 'PartItemReturnCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */

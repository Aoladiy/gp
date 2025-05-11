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
    Route::crud('part/{storage_requireable_id}/storage-requirement', 'StorageRequirementCrudController');
    Route::crud('part-item', 'PartItemCrudController');
    Route::crud('part-item/{storage_requireable_id}/storage-requirement', 'StorageRequirementCrudController');
    Route::crud('part-batch', 'PartBatchCrudController');
    Route::crud('equipment', 'EquipmentCrudController');
    Route::crud('storage-location', 'StorageLocationCrudController');
    Route::crud('storage-location/{parent_id}/storage-location', 'StorageLocationCrudController');
    Route::crud('storage-location/{storage_requireable_id}/storage-requirement', 'StorageRequirementCrudController');
    Route::crud('stock-movement', 'StockMovementCrudController');
    Route::crud('rotation-method', 'RotationMethodCrudController');
    Route::crud('lighting-level', 'LightingLevelCrudController');
    Route::crud('part-item-status', 'PartItemStatusCrudController');
    Route::crud('stock-movement-type', 'StockMovementTypeCrudController');
    Route::crud('report', 'ReportCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */

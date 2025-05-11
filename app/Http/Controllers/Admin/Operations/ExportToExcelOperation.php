<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Jobs\ExportStockMovementsToExcelJob;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;

trait ExportToExcelOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupExportToExcelRoutes($segment, $routeName, $controller): void
    {
        Route::get($segment.'/export-to-excel', [
            'as'        => $routeName.'.exportToExcel',
            'uses'      => $controller.'@exportToExcel',
            'operation' => 'exportToExcel',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupExportToExcelDefaults(): void
    {
        CRUD::allowAccess('exportToExcel');

        CRUD::operation('exportToExcel', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
             CRUD::addButton('top', 'export_to_excel', 'view', 'crud::buttons.export_to_excel');
            // CRUD::addButton('line', 'export_to_excel', 'view', 'crud::buttons.export_to_excel');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return RedirectResponse
     */
    public function exportToExcel(): RedirectResponse
    {
        $this->crud->hasAccessOrFail('exportToExcel');

        ExportStockMovementsToExcelJob::dispatch(backpack_user()->id);

        Alert::success('Экспорт запущен в фоновом режиме. Файл будет доступен после завершения.')->flash();

        return redirect()->back();
    }
}

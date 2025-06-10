<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PermissionsEnum;
use App\Models\Report;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReportCrudController extends BaseCrudController
{
    use ListOperation;
    use ShowOperation;

    public static function getPermissionEnum(): PermissionsEnum
    {
        return PermissionsEnum::REPORTS;
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup(): void
    {
        parent::setup();
        CRUD::setModel(Report::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/report');
        CRUD::setEntityNameStrings('Отчет', 'Отчеты');
        $this->crud->allowAccess('downloadReport');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation(): void
    {
        $this->crud->addColumn([
            'name' => 'type',
            'label' => 'Тип отчета',
        ]);
        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => 'Создан',
            'type' => 'datetime',
            'format' => 'DD.MM.YYYY HH:mm:ss',
        ]);
        $this->crud->addButton('line', 'download_report', 'view', 'vendor.backpack.crud.buttons.download_report');
        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }
}

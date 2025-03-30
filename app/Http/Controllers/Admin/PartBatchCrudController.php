<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PartBatchRequest;
use App\Models\Part;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PartBatchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PartBatchCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup(): void
    {
        CRUD::setModel(\App\Models\PartBatch::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/part-batch');
        CRUD::setEntityNameStrings('part batch', 'part batches');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation(): void
    {
        $this->crud->addColumn('id');
        $this->crud->addColumn([
            'name' => 'part_id',
            'label' => 'Деталь',
            'type' => 'select',
            'entity' => 'part',
            'attribute' => 'name',
            'model' => Part::class,
        ]);
        $this->crud->addColumn('batch_number');
        $this->crud->addColumn('received_at');
        $this->crud->addColumn('expiry_date');

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(PartBatchRequest::class);

        $this->crud->addField([
            'name' => 'part_id',
            'label' => 'Запчасть',
            'type' => 'select_from_array',
            'options' => Part::query()->pluck('name', 'id'),
            'allows_null' => false,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'batch_number',
            'label' => 'Номер партии',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'received_at',
            'label' => 'Когда получено',
            'type' => 'datetime',
        ]);

        $this->crud->addField([
            'name' => 'expiry_date',
            'label' => 'Годен до',
            'type' => 'datetime',
        ]);

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}

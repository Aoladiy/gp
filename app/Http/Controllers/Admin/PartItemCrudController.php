<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PartItemRequest;
use App\Models\Part;
use App\Models\PartBatch;
use App\Models\PartItemStatus;
use App\Models\PartTemplate;
use App\Models\StorageLocation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PartItemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PartItemCrudController extends CrudController
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
        $this->crud->allowAccess('related_storage_requirements');
        CRUD::setModel(\App\Models\PartItem::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/part-item');
        CRUD::setEntityNameStrings('part item', 'part items');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation(): void
    {
        if ($this->crud->hasAccess('related_storage_requirements')) {
            $this->crud->addButtonFromView('line', 'related_storage_requirements', 'related_storage_requirements');
        }
        $this->crud->addColumn('id');
        $this->crud->addColumn([
            'name' => 'fake',
            'label' => 'Шаблон',
            'type' => 'select',
            'entity' => 'part.partTemplate',
            'attribute' => 'name',
            'model' => PartTemplate::class,
        ]);
        $this->crud->addColumn([
            'name' => 'part_id',
            'label' => 'Деталь',
            'type' => 'select',
            'entity' => 'part',
            'attribute' => 'name',
            'model' => Part::class,
        ]);
        $this->crud->addColumn('serial_number')
            ->setColumnLabel('serial_number', 'Серийный номер');
        $this->crud->addColumn([
            'name' => 'part_batch_id',
            'label' => 'Партия',
            'type' => 'select',
            'entity' => 'partBatch',
            'attribute' => 'batch_number',
            'model' => PartBatch::class,
        ]);
        $this->crud->addColumn([
            'name' => 'storage_location_id',
            'label' => 'Локация',
            'type' => 'select',
            'entity' => 'storageLocation',
            'attribute' => 'name',
            'model' => StorageLocation::class,
        ]);
        $this->crud->addColumn([
            'name' => 'status_id',
            'label' => 'Статус',
            'type' => 'select',
            'entity' => 'status',
            'attribute' => 'name',
            'model' => PartItemStatus::class,
        ]);

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
        CRUD::setValidation(PartItemRequest::class);

        $this->crud->addField([
            'name' => 'part_id',
            'label' => 'Запчасть',
            'type' => 'select_from_array',
            'options' => Part::query()->pluck('name', 'id'),
            'allows_null' => false,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'serial_number',
            'label' => 'Серийный номер',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'part_batch_id',
            'label' => 'Партия',
            'type' => 'select_from_array',
            'options' => PartBatch::query()->pluck('batch_number', 'id'),
            'allows_null' => true,
            'default' => null,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'storage_location_id',
            'label' => 'Складская локация',
            'type' => 'select_from_array',
            'options' => StorageLocation::query()->pluck('name', 'id'),
            'allows_null' => true,
            'default' => null,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'status_id',
            'label' => 'Статус',
            'type' => 'select_from_array',
            'options' => PartItemStatus::query()->pluck('name', 'id'),
            'allows_null' => false,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'condition',
            'label' => 'Состояние',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'name'      => 'image',
            'label'     => 'Image',
            'type'      => 'upload',
            'withFiles' => true
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

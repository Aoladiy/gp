<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StockMovementRequest;
use App\Models\Part;
use App\Models\PartItem;
use App\Models\StockMovementType;
use App\Models\StorageLocation;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;

/**
 * Class StockMovementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StockMovementCrudController extends CrudController
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
        CRUD::setModel(\App\Models\StockMovement::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/stock-movement');
        CRUD::setEntityNameStrings('stock movement', 'stock movements');
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
            'name' => 'fake',
            'label' => 'Запчасть',
            'type' => 'select',
            'entity' => 'partItem.part',
            'attribute' => 'name',
            'model' => Part::class,
        ]);

        $this->crud->addColumn([
            'name' => 'part_item_id',
            'label' => 'Экземпляр',
            'type' => 'select',
            'entity' => 'partItem',
            'attribute' => 'serial_number',
            'model' => PartItem::class,
        ]);

        $this->crud->addColumn([
            'name' => 'stock_movement_type_id',
            'label' => 'Тип движения',
            'type' => 'select',
            'entity' => 'stockMovementType',
            'attribute' => 'name',
            'model' => StockMovementType::class,
        ]);

        $this->crud->addColumn([
            'name' => 'from_location_id',
            'label' => 'Из локации',
            'type' => 'select',
            'entity' => 'fromLocation',
            'attribute' => 'name',
            'model' => StorageLocation::class,
        ]);

        $this->crud->addColumn([
            'name' => 'to_location_id',
            'label' => 'В локацию',
            'type' => 'select',
            'entity' => 'toLocation',
            'attribute' => 'name',
            'model' => StorageLocation::class,
        ]);

        $this->crud->addColumn([
            'name' => 'user_id',
            'label' => 'Ответственный',
            'type' => 'select',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => User::class,
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
        CRUD::setValidation(StockMovementRequest::class);

        $this->crud->addField([
            'name' => 'user_id',
            'type' => 'hidden',
            'value' => backpack_auth()->user()->id,
        ]);

        $this->crud->addField([
            'name' => 'part_item_id',
            'label' => 'Запчасть',
            'type' => 'select_from_array',
            'options' => PartItem::query()->pluck('serial_number', 'id'),
            'allows_null' => true,
            'default' => null,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'stock_movement_type_id',
            'label' => 'Тип движения',
            'type' => 'select_from_array',
            'options' => StockMovementType::query()->pluck('name', 'id'),
            'allows_null' => true,
            'default' => null,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'from_location_id',
            'label' => 'Из локации',
            'type' => 'select_from_array',
            'options' => StorageLocation::query()->pluck('name', 'id'),
            'allows_null' => true,
            'default' => null,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'to_location_id',
            'label' => 'Из локации',
            'type' => 'select_from_array',
            'options' => StorageLocation::query()->pluck('name', 'id'),
            'allows_null' => true,
            'default' => null,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'moved_at',
            'label' => 'Дата и время поступления',
            'type' => 'datetime',
            'default' => Carbon::now(),
        ]);

        $this->crud->addField([
            'name' => 'note',
            'label' => 'Примечания',
            'type' => 'textarea',
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

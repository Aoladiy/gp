<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PermissionsEnum;
use App\Http\Requests\StorageLocationRequest;
use App\Models\StorageLocation;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

/**
 * Class StorageLocationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StorageLocationCrudController extends BaseCrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;

    public static function getPermissionEnum(): PermissionsEnum
    {
        return PermissionsEnum::STORAGE;
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup(): void
    {
        parent::setup();
        CRUD::setModel(StorageLocation::class);
        $this->crud->allowAccess('nested_storage_locations');
        $this->crud->allowAccess('related_storage_requirements');
        $parent_id = Route::current()->parameter('parent_id');
        if ($parent_id) {
            CRUD::setRoute(config('backpack.base.route_prefix') . '/storage-location/' . $parent_id . '/storage-location');
            $this->crud->addClause('where', 'parent_id', $parent_id);
            $this->crud->allowAccess('nested_storage_locations_back');
        } else {
            CRUD::setRoute(config('backpack.base.route_prefix') . '/storage-location');
            $this->crud->addClause('where', 'parent_id', null);
        }
        CRUD::setEntityNameStrings('Складская локация', 'Складские локации');
        /** @var StorageLocation $currentEntry */
        if ($currentEntryId = $this->crud->getCurrentEntryId()) {
            $currentEntry = $this->crud->getModel()::query()->find($currentEntryId);
            $parents = collect();
            /** @var StorageLocation|null $parent */
            $parent = $currentEntry->parent;
            while ($parent) {
                $parents->prepend([$parent->name => backpack_url('/storage-location/' . $parent->id . '/storage-location')]);
                $parent = $parent->parent;
            }
            $parents->prepend(['Складские локации' => backpack_url('storage-location')]);
            $parents->prepend([trans('backpack::crud.admin') => backpack_url('dashboard')]);
            $parents->add([trans('backpack::crud.list') => false]);
            $parents = $parents->mapWithKeys(fn($breadcrumb, $key) => $breadcrumb);
            $this->data['breadcrumbs'] = $parents->toArray();
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation(): void
    {
        if ($this->crud->hasAccess('nested_storage_locations_back')) {
            $this->crud->addButtonFromView('top', 'nested_storage_locations_back', 'nested_storage_locations_back');
        }
        if ($this->crud->hasAccess('related_storage_requirements')) {
            $this->crud->addButtonFromView('line', 'related_storage_requirements', 'related_storage_requirements');
        }
        $this->crud->addButtonFromView('line', 'nested_storage_locations', 'nested_storage_locations', 'beginning');

        $this->crud->addColumn('id');
        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Название',
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
        CRUD::setValidation(StorageLocationRequest::class);

        $this->crud->addField([
            'name' => 'parent_id',
            'type' => 'hidden',
            'value' => Route::current()->parameter('parent_id'),
        ]);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Название',
            'type' => 'text',
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PartRequest;
use App\Models\PartTemplate;
use App\Models\RotationMethod;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PartCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PartCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Part::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/part');
        CRUD::setEntityNameStrings('part', 'parts');
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
            'name' => 'template_id',
            'label' => 'Шаблон',
            'type' => 'select',
            'entity' => 'partTemplate',
            'attribute' => 'name',
            'model' => PartTemplate::class,
        ]);
        $this->crud->addColumn('name');
        $this->crud->addColumn('article_number');
        $this->crud->addColumn([
            'name' => 'rotation_number_id',
            'label' => 'Метод Ротации',
            'type' => 'select',
            'entity' => 'rotationMethod',
            'attribute' => 'name',
            'model' => RotationMethod::class,
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
        CRUD::setValidation(PartRequest::class);

        $this->crud->addField([
            'name' => 'template_id',
            'label' => 'Шаблон запчасти',
            'type' => 'select_from_array',
            'options' => PartTemplate::query()->pluck('name', 'id'),
            'allows_null' => true,
            'default' => null,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Название',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'article_number',
            'label' => 'Артикль',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => 'Описание',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'name' => 'rotation_method_id',
            'label' => 'Метод ротации',
            'type' => 'select_from_array',
            'options' => RotationMethod::query()->pluck('name', 'id'),
            'allows_null' => true,
            'default' => null,
            'allows_multiple' => false,
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

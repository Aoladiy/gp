<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PartTemplateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

/**
 * Class PartTemplateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PartTemplateCrudController extends CrudController
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
    public function setup()
    {
        CRUD::setModel(\App\Models\PartTemplate::class);
        $this->crud->allowAccess('nested_part_templates');
        $parent_id = Route::current()->parameter('parent_id');
        if ($parent_id) {
            CRUD::setRoute(config('backpack.base.route_prefix') . '/part-template/' . $parent_id . '/part-template');
            $this->crud->addClause('where', 'parent_id', $parent_id);
            $this->crud->allowAccess('nested_part_templates_back');
        } else {
            CRUD::setRoute(config('backpack.base.route_prefix') . '/part-template');
            $this->crud->addClause('where', 'parent_id', null);
        }
        CRUD::setEntityNameStrings('part template', 'part templates');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation(): void
    {
        if ($this->crud->hasAccess('nested_part_templates_back')) {
            $this->crud->addButtonFromView('top', 'nested_part_templates_back', 'nested_part_templates_back');
        }
        $this->crud->addButtonFromView('line', 'nested_part_templates', 'nested_part_templates', 'beginning');

        $this->crud->addColumn('id');
        $this->crud->addColumn('name');

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
        CRUD::setValidation(PartTemplateRequest::class);

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

        $this->crud->addField([
            'name' => 'description',
            'label' => 'Описание',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => 'Описание',
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

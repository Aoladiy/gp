<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PermissionsEnum;
use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use App\Models\Part;
use App\Models\PartTemplate;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

/**
 * Class EquipmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EquipmentCrudController extends BaseCrudController
{
    use ListOperation;
    use CreateOperation {
        CreateOperation::store as traitStore;
    }
    use UpdateOperation {
        UpdateOperation::update as traitUpdate;
    }
    use DeleteOperation;
    use ShowOperation;

    public static function getPermissionEnum(): PermissionsEnum
    {
        return PermissionsEnum::EQUIPMENT;
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup(): void
    {
        parent::setup();
        CRUD::setModel(Equipment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/equipment');
        CRUD::setEntityNameStrings('Техника', 'Техника');
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
        CRUD::setValidation(EquipmentRequest::class);
        /** @var ?Equipment $currentEntry */
        $currentEntry = $this->crud->getCurrentEntry();

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Название',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'requiredPartTemplatesFake',
            'label' => 'Требуемые типы запчастей',
            'type' => 'select_from_array',
            'options' => [null => '-'] + PartTemplate::query()->pluck('name', 'id')->toArray(),
            'default' => $currentEntry
                ? $currentEntry->requiredPartTemplates()->pluck('part_templates.id')->toArray()
                : [],
            'allows_multiple' => true,
        ]);

        $this->crud->addField([
            'name' => 'compatiblePartsFake',
            'label' => 'Совместимые запчасти',
            'type' => 'select_from_array',
            'options' => [null => '-'] + Part::query()->pluck('name', 'id')->toArray(),
            'default' => $currentEntry
                ? $currentEntry->compatibleParts()->pluck('parts.id')->toArray()
                : [],
            'allows_multiple' => true,
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

    /**
     * @param Equipment $entry
     * @return void
     */
    protected function handleRequiredPartTemplates(Equipment $entry): void
    {
        $selected = request()->input('requiredPartTemplatesFake', []);
        $selected = is_null($selected) ? $selected : array_filter($selected, fn($item) => !is_null($item));

        $entry->requiredPartTemplates()->sync($selected);
    }

    /**
     * @param Equipment $entry
     * @return void
     */
    protected function handleCompatibleParts(Equipment $entry): void
    {
        $selected = request()->input('compatiblePartsFake', []);
        $selected = is_null($selected) ? $selected : array_filter($selected, fn($item) => !is_null($item));

        $entry->compatibleParts()->sync($selected);
    }

    /**
     * @return RedirectResponse
     */
    public function store(): RedirectResponse
    {
        $response = $this->traitStore();
        $this->handleRequiredPartTemplates($this->crud->entry);
        $this->handleCompatibleParts($this->crud->entry);
        return $response;
    }

    /**
     * @return JsonResponse|RedirectResponse
     */
    public function update(): JsonResponse|RedirectResponse
    {
        $response = $this->traitUpdate();
        $this->handleRequiredPartTemplates($this->crud->entry);
        $this->handleCompatibleParts($this->crud->entry);
        return $response;
    }

}

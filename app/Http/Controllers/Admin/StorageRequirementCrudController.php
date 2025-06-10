<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PermissionsEnum;
use App\Http\Requests\StorageRequirementRequest;
use App\Models\LightingLevel;
use App\Models\Part;
use App\Models\PartItem;
use App\Models\StorageLocation;
use App\Models\StorageRequirement;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Exception;
use Illuminate\Support\Str;

/**
 * Class StorageRequirementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StorageRequirementCrudController extends BaseCrudController
{
    use CreateOperation;
    use UpdateOperation;

    public static function getPermissionEnum(): PermissionsEnum
    {
        return PermissionsEnum::STORAGE;
    }

    /**
     * @var int
     */
    protected int $requireable_id;
    /**
     * @var string
     */
    protected string $requireable_model;
    /**
     * @var string
     */
    protected string $route_path;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     * @throws Exception
     */
    public function setup(): void
    {
        parent::setup();
        CRUD::setModel(StorageRequirement::class);
        $this->loadParameters();
        $requireableId = $this->getRequireableId();

        if (!empty($requireableId)) {
            $this->crud->query
                ->where('requireable_type', $this->getRequireableModel())
                ->where('requireable_id', $requireableId);
            CRUD::setRoute(config('backpack.base.route_prefix') . '/' . $this->getRoutePath() . '/' . $requireableId . '/storage-requirement');
        } else {
            CRUD::setRoute(config('backpack.base.route_prefix') . '/storage-requirement');
        }
        if ($this->getRoutePath() === 'part') {
            CRUD::setEntityNameStrings('Требования к хранению', 'Требования к хранению');
        } else {
            CRUD::setEntityNameStrings('Складские условия', 'Складские условия');
        }

        $this->data['breadcrumbs'] = ['Назад' => backpack_url($this->getRoutePath())];
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(StorageRequirementRequest::class);

        $this->crud->removeSaveAction('save_and_new');

        $this->crud->addField([
            'name' => 'requireable_id',
            'type' => 'hidden',
            'value' => $this->getRequireableId(),
        ]);

        $this->crud->addField([
            'name' => 'requireable_type',
            'type' => 'hidden',
            'value' => $this->getRequireableModel(),
        ]);

        $this->crud->addField([
            'name' => 'temperature_min',
            'label' => 'Минимальная температура',
            'type' => 'number',
            'attributes' => ['step' => 0.1],
        ]);

        $this->crud->addField([
            'name' => 'temperature_max',
            'label' => 'Максимальная температура',
            'type' => 'number',
            'attributes' => ['step' => 0.1],
        ]);

        $this->crud->addField([
            'name' => 'humidity_min',
            'label' => 'Минимальная влажность',
            'type' => 'number',
            'attributes' => ['step' => 0.1],
        ]);

        $this->crud->addField([
            'name' => 'humidity_max',
            'label' => 'Максимальная влажность',
            'type' => 'number',
            'attributes' => ['step' => 0.1],
        ]);

        $this->crud->addField([
            'name' => 'lighting_level_id',
            'label' => 'Уровень освещения',
            'type' => 'select_from_array',
            'options' => LightingLevel::query()->pluck('name', 'id'),
            'allows_null' => true,
            'default' => null,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'ventilation_level',
            'label' => 'Уровень вентиляции',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'fire_safety_class',
            'label' => 'Уровень пожарной безопасности',
            'type' => 'text',
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

    /**
     * @return void
     * @throws Exception
     */
    protected function loadParameters(): void
    {
        $this->requireable_id = request()->storage_requireable_id ?? null;

        $url = request()->path();

        if (Str::startsWith($url, 'admin/part')) {
            $this->requireable_model = Part::class;
            $this->route_path = 'part';
            return;
        }

        if (Str::startsWith($url, 'admin/part-item')) {
            $this->requireable_model = PartItem::class;
            $this->route_path = 'part-item';
            return;
        }

        if (Str::startsWith($url, 'admin/storage-location')) {
            $this->requireable_model = StorageLocation::class;
            $this->route_path = 'storage-location';
            return;
        }

        throw new Exception('Unknown url:' . $url);
    }

    /**
     * @return int
     */
    public function getRequireableId(): int
    {
        return $this->requireable_id;
    }

    /**
     * @return string
     */
    public function getRequireableModel(): string
    {
        return $this->requireable_model;
    }

    /**
     * @return string
     */
    public function getRoutePath(): string
    {
        return $this->route_path;
    }
}

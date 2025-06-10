<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PermissionsEnum;
use App\Exceptions\StorageLocationException;
use App\Http\Requests\PartBatchRequest;
use App\Models\Part;
use App\Models\PartBatch;
use App\Models\PartItem;
use App\Models\PartItemStatus;
use App\Models\StorageLocation;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class PartBatchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PartBatchCrudController extends BaseCrudController
{
    use ListOperation;
    use CreateOperation {
        CreateOperation::store as traitStore;
    }
    use UpdateOperation {
        UpdateOperation::update as traitUpdate;
    }
    use DeleteOperation {
        DeleteOperation::destroy as traitDestroy;
    }
    use ShowOperation;

    public static function getPermissionEnum(): PermissionsEnum
    {
        return PermissionsEnum::PARTS;
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup(): void
    {
        parent::setup();
        CRUD::setModel(PartBatch::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/part-batch');
        CRUD::setEntityNameStrings('Партия запчастей', 'Партии запчастей');
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
        $this->crud->addColumn([
            'name' => 'batch_number',
            'label' => 'Номер партии',
        ]);
        $this->crud->addColumn([
            'name' => 'received_at',
            'label' => 'Когда получено',
        ]);
        $this->crud->addColumn([
            'name' => 'expiry_date',
            'label' => 'Годен до',
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
        CRUD::setValidation(PartBatchRequest::class);
        /** @var PartBatch|false $currentEntry */
        $currentEntry = $this->crud->getCurrentEntry();
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
            'default' => Carbon::now(),
        ]);

        $this->crud->addField([
            'name' => 'expiry_date',
            'label' => 'Годен до',
            'type' => 'datetime',
        ]);

        $this->crud->addField([
            'name' => 'quantity',
            'label' => 'Количество',
            'type' => 'number',
            'attributes' => ['step' => 1],
        ]);

        $this->crud->addField([
            'name' => 'storage_location_id',
            'label' => 'Складская локация',
            'type' => 'select_from_array',
            'options' => StorageLocation::query()->pluck('name', 'id'),
            'allows_null' => true,
            'default' => $currentEntry && $currentEntry->hasPartItems() ? $currentEntry->partItems()->value('storage_location_id') : null,
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
        $this->crud->removeField('quantity');
    }

    /**
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(): RedirectResponse
    {
        try {
            return DB::transaction(function () {
                $quantity = request()->input('quantity', 0);
                $storageLocationId = request()->input('storage_location_id');
                $status = PartItemStatus::query()->where('name', '=', 'В наличии')->value('id');

                if (!$status) {
                    throw new Exception('PartItemStatus with name "В наличии" не найден.');
                }

                $response = $this->traitStore();
                $partBatchId = $this->crud->getCurrentEntryId();

                if ($quantity > 0) {
                    for ($i = 0; $i < $quantity; $i++) {
                        PartItem::query()
                            ->create([
                                'part_id' => request()->input('part_id'),
                                'part_batch_id' => $partBatchId,
                                'storage_location_id' => $storageLocationId,
                                'status_id' => $status,
                            ]);
                    }
                }

                return $response;
            });
        } catch (StorageLocationException $e) {
            return back()
                ->withErrors(['storage_location' => 'Неудовлетворены требования хранения: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * @return JsonResponse|RedirectResponse
     */
    public function update(): JsonResponse|RedirectResponse
    {
        try {
            return DB::transaction(function () {
                $response = $this->traitUpdate();

                $partBatchId = $this->crud->getCurrentEntryId();
                $storageLocationId = request()->input('storage_location_id');
                $inStockStatusId = PartItemStatus::query()->where('name', '=', 'В наличии')->valueOrFail('id');
                $writtenOffStatusId = PartItemStatus::query()->where('name', '=', 'Списано')->valueOrFail('id');

                PartItem::query()
                    ->where('part_batch_id', '=', $partBatchId)
                    ->get()
                    ->each(function (PartItem $item) use ($writtenOffStatusId, $inStockStatusId, $storageLocationId) {
                        $item->storage_location_id = $storageLocationId;
                        $item->status_id = $storageLocationId ? $inStockStatusId : $writtenOffStatusId;
                        $item->save();
                    });

                return $response;
            });
        } catch (StorageLocationException $e) {
            return back()
                ->withErrors(['storage_location' => 'Неудовлетворены требования хранения: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * @param int $id
     * @return string
     */
    public function destroy(int $id): string
    {
        try {
            return DB::transaction(function () {
                $partBatchId = $this->crud->getCurrentEntryId();
                $statusId = PartItemStatus::query()->where('name', '=', 'Списано')->valueOrFail('id');
                PartItem::query()
                    ->where('part_batch_id', '=', $partBatchId)
                    ->get()
                    ->each(function (PartItem $item) use ($statusId) {
                        $item->storage_location_id = null;
                        $item->status_id = $statusId;
                        $item->save();
                    });
                return $this->traitDestroy($partBatchId);
            });
        } catch (StorageLocationException $e) {
            return back()
                ->withErrors(['storage_location' => 'Неудовлетворены требования хранения: ' . $e->getMessage()])
                ->withInput();
        }
    }
}

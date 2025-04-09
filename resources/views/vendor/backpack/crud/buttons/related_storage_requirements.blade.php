@if ($crud->hasAccess('related_storage_requirements'))
    @php
    $urlStart = match ($entry::class) {
        \App\Models\Part::class => 'admin/part/',
        \App\Models\PartItem::class => 'admin/part-item/',
        \App\Models\StorageLocation::class => 'admin/storage-location/',
        default => throw new Exception('Unknown storage requireable class: ' . $entry::class),
    };
        if ($entry->hasStorageRequirements()) {
            $url = $urlStart . $entry->id . "/storage-requirement/" . $entry->storageRequirements()->value('id') . "/edit";
        } else {
            $url = $urlStart . $entry->id . "/storage-requirement/create";
        }
    @endphp
    <a href="{{ url($url) }}"
       class="btn btn-sm btn-link"> Требования к хранению <i class="la la-arrow-right"></i></a>
@endif

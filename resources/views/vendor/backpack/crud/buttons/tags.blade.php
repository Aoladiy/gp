@if ($crud->hasAccess('related_storage_requirements'))
    @php
        $urlStart = match ($entry::class) {
            \App\Models\Equipment::class => 'admin/equipment/',
            \App\Models\Part::class => 'admin/part/',
            \App\Models\PartItem::class => 'admin/part-item/',
            \App\Models\PartTemplate::class => 'admin/part-template/',
            \App\Models\StorageLocation::class => 'admin/storage-location/',
            default => throw new Exception('Unknown storage taggable class: ' . $entry::class),
        };
            if ($entry->hasTags()) {
                $url = $urlStart . $entry->id . "/tags/" . $entry->tags()->value('id') . "/edit";
            } else {
                $url = $urlStart . $entry->id . "/tags/create";
            }
    @endphp
    <a href="{{ url($url) }}"
       class="btn btn-sm btn-link"> Требования к хранению <i class="la la-arrow-right"></i></a>
@endif

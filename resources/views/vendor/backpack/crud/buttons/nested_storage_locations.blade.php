{{--@if ($crud->hasAccess('nested_storage_locations'))--}}
{{--  <a href="{{ url($crud->route.'/'.$entry->getKey().'/nested_storage_locations') }}" class="btn btn-sm btn-link text-capitalize"><i class="la la-question"></i> nested_storage_locations</a>--}}
{{--@endif--}}
@if ($crud->hasAccess('nested_storage_locations'))
    @php
        $parent_id = \Illuminate\Support\Facades\Route::current()->parameter('parent_id');
    @endphp
    <a href="{{ url($parent_id ? "admin/storage-location/{$entry->getKey()}/storage-location" : $crud->route.'/'.$entry->getKey().'/storage-location') }}"
       class="btn btn-sm btn-link text-capitalize"><i class="la la-arrow-right"></i> вложенные складские локации</a>
@endif

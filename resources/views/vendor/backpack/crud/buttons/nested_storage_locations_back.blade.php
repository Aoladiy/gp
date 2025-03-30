{{--@if ($crud->hasAccess('nested_storage_locations_back'))--}}
{{--  <a href="{{ url($crud->route.'/'.$entry->getKey().'/nested_storage_locations_back') }}" class="btn btn-sm btn-link text-capitalize"><i class="la la-question"></i> nested_storage_locations_back</a>--}}
{{--@endif--}}
@if ($crud->hasAccess('nested_storage_locations_back'))
    @php
        $parent_id = \Illuminate\Support\Facades\Route::current()->parameter('parent_id');
        $parent_model = $crud->getModel()::class::find($parent_id);
        if ($parent_model->hasParent()) {
            $url = "admin/storage-location/{$parent_model->parent->id}/storage-location";
        } else {
            $url = "admin/storage-location/";
        }
    @endphp
    <a href="{{ url($url) }}"
       class="btn btn-primary text-capitalize"><i class="la la-arrow-left"></i> назад к {{$parent_model->name}}</a>
@endif

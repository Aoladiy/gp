@if ($crud->hasAccess('nested_part_templates'))
    @php
        $parent_id = \Illuminate\Support\Facades\Route::current()->parameter('parent_id');
    @endphp
    <a href="{{ url($parent_id ? "admin/part-template/{$entry->getKey()}/part-template" : $crud->route.'/'.$entry->getKey().'/part-template') }}"
       class="btn btn-sm btn-link text-capitalize"><i class="la la-arrow-right"></i> вложенные шаблоны запчасти</a>
@endif

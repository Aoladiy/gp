@if ($crud->hasAccess('nested_part_templates_back'))
    @php
        $parent_id = \Illuminate\Support\Facades\Route::current()->parameter('parent_id');
        $parent_model = $crud->getModel()::class::find($parent_id);
        if ($parent_model->hasParent()) {
            $url = "admin/part-template/{$parent_model->parent->id}/part-template";
        } else {
            $url = "admin/part-template/";
        }
    @endphp
    <a href="{{ url($url) }}"
       class="btn btn-primary text-capitalize"><i class="la la-arrow-left"></i> назад к {{$parent_model->name}}</a>
@endif

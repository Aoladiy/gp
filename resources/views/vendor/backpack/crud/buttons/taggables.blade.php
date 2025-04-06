@if ($crud->hasAccess('taggables'))
  <a href="{{ url($crud->route.'/'.$entry->getKey().'/taggables') }}" class="btn btn-sm btn-link text-capitalize"><i class="la la-question"></i> taggables</a>
@endif
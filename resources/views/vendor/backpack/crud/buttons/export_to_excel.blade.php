@if ($crud->hasAccess('exportToExcel'))
  <a href="{{ url($crud->route.'/export-to-excel') }}" class="btn btn-sm btn-link text-capitalize"><i class="la la-question"></i> Экспорт в Excel</a>
@endif

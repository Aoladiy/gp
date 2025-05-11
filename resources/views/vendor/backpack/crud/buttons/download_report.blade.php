@if ($crud->hasAccess('downloadReport'))
  <a href="{{ $entry->download_url }}" class="btn btn-sm btn-link text-capitalize"><i class="la la-question"></i> Скачать отчет</a>
@endif

@php use Illuminate\Support\Str; @endphp
{{-- This file is used for menu items by any Backpack v6 theme --}}
<x-backpack::menu-item title="{{ Str::ucfirst(trans('backpack::base.dashboard')) }}" icon="la la-home" :link="backpack_url('dashboard')" />

<x-backpack::menu-item title="{{ Str::ucfirst(trans('backpack::settings.setting_plural')) }}" icon="la la-cog" :link="backpack_url('setting')" />

<x-backpack::menu-dropdown title="Авторизация" icon="la la-puzzle-piece">
{{--    <x-backpack::menu-dropdown-header title="Авторизация" />--}}
    <x-backpack::menu-dropdown-item title="{{ Str::ucfirst(trans('backpack::permissionmanager.users')) }}" icon="la la-user" :link="backpack_url('user')" />
    <x-backpack::menu-dropdown-item title="{{ Str::ucfirst(trans('backpack::permissionmanager.roles')) }}" icon="la la-group" :link="backpack_url('role')" />
    <x-backpack::menu-dropdown-item title="{{ Str::ucfirst(trans('backpack::permissionmanager.permission_plural')) }}" icon="la la-key" :link="backpack_url('permission')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-item title="Part templates" icon="la la-question" :link="backpack_url('part-template')" />
<x-backpack::menu-item title="Parts" icon="la la-question" :link="backpack_url('part')" />
<x-backpack::menu-item title="Part items" icon="la la-question" :link="backpack_url('part-item')" />
<x-backpack::menu-item title="Part batches" icon="la la-question" :link="backpack_url('part-batch')" />
<x-backpack::menu-item title="Equipment" icon="la la-question" :link="backpack_url('equipment')" />
<x-backpack::menu-item title="Tags" icon="la la-question" :link="backpack_url('tag')" />
<x-backpack::menu-item title="Storage locations" icon="la la-question" :link="backpack_url('storage-location')" />
<x-backpack::menu-item title="Storage zones" icon="la la-question" :link="backpack_url('storage-zone')" />
<x-backpack::menu-item title="Storage movements" icon="la la-question" :link="backpack_url('storage-movement')" />
<x-backpack::menu-item title="Stock movements" icon="la la-question" :link="backpack_url('stock-movement')" />
<x-backpack::menu-item title="Write off reasons" icon="la la-question" :link="backpack_url('write-off-reason')" />
<x-backpack::menu-item title="Part item returns" icon="la la-question" :link="backpack_url('part-item-return')" />

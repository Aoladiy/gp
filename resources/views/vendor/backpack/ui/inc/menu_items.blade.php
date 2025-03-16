@php use Illuminate\Support\Str; @endphp
{{-- This file is used for menu items by any Backpack v6 theme --}}
<x-backpack::menu-item title="{{ Str::ucfirst(trans('backpack::base.dashboard')) }}" icon="la la-home" :link="backpack_url('dashboard')" />

<x-backpack::menu-item title="{{ Str::ucfirst(trans('backpack::settings.setting_plural')) }}" icon="la la-cog" :link="backpack_url('setting')" />

<x-backpack::menu-dropdown title="Авторизация" icon="la la-puzzle-piece">
    <x-backpack::menu-dropdown-header title="Авторизация" />
    <x-backpack::menu-dropdown-item title="{{ Str::ucfirst(trans('backpack::permissionmanager.users')) }}" icon="la la-user" :link="backpack_url('user')" />
    <x-backpack::menu-dropdown-item title="{{ Str::ucfirst(trans('backpack::permissionmanager.roles')) }}" icon="la la-group" :link="backpack_url('role')" />
    <x-backpack::menu-dropdown-item title="{{ Str::ucfirst(trans('backpack::permissionmanager.permission_plural')) }}" icon="la la-key" :link="backpack_url('permission')" />
</x-backpack::menu-dropdown>

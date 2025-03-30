@php use Illuminate\Support\Str; @endphp
{{-- This file is used for menu items by any Backpack v6 theme --}}
<x-backpack::menu-item title="{{ Str::ucfirst(trans('backpack::base.dashboard')) }}" icon="la la-home"
                       :link="backpack_url('dashboard')"/>

<x-backpack::menu-item title="{{ Str::ucfirst(trans('backpack::settings.setting_plural')) }}" icon="la la-cog"
                       :link="backpack_url('setting')"/>

<x-backpack::menu-dropdown title="Авторизация" icon="la la-puzzle-piece">
    {{--    <x-backpack::menu-dropdown-header title="Авторизация" />--}}
    <x-backpack::menu-dropdown-item title="{{ Str::ucfirst(trans('backpack::permissionmanager.users')) }}"
                                    icon="la la-user" :link="backpack_url('user')"/>
    <x-backpack::menu-dropdown-item title="{{ Str::ucfirst(trans('backpack::permissionmanager.roles')) }}"
                                    icon="la la-group" :link="backpack_url('role')"/>
    <x-backpack::menu-dropdown-item title="{{ Str::ucfirst(trans('backpack::permissionmanager.permission_plural')) }}"
                                    icon="la la-key" :link="backpack_url('permission')"/>
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Запчасти" icon="la la-puzzle-piece">
    <x-backpack::menu-dropdown-item title="Шаблоны запчастей" icon="la la-question"
                                    :link="backpack_url('part-template')"/>
    <x-backpack::menu-dropdown-item title="Запчасти" icon="la la-question" :link="backpack_url('part')"/>
    <x-backpack::menu-dropdown-item title="Экземпляры запчастей" icon="la la-question"
                                    :link="backpack_url('part-item')"/>
    <x-backpack::menu-dropdown-item title="Партии запчастей" icon="la la-question" :link="backpack_url('part-batch')"/>
    <x-backpack::menu-dropdown-item title="Методы ротации" icon="la la-question"
                                    :link="backpack_url('rotation-method')"/>
    <x-backpack::menu-dropdown-item title="Уровни освещения" icon="la la-question"
                                    :link="backpack_url('lighting-level')"/>
</x-backpack::menu-dropdown>
<x-backpack::menu-item title="Техника" icon="la la-question" :link="backpack_url('equipment')"/>
<x-backpack::menu-item title="Теги" icon="la la-question" :link="backpack_url('tag')"/>
<x-backpack::menu-dropdown title="Склад" icon="la la-puzzle-piece">
    <x-backpack::menu-dropdown-item title="Складские локации" icon="la la-question"
                                    :link="backpack_url('storage-location')"/>
    <x-backpack::menu-dropdown-item title="Внутрискладские движения" icon="la la-question"
                                    :link="backpack_url('storage-movement')"/>
    <x-backpack::menu-dropdown-item title="Складские движения" icon="la la-question"
                                    :link="backpack_url('stock-movement')"/>
</x-backpack::menu-dropdown>
<x-backpack::menu-item title="Причины списания" icon="la la-question" :link="backpack_url('write-off-reason')"/>
<x-backpack::menu-item title="Возврат экземпляров запчастей" icon="la la-question"
                       :link="backpack_url('part-item-return')"/>

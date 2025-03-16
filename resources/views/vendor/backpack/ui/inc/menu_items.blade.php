@php use Illuminate\Support\Str; @endphp
{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i>
        {{ Str::ucfirst(trans('backpack::base.dashboard')) }}
    </a>
</li>

<li class='nav-item'>
    <a class='nav-link' href='{{ backpack_url('setting') }}'>
        <i class='nav-icon la la-cog'></i>
        <span>{{ Str::ucfirst(trans('backpack::settings.setting_plural')) }}</span>
    </a>
</li>

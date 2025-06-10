<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PermissionsEnum;
use Backpack\PermissionManager\app\Http\Controllers\RoleCrudController as BaseRoleCrudController;

class RoleCrudController extends BaseRoleCrudController
{
    public static function getPermissionEnum(): PermissionsEnum
    {
        return PermissionsEnum::AUTHORIZATION;
    }

    public function setup(): void
    {
        $permission = static::getPermissionEnum()->value;
        $user = backpack_user();

        if (!$user || !$user->can($permission)) {
            abort(403, 'Вы не авторизованы для доступа к этому разделу.');
        }
        parent::setup();
    }
}

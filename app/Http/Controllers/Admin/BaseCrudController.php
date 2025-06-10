<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PermissionsEnum;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Prologue\Alerts\Facades\Alert;

abstract class BaseCrudController extends CrudController
{
    abstract public static function getPermissionEnum(): PermissionsEnum;

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

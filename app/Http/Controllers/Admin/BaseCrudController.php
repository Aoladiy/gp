<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PermissionsEnum;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Prologue\Alerts\Facades\Alert;

abstract class BaseCrudController extends CrudController
{
    abstract public static function getPermissionEnum(): PermissionsEnum;

    public function __construct()
    {
        parent::__construct();
        if (!backpack_user()->can(static::getPermissionEnum()->value)) {
            Alert::error('Вы не авторизованы для этого действия')->flash();
            redirect('dashboard')->send();
        }
    }
}

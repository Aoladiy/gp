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
        $this->middleware('crud.permission');
        parent::setup();
    }
}

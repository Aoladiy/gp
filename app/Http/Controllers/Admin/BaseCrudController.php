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
        $this->middleware(function ($request, $next) {
            $user = backpack_user();

            if (!$user || !$user->can(static::getPermissionEnum()->value)) {
                Alert::error('Вы не авторизованы для этого действия')->flash();
                return redirect('dashboard');
            }

            return $next($request);
        });
        parent::setup();
    }
}

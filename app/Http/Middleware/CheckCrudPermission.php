<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class CheckCrudPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $controller = $request->route()?->controller;

        if (!method_exists($controller, 'getPermissionEnum')) {
            // Если контроллер не использует механизм прав — пропускаем
            return $next($request);
        }

        $permission = $controller::getPermissionEnum()->value;
        $user = backpack_user();

        if (!$user || !$user->can($permission)) {
            Alert::error('Вы не авторизованы для доступа к этому разделу')->flash();
            return redirect(route( 'backpack.dashboard'));
        }

        return $next($request);
    }
}

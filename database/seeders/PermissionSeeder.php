<?php

namespace Database\Seeders;

use App\Enum\PermissionsEnum;
use App\Models\User;
use Backpack\PermissionManager\app\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Admin = User::query()->where('name', '=', 'Admin')->first();
        $ReportOnlyUser = User::query()->where('name', '=', 'ReportUser')->first();
        $StorageManager = User::query()->where('name', '=', 'StorageManager')->first();
        foreach (PermissionsEnum::cases() as $permissionsEnum) {
            Permission::create([
                'name' => $permissionsEnum->value,
                'guard_name' => 'backpack',
            ]);
            $Admin->givePermissionTo(Permission::query()->where('name', $permissionsEnum->value)->first());
        }
        $ReportOnlyUser->givePermissionTo(Permission::query()->where('name', PermissionsEnum::REPORTS->value)->first());

        $StorageManager->givePermissionTo(Permission::query()->where('name', PermissionsEnum::STORAGE->value)->first());
        $StorageManager->givePermissionTo(Permission::query()->where('name', PermissionsEnum::PARTS->value)->first());
        $StorageManager->givePermissionTo(Permission::query()->where('name', PermissionsEnum::EQUIPMENT->value)->first());
    }
}

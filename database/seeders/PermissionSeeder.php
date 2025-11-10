<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPermissionArray = config('acl.permissions');

        $updateAblePermissions = [
            'admin.ticketissuetype.index' => 'admin.ticketIssueType.index',
            'admin.ticketissuetype.create' => 'admin.ticketIssueType.create',
            'admin.ticketissuetype.edit' => 'admin.ticketIssueType.edit',
            'admin.ticketissuetype.toggle' => 'admin.ticketIssueType.toggle',
            'admin.ticketissuetype.delete' => 'admin.ticketIssueType.delete',
        ];

        foreach ($updateAblePermissions as $oldPermission => $newPermission) {
            Permission::where('name', $oldPermission)
                ->update(['name' => $newPermission]);
        }

        foreach ($allPermissionArray as $modelType => $allPermissions) {
            $type = $modelType == 'adminMultiShop' ? 'admin' : $modelType;
            $type = $type == 'shopMultiShop' ? 'shop' : $type;

            foreach ($allPermissions as $permissionName => $permissionValues) {
                foreach ($permissionValues as $permission) {
                    Permission::firstOrCreate([
                        'name' => $type.'.'.$permissionName.'.'.$permission,
                        'guard_name' => 'web',
                    ]);
                }
            }
        }

        Artisan::call('cache:clear');
    }
}

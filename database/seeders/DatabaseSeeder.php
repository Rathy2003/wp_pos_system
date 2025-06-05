<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 
        $DeveloperRole = Role::create(['name' => 'developer']);
        $AdminRole = Role::create(['name' => 'admin']);
        $SellerRole = Role::create(['name' => 'seller']);
        $StockHolderRole = Role::create(['name' => 'stockholder']);

        $FullManageProduct = Permission::create(['name' => 'full manage product']);
        $FullManageCategory = Permission::create(['name' => 'full manage category']);
        $FullManageCustomer = Permission::create(['name' => 'full manage customer']);
        $FullManageSale = Permission::create(['name' => 'full manage sale']);
        $FullManageReport = Permission::create(['name' => 'full manage report']);
        $FullManageSetting = Permission::create(['name' => 'full manage setting']);
        $FullManageUser = Permission::create(['name' => 'full manage user']);

        $DeveloperRole->givePermissionTo($FullManageProduct);
        $DeveloperRole->givePermissionTo($FullManageCategory);
        $DeveloperRole->givePermissionTo($FullManageCustomer);
        $DeveloperRole->givePermissionTo($FullManageSale);
        $DeveloperRole->givePermissionTo($FullManageReport);
        $DeveloperRole->givePermissionTo($FullManageSetting);
        $DeveloperRole->givePermissionTo($FullManageUser);

        $AdminRole->givePermissionTo($FullManageProduct);
        $AdminRole->givePermissionTo($FullManageCategory);
        $AdminRole->givePermissionTo($FullManageCustomer);
        $AdminRole->givePermissionTo($FullManageSale);
        $AdminRole->givePermissionTo($FullManageReport);
        $AdminRole->givePermissionTo($FullManageSetting);

        $StockHolderRole->givePermissionTo($FullManageProduct);
        $StockHolderRole->givePermissionTo($FullManageCategory);

        User::factory()->create([
            'name' => 'Admin',
            'is_admin' => 1,
            'email' => 'admin@pos.com',
            'password' => Hash::make('admin@123'),
        ])->assignRole('developer');
    }
}

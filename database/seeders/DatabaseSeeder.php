<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([


            // MsproductTypeSeeder::class,
            StatusSeeder::class,
            MsActionSeeder::class,
            MsPageSeeder::class,
            MsProductCategorySeeder::class,
            MsProductTypeSeeder::class,
            MsBrandSeeder::class,
            MsWarehouseSeeder::class,
            BranchWarehouseSeeder::class,
            MsBranchSeeder::class,
            MsPositionSeeder::class,
            MsCustomerSeeder::class,
            MsCustomerAddressSeeder::class,

            MsEmployeeSeeder::class,
            EmployeePositionSeeder::class,
            UserSeeder::class,
            AccessRightGroupSeeder::class,
            AccessRightPositionSeeder::class,
            AccessRightPositionStatusSeeder::class,
            AccessRightPositionWarehouseSeeder::class,
            AccessRightPositionWarehouseBranchSeeder::class,

            StatusSeeder::class,
            GiftDatabaseSeeder::class,

        ]);
    }
}

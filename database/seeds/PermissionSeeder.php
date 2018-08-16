<?php

use Illuminate\Database\Seeder;

use App\Models\Permission;
use App\Handler\Common;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 房源
        $housePermission = Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源',
            'parent_guid' => '',
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '类型',
            'parent_guid' => $housePermission->guid
        ]);


    }
}

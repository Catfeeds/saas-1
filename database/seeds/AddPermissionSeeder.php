<?php

use Illuminate\Database\Seeder;

use App\Models\Permission;
use App\Handler\Common;

class AddPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 上线房源
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-上线房源',
            'name_en' => 'house_online'
        ]);
    }
}

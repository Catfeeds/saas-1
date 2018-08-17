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
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源',
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-类型'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-业主信息-新增房源的业主信息'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-业主信息-修改/删除房源的业主信息'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-电脑端查看公盘房源业主信息'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-公盘门牌号'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-门牌号'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-小区名称'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-房源等级'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-价格'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-低价'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-其他信息'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-相关人'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-转为无效'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-置顶房源'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-图片'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-证件'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-钥匙'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-委托'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-查看联系方式写跟进'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除跟进'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除房源'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-列表展示'
        ]);

        // 客源
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源',
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-求购客源'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-求租客源'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-客别'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-电脑端公客'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-电脑端抢客'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-私客查看范围'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-联系方式'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-客源等级'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-其他信息'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-录入人'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-维护人'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-转为无效'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-查看联系方式写跟进'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-删除跟进'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-查看带看'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-删除带看'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-删除客源'
        ]);

    }
}

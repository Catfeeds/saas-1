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
            'name_en' => 'Listing'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-类型',
            'name_en' => 'Listing-type'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-私盘房源上限',
            'name_en' => 'Private listing limit'
        ]);


        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-公盘转私盘',
            'name_en' => 'Public disk'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-私盘转公盘',
            'name_en' => 'Private disk transfer'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-业主信息-新增房源的业主信息',
            'name_en' => 'Owner information for new listings'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-业主信息-修改/删除房源的业主信息',
            'name_en' => 'Edit/delete owner information for a listing'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-电脑端查看公盘房源业主信息',
            'name_en' => 'View the public information of the owner of the public on the computer'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '私盘房源业主信息',
            'name_en' => 'Private Listing Owner Information'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '私盘房源门牌号',
            'name_en' => 'Private house number'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-公盘门牌号',
            'name_en' => 'Public house number'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-门牌号',
            'name_en' => 'House number'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-小区名称',
            'name_en' => 'Community name'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-房源等级',
            'name_en' => 'Listing level'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-价格',
            'name_en' => 'price'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-其他信息',
            'name_en' => 'other information'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-录入人',
            'name_en' => 'Entering person'
        ]);


        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-维护人',
            'name_en' => 'Maintainer'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-图片人',
            'name_en' => 'Picture person'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-钥匙人',
            'name_en' => 'Key person'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-修改状态',
            'name_en' => 'Modify status'
        ]);


        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-转为无效审核',
            'name_en' => 'Switch to invalid review'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-置顶房源',
            'name_en' => 'Pinned listing'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-上传图片',
            'name_en' => 'upload image'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-编辑图片',
            'name_en' => 'Edit picture'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除图片',
            'name_en' => 'Delete image'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-上传证件',
            'name_en' => 'Upload document'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-查看证件',
            'name_en' => 'View documents'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除证件',
            'name_en' => 'Delete document'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-提交钥匙',
            'name_en' => 'Submit key'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-编辑退换钥匙',
            'name_en' => 'Edit return key'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-提交.退还钥匙审核',
            'name_en' => 'Submit a refund key review'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-编辑删除委托书',
            'name_en' => 'Edit delete power of attorney'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-查看联系方式写跟进',
            'name_en' => 'View contact information write follow up'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除跟进',
            'name_en' => 'Delete follow up'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除房源',
            'name_en' => 'Delete listing'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-列表展示',
            'name_en' => 'List display'
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

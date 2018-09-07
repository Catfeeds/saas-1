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
            'name_en' => 'house'
        ]);

        // 私盘房源上线
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-私盘房源上线',
            'name_en' => 'private_plate_most'
        ]);

        // 公盘转私盘
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-公盘转为私盘',
            'name_en' => 'public_to_private'
        ]);

        // 私盘转为公盘
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-私盘转为公盘',
            'name_en' => 'private_to_public'
        ]);

        // 房源-业主信息-新增房源的业主信息
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-业主信息-新增房源的业主信息',
            'name_en' => 'add_owner_info'
        ]);

        // 房源-业主信息-修改/删除房源的业主信息
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-业主信息-修改/删除房源的业主信息',
            'name_en' => 'edit_owner_info'
        ]);

        // 电脑端朝查看公盘房源业主信息
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-电脑端朝查看公盘房源业主信息',
            'name_en' => 'computer_view'
        ]);

        // 私盘房源业主信息
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-私盘房源业主信息',
            'name_en' => 'private_house_most'
        ]);

        // 门牌号(修改相关)
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-门牌号',
            'name_en' => 'update_house_number'
        ]);

        // 房源等级(修改相关)
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-房源等级',
            'name_en' => 'update_house_grade'
        ]);

        // 房源价格(修改相关)
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-房源价格',
            'name_en' => 'update_house_price'
        ]);

        // 其他信息(修改相关)
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-其他信息',
            'name_en' => 'update_house_other'
        ]);

        // 录入人
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-录入人',
            'name_en' => 'set_entry_person'
        ]);

        // 维护人
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-维护人',
            'name_en' => 'set_guardian_person'
        ]);

        // 图片人
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-图片人',
            'name_en' => 'set_pic_person'
        ]);

        // 钥匙人
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-钥匙人',
            'name_en' => 'set_key_person'
        ]);

        // 修改状态(无效有效转换)
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-修改状态',
            'name_en' => 'update_house_status'
        ]);

        // 转为无效审核
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-转为无效审核',
            'name_en' => 'turned_invalid_review'
        ]);

        // 编辑房源
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-编辑房源',
            'name_en' => 'edit_house'
        ]);

        // 置顶房源
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-置顶房源/取消置顶',
            'name_en' => 'set_top'
        ]);

        // 上传图片
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-上传图片',
            'name_en' => 'upload_pic'
        ]);

        // 编辑图片
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-编辑图片',
            'name_en' => 'edit_pic'
        ]);

        // 删除图片
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除图片',
            'name_en' => 'del_pic'
        ]);

        // 上传证件
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-上传证件',
            'name_en' => 'upload_document'
        ]);

        // 查看证件
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-查看证件',
            'name_en' => 'see_documents'
        ]);

        // 删除证件
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除证件',
            'name_en' => 'del_documents'
        ]);

        // 提交钥匙
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-提交钥匙',
            'name_en' => 'submit_key'
        ]);

        // 编辑/退换钥匙
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-编辑退换钥匙',
            'name_en' => 'edit_return_key'
        ]);

        // 提交/退还钥匙审核
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-提交退还钥匙审核',
            'name_en' => 'submit_key_review'
        ]);

        // 查看联系方式写跟进
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-查看联系方式写跟进',
            'name_en' => 'write_follow_up'
        ]);

        // 删除跟进
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除跟进',
            'name_en' => 'del_track'
        ]);

        // 删除房源
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除房源',
            'name_en' => 'del_house'
        ]);

        // 列表展示
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-列表展示',
            'name_en' => 'house_list'
        ]);
    }
}

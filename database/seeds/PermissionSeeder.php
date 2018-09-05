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
            'name_en' => 'listing'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-类型',
            'name_en' => 'listing_type'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-私盘房源上限',
            'name_en' => 'private_listing_limit'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-公盘转私盘',
            'name_en' => 'public_disk'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-私盘转公盘',
            'name_en' => 'private_disk_transfer'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-业主信息-新增房源的业主信息',
            'name_en' => 'add_owner_information'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-业主信息-修改/删除房源的业主信息',
            'name_en' => 'edit_owner_information'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-电脑端查看公盘房源业主信息',
            'name_en' => 'computer_view'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '私盘房源业主信息',
            'name_en' => 'private_owner_information'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '私盘房源门牌号',
            'name_en' => 'private_house_number'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-公盘门牌号',
            'name_en' => 'public_house_number'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-门牌号',
            'name_en' => 'house_number'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-小区名称',
            'name_en' => 'community_name'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-房源等级',
            'name_en' => 'listing_level'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-价格',
            'name_en' => 'house_price'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-其他信息',
            'name_en' => 'other_information'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-录入人',
            'name_en' => 'entering_person'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-维护人',
            'name_en' => 'maintainer'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-图片人',
            'name_en' => 'picture_person'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-钥匙人',
            'name_en' => 'key_person'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-修改状态',
            'name_en' => 'modify_status'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-转为无效审核',
            'name_en' => 'turned_invalid'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-置顶房源',
            'name_en' => 'pinned_listing'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-上传图片',
            'name_en' => 'upload_image'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-编辑图片',
            'name_en' => 'edit_picture'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除图片',
            'name_en' => 'delete_image'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-上传证件',
            'name_en' => 'upload_document'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-查看证件',
            'name_en' => 'view_documents'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除证件',
            'name_en' => 'delete_document'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-提交钥匙',
            'name_en' => 'submit_key'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-编辑退换钥匙',
            'name_en' => 'edit_return_key'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-提交退还钥匙审核',
            'name_en' => 'submit_key_review'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-编辑/删除委托书',
            'name_en' => 'power_of_attorney'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-查看联系方式写跟进',
            'name_en' => 'write_follow_up'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除跟进',
            'name_en' => 'delete_follow_up'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-删除房源',
            'name_en' => 'delete_listing'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '房源-列表展示',
            'name_en' => 'list_display'
        ]);

        // 客源
        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源',
            'name_en' => 'customer_source'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-求购客源',
            'name_en' => 'buying_source'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-求租客源',
            'name_en' => 'renter_source'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-客别',
            'name_en' => 'guest'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-电脑端公客',
            'name_en' => 'computer_public'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-电脑端抢客',
            'name_en' => 'computer_rush'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-私客查看范围',
            'name_en' => 'private_guest_view'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-联系方式',
            'name_en' => 'contact_information'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-客源等级',
            'name_en' => 'source_level'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-其他信息',
            'name_en' => 'other_information'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-录入人',
            'name_en' => 'entering_person'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-维护人',
            'name_en' => 'maintainer'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-转为无效',
            'name' => 'turned_invalid'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-查看联系方式写跟进',
            'name_en' => 'write_follow_up'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-删除跟进',
            'name_en' => 'delete_follow_up'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-查看带看',
            'name' => 'view_with_look'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-删除带看',
            'name_en' => 'delete_visits'
        ]);

        Permission::create([
            'guid' => Common::getUuid(),
            'name' => '客源-删除客源',
            'name_en' => 'delete_source'
        ]);

    }
}

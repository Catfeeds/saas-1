<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\BuildingBlock;
use App\Models\CompanyFramework;
use App\Models\House;
use App\Models\SeeHouseWay;
use App\Models\User;

class HousesService
{
    // 通过楼座,楼层获取房源成功
    public function adoptConditionGetHouse(
        $request
    )
    {
        // 通过楼座,楼层获取房号
        $housesNumber = House::where([
            'company_guid' => Common::user()->company_guid,
            'building_block_guid' => $request->building_block_guid,
            'floor' => $request->floor
        ])->pluck('house_number');

        if ($housesNumber->isEmpty()) return (object)[];

        // 获取楼盘楼座拼接
        $buildingBlock = BuildingBlock::where('guid', $request->building_block_guid)->with('building')->first();

        // 楼座名称
        $buildingBlockName = $buildingBlock->name.$buildingBlock->name_unit.'-'.$buildingBlock->unit.$buildingBlock->unit_unit;

        return [
            'name' => $buildingBlock->building->name.$buildingBlockName,
            'housesNumber' => $housesNumber
        ];
    }

    // 房号验证
    public function houseNumberValidate(
        $request
    )
    {
        $house = House::where([
            'company_guid' => Common::user()->company_guid,
            'building_block_guid' => $request->building_block_guid,
            'floor' => $request->floor,
            'house_number' => $request->house_number
        ])->with('buildingBlock.building')->first();

        if (empty($house) || $house->guid == $request->house_guid) return (object)[];

        return [
            'house_img' => $house->indoor_img_cn,
            'buildingName' => $house->buildingBlock->building->name,
            'acreage' => $house->acreage_cn,
            'price' => $house->price.$house->price_unit_cn,
            'entry_person' => User::find($house->entry_person)->name,
            'created_at' => $house->created_at->format('Y-m-d H:i')
        ];
    }

    // 通过楼座获取城市
    public function adoptBuildingBlockGetCity($BuildingBlockGuid)
    {
        $temp = BuildingBlock::find($BuildingBlockGuid);

        // 拼接商圈获取城市数据
        $arr[] = $temp->building->area->city->guid;
        $arr[] = $temp->building->area->guid;
        $arr[] = $temp->building->guid;
        $arr[] = $BuildingBlockGuid;

        return $arr;
    }

    // 提取数据
    public function getData($res)
    {
        $houses = [];
        $houses['guid'] = $res->guid;
        $houses['img'] = $res->indoor_img_cn; //图片
        $houses['name'] = $res->buildingBlock->building->name;  //名称
        $houses['public_private'] = $res->public_private_cn; //公私盘
        $houses['grade'] = $res->grade_cn; //级别
        $houses['key'] = $res->have_key == 1 ? true : false; //是否有钥匙
        $houses['price_unit'] = $res->price . $res->price_unit_cn; //价格单位
        $houses['payment_type'] = $res->payment_type_cn; //付款方式
        $houses['acreage'] = $res->acreage_cn; //面积
        $houses['renovation'] = $res->renovation_cn;  //装修程度
        $houses['orientation'] = $res->orientation_cn; //朝向
        $houses['type'] = $res->type_cn; //类型
        $houses['floor'] = $res->floor. '层'; //楼层
        $houses['total_floor'] = '共' . $res->total_floor . '层'; //总楼层
        $houses['top'] = $res->top == 1 ? true : false; // 置顶
        $houses['track_user'] = !$res->track->isEmpty() ? $res->track->sortByDesc('created_at')->first()->user->name : $res->entryPerson->name;
        $houses['track_time'] = $res->track_time; //跟进时间
        return $houses;
    }

    // 房源详情数据处理
    public function getHouseInfo(
        $house
    )
    {
        $data = array();
        $data['top'] = 1 ? true : false; // 置顶
        $data['img'] = $house->indoor_img_cn; // 图片
        $data['buildingName'] = $house->buildingBlock->building->name; // 楼盘名
        // 门牌号
        if (empty($house->buildingBlock->unit)) {
            $data['house_number'] = $house->buildingBlock->name.$house->buildingBlock->name_unit.' '.$house->house_number;
        } else {
            $data['house_number'] = $house->buildingBlock->name.$house->buildingBlock->name_unit.' '.$house->buildingBlock->unit.$house->buildingBlock->unit_unit;
        }
        $data['public_private'] = $house->public_private_cn; // 公私盘
        $data['grade'] = $house->grade_cn; // 级别
        $data['price_unit'] = $house->price . $house->price_unit_cn; //价格单位
        $data['payment_type'] = $house->payment_type_cn; //付款方式
        $data['acreage'] = $house->acreage_cn; //面积
        // 楼层
        if ($house->buildingBlock->total_floor) {
            $data['floor'] = $house->floor.'/'.$house->buildingBlock->total_floor;
        } else {
            $data['floor'] = $house->floor;
        }
        $data['orientation'] = $house->orientation_cn; //朝向
        $data['renovation'] = $house->renovation_cn;  //装修程度
        $data['type'] = $house->type_cn; //类型
        $data['cost_detail'] = empty($house->cost_detail)?'暂无':implode(',', $house->cost_detail); // 费用明细
        $data['source'] = $house->source_cn; // 来源渠道
        $data['increasing_situation_remark'] = $house->increasing_situation_remark; // 递增情况
        $data['split'] = $house->split_cn; // 拆分
        $data['mini_acreage'] = empty($house->mini_acreage)?'暂无':$house->mini_acreage.'㎡'; // 最小面积
        $data['floor_height'] = empty($house->floor_height)?'暂无':$house->floor_height.'米'; // 层高
        $data['property_fee'] = $house->buildingBlock->property_fee_cn; // 物业费
        $data['register_company'] = $house->register_company_cn; // 是否注册
        $data['open_bill'] = $house->open_bill_cn; // 可开发票
        $data['station_number'] = empty($house->station_number)?'暂无':$house->station_number.'个'; // 工位数量
        $data['rent_free'] = empty($house->rent_free)?'暂无':$house->rent_free.'天'; // 免租期
        $data['shortest_lease'] = $house->shortest_lease_cn; // 最短租期
        $data['actuality'] = $house->actuality_cn; // 现状
        $data['support_facilities'] = empty($house->support_facilities)?'暂无':implode(',',$house->support_facilities); // 配套设施
        $data['remarks'] = $house->remarks??'暂无'; // 备注

        // 录入人
        if ($house->entryPerson) {
            // 录入人姓名
            $entryPersonName = $house->entryPerson->name;
            // 录入人所属门店
            if ($house->entryPerson->rel_guid) $entryPersonStorefront = CompanyFramework::find($house->entryPerson->rel_guid)->name;
            // 录入人图像
            if ($house->entryPerson->pic) $entryPersonPic = config('setting.qiniu_url') . $house->entryPerson->pic;
        }
        $entryPersonGuid = $house->entry_person??''; // 录入人guid
        $data['relevant']['entry_person']['name'] = $entryPersonName??'-';
        $data['relevant']['entry_person']['storefront'] = $entryPersonStorefront??'';
        $data['relevant']['entry_person']['pic'] = $entryPersonPic??config('setting.user_default_img');
        $data['relevant']['entry_person']['guid'] = $entryPersonGuid;

        // 维护人
        if ($house->guardianPerson) {
            // 维护人姓名
            $guardianPersonName = $house->guardianPerson->name;
            // 维护人所属门店
            if ($house->guardianPerson->rel_guid) $guardianPersonStorefront = CompanyFramework::find($house->guardianPerson->rel_guid)->name;
            // 维护人图像
            if ($house->guardianPerson->pic) $guardianPersonPic = config('setting.qiniu_url') . $house->guardianPerson->pic;
        }
        $data['relevant']['guardian_person']['name'] = $guardianPersonName??'-';
        $data['relevant']['guardian_person']['storefront'] = $guardianPersonStorefront??'';
        $data['relevant']['guardian_person']['pic'] = $guardianPersonPic??config('setting.user_default_img');
        $data['relevant']['guardian_person']['guid'] = $house->guardian_person??'';

        // 图片人
        if ($house->picPerson) {
            // 图像人姓名
            $picPersonName = $house->picPerson->name;
            // 图片人所属门店
            if ($house->picPerson->rel_guid) $picPersonStorefront = CompanyFramework::find($house->picPerson->rel_guid)->name;
            // 图片人图像
            if ($house->picPerson->pic) $picPersonStorePic = config('setting.qiniu_url') . $house->picPerson->pic;
        }
        $data['relevant']['pic_person']['name'] = $picPersonName??'-';
        $data['relevant']['pic_person']['storefront'] = $picPersonStorefront??'';
        $data['relevant']['pic_person']['pic'] = $picPersonStorePic??config('setting.user_default_img');
        $data['relevant']['pic_person']['guid'] = $house->pic_person??'';

        // 钥匙人
        if ($house->keyPerson) {
            // 钥匙人姓名
            $keyPersonName = $house->keyPerson->name;
            // 钥匙人所属门店
            if ($house->keyPerson->rel_guid) $keyPersonStorefront = CompanyFramework::find($house->keyPerson->rel_guid)->name;
            // 钥匙人图像
            if ($house->keyPerson->pic) $keyPersonPic = config('setting.qiniu_url') . $house->keyPerson->pic;
        }
        $data['relevant']['key_person']['name'] = $keyPersonName??'-';
        $data['relevant']['key_person']['storefront'] = $keyPersonStorefront??'';
        $data['relevant']['key_person']['pic'] = $keyPersonPic??config('setting.user_default_img');
        $data['relevant']['key_person']['guid'] = $house->key_personGuid??'';

        return $data;
    }

    // 看房方式
    public function seeHouseWay(
        $request
    )
    {
        \DB::beginTransaction();
        try {
            // 查询是否有看房记录数据
            $seeHouseWay = SeeHouseWay::where('house_guid', $request->house_guid)->first();
            if ($seeHouseWay) {
                $seeHouseWay->type = $request->type;
                $seeHouseWay->remarks = $request->remarks;
                $seeHouseWay->storefront_guid = $request->storefront_guid;
                $seeHouseWay->key_number = $request->key_number;
                $seeHouseWay->key_single = $request->key_single;
                $seeHouseWay->received_time = $request->received_time;
                if (!$seeHouseWay->save()) throw new \Exception('看房方式修改失败');
            } else {
                $seeHouseWay = SeeHouseWay::create([
                    'guid' => Common::getUuid(),
                    'house_guid' => $request->house_guid,
                    'type' => $request->type,
                    'remarks' => $request->remarks,
                    'storefront_guid' => $request->storefront_guid,
                    'key_number' => $request->key_number,
                    'key_single' => $request->key_single,
                    'received_time' => $request->received_time,
                ]);
                if (empty($seeHouseWay)) throw new \Exception('看房方式添加失败');
            }

            // 修改房源钥匙人
            $house = House::where(['guid' => $request->house_guid])->update(['key_person' => Common::user()->guid]);
            if (empty($house)) throw new \Exception('房源钥匙人修改失败');

            // TODO 操作记录

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }

    // 转为无效
    public function turnedInvalid($request)
    {
        \DB::beginTransaction();
        try {
            // 修改房源状态
            $houseStatus = House::where('guid',$request->guid)->update(['status' => $request->status]);
            if (empty($houseStatus)) throw new \Exception('修改房源状态失败');

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }

    // 转为有效
    public function turnEffective($request)
    {
        \DB::beginTransaction();
        try {
            // 修改房源状态
            $data = ['status' => 1];
            if ($request->type == 1) {
                $data['guardian_person'] = Common::user()->guid;
            } elseif ($request->type == 2) {
                $data['guardian_person'] = '';
            }

            $houseStatus = House::where('guid',$request->guid)->update($data);
            if (empty($houseStatus)) throw new \Exception('修改房源状态失败');

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }
}
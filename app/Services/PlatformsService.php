<?php

namespace App\Services;

use App\Models\House;

class PlatformsService
{
    // 平台房源列表
    public function getList($request)
    {
        if ($request->share) {
            $share = $request->share;
        } else {
            $share = 1;
        }
        $res = House::with('buildingBlock', 'buildingBlock.building')->where(['release_source' => '平台','share' => $share])
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page??20);
        $houses = [];
        foreach ($res as $key => $v) {
            $houses[$key]['guid'] = $v->guid;
            $houses[$key]['img'] = $v->indoor_img_cn; //图片
            $houses[$key]['name'] = $v->buildingBlock->building->name;  //名称
            $houses[$key]['grade'] = $v->grade_cn; //级别
            $houses[$key]['price_unit'] = $v->price . $v->price_unit_cn; //价格单位
            $houses[$key]['payment_type'] = $v->payment_type_cn; //付款方式
            $houses[$key]['acreage'] = $v->acreage_cn; //面积
            $houses[$key]['renovation'] = $v->renovation_cn;  //装修程度
            $houses[$key]['orientation'] = $v->orientation_cn; //朝向
            $houses[$key]['type'] = $v->type_cn; //类型
            $houses[$key]['floor'] = $v->floor. '层'; //楼层
            $houses[$key]['total_floor'] = $v->buildingBlock->total_floor?'共' . $v->buildingBlock->total_floor. '层':'-';
            $houses[$key]['belong'] = $v->release_source;
            $share = $v->shareRecord->sortByDesc('created_at')->first();
            if ($v->share == 1) {
                $houses[$key]['share'] = explode(' ',optional($share)->remarks)[0];
            } elseif ($v->share == 2) {
                $houses[$key]['share'] = $v->lower_cn;
            }
            $houses[$key]['share_time'] = optional($share)->created_at->format('Y-m-d H:i:s');
        }
        return $res->setCollection(collect($houses));
    }

    // 平台房源详情
    public function getInfo($guid)
    {
        $house = House::where('guid', $guid)->with(['buildingBlock', 'buildingBlock.building', 'guardianPerson'])->first();
        $data = [];
        $data['img'] = $house->indoor_img_cn??[]; // 图片
        $data['indoor_img'] = $house->indoor_img??[]; // 室内图未处理
        $data['house_type_img'] = $house->house_type_img??[]; // 户型图未处理
        $data['outdoor_img'] = $house->outdoor_img??[]; // 室外图未处理
        $data['indoor_img_url'] = $house->indoor_img_url??[]; // 室内图
        $data['house_type_img_url'] = $house->house_type_img_url??[]; // 户型图
        $data['outdoor_img_url'] = $house->outdoor_img_url??[]; // 室外图
        $data['buildingName'] = $house->buildingBlock->building->name??'暂无'; // 楼盘名
        $data['grade'] = $house->grade_cn??'暂无'; // 级别
        $data['price_unit'] = $house->price . $house->price_unit_cn; //价格单位
        $data['payment_type'] = $house->payment_type_cn??'暂无'; //付款方式
        $data['acreage'] = $house->acreage_cn??'暂无'; //面积
        // 楼层
        if ($house->buildingBlock->total_floor) {
            $data['floor'] = $house->floor.'/'.$house->buildingBlock->total_floor;
        } else {
            $data['floor'] = $house->floor;
        }
        $data['orientation'] = $house->orientation_cn??'暂无'; //朝向
        $data['renovation'] = $house->renovation_cn??'暂无';  //装修程度
        $data['type'] = $house->type_cn??'暂无'; //类型
        // 费用明细
        $data['cost_detail'] = empty($house->cost_detail)?'暂无':implode(',', $house->cost_detail);
        $data['source'] = $house->source_cn; // 来源渠道
        $data['increasing_situation_remark'] = $house->increasing_situation_remark??'暂无'; // 递增情况
        $data['split'] = $house->split_cn; // 拆分
        $data['mini_acreage'] = empty($house->mini_acreage)?'暂无':$house->mini_acreage.'㎡'; // 最小面积
        $data['floor_height'] = empty($house->floor_height)?'暂无':$house->floor_height.'m'; // 层高
        $data['property_fee'] = $house->buildingBlock->property_fee_cn??'暂无'; // 物业费
        $data['register_company'] = $house->register_company_cn??'暂无'; // 是否注册
        $data['open_bill'] = $house->open_bill_cn??'暂无'; // 可开发票
        // 工位数量
        $data['station_number'] = empty($house->station_number)?'暂无':$house->station_number.'个';
        $data['rent_free'] = empty($house->rent_free)?'暂无':$house->rent_free.'天'; // 免租期
        $data['shortest_lease'] = $house->shortest_lease_cn??'暂无'; // 最短租期
        $data['support_facilities'] = empty($house->support_facilities)?'暂无':implode(',',$house->support_facilities); // 配套设施
        $data['actuality'] = $house->actuality_cn;

        return $data;
    }
}
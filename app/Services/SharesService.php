<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\Area;
use App\Models\Company;
use App\Models\House;

class SharesService
{
    // 共享房源列表
    public function getList($request)
    {
        if ($request->share) {
            $share = $request->share;
        } else {
            $share = 1;
        }
        $res = House::with('buildingBlock', 'buildingBlock.building')->where('share', $share)->orderBy('created_at', 'desc')->paginate($request->per_page??20);
        $houses = [];
        foreach ($res as $key => $v) {
            if ($request->type) {
                $belong = $v->company_guid ? '其他公司' : '平台';
            } else {
                $belong = $v->company_guid ? $v->company_guid == Common::user()->company_guid ? '本公司' : '其他公司' : '平台';
            }
            $houses[$key]['guid'] = $v->guid;
            $houses[$key]['img'] = $v->indoor_img_cn; //图片
            $houses[$key]['name'] = $v->buildingBlock->building->name;  //名称
            $houses[$key]['grade'] = $v->grade_cn; //级别
            $houses[$key]['price_unit'] = $v->price . '元/㎡/月'; //价格单位
            $houses[$key]['payment_type'] = $v->payment_type_cn; //付款方式
            $houses[$key]['acreage'] = $v->acreage_cn; //面积
            $houses[$key]['renovation'] = $v->renovation_cn;  //装修程度
            $houses[$key]['orientation'] = $v->orientation_cn; //朝向
            $houses[$key]['type'] = $v->type_cn; //类型
            $houses[$key]['floor'] = $v->floor. '层'; //楼层
            $houses[$key]['total_floor'] = $v->buildingBlock->total_floor?'共' . $v->buildingBlock->total_floor. '层':'-';
            $houses[$key]['belong'] = $belong;
            $share = $v->shareRecord->sortByDesc('created_at')->first();
            if ($v->share == 1) {
                $houses[$key]['share'] = optional($share)->remarks;
            } elseif ($v->share == 2) {
                $houses[$key]['share'] = $v->lower_cn;
            }
            $houses[$key]['share_time'] = optional($share)->created_at->format('Y-m-d H:i:s');
        }
        return $res->setCollection(collect($houses));
    }

    // 共享房源详情
    public function getInfo($guid, $type = null)
    {
        $house = House::where('guid', $guid)->with(['buildingBlock', 'buildingBlock.building', 'shareRecord', 'guardianPerson', 'company'])->first();
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
        $data['price_unit'] = $house->price . '元/㎡/月'; //价格单位
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
        //联系方式
        $contact = [];
        //  所属公司
        if ($house->company_guid) {
            if ($type) {
                $data['belong'] = '其他公司';
            } else {
                $data['belong'] = $house->company_guid == Common::user()->company_guid ? '本公司' : '其他公司';
            }
            $contact['name'] = optional($house->guardianPerson)->name;
            $contact['tel'] = optional($house->guardianPerson)->tel;
        } else {
            $data['belong'] = '平台';
            $contact['name'] = '平台客服';
            $contact['tel'] = '4000-580-888';
        }
        $share = $house->shareRecord->sortByDesc('created_at')->first();
        $data['share_info'] = $house->shareRecord;
        $data['share_time'] = optional($share)->created_at->format('Y-m-d H:i:s');
        $data['contact'] = $contact;
        $data['company_name'] = $house->company ? $house->company->name : '平台';
        $data['share'] = $house->share;
        return $data;
    }


    // 公司共享房源列表
    public function getCompanyList($request)
    {
        $res = House::with('buildingBlock', 'buildingBlock.building')->where(['company_guid' => Common::user()->company_guid, 'share' => $request->working])->orderBy($request->sortKey,$request->sortValue);

        // 区域
        if ($request->region) {
            $area = Area::where('guid',$request->region)->with('building.buildingBlock')->first();
            // 区域下所有楼座
            $buildingBlockGuid = array();
            foreach ($area->building as $v) {
                foreach ($v->buildingBlock as $val) {
                    $buildingBlockGuid[] = $val->guid;
                }
            }
            $res = $res->whereIn('building_block_guid', $buildingBlockGuid);
        }

        // 面积
        if ($request->area) {
            $area = explode('-',$request->area);
            $res = $res->whereBetween('acreage', $area);
        }

        // 价格
        if ($request->price) {
            $price = explode('-', $request->price);
            $res = $res->whereBetween('price', $price);
        }

        // 付款方式
        if ($request->paymode) {
            $res = $res->where('payment_type', $request->paymode);
        }

        // 最短租期
        if ($request->shortestLease) {
            $res = $res->where('shortest_lease', $request->shortestLease);
        }

        // 等级
        if ($request->grade) {
            $res = $res->where('grade', $request->grade);
        }

        // 标签
        if ($request->label) {
            // 有图
            if ($request->label == 1) {
                $res = $res->where('house_type_img', '!=',null)->where('indoor_img', '!=',null)->where('outdoor_img','!=',null);
            }

            // 有钥匙
            if ($request->label == 2) {
                $res = $res->where('have_key',1);
            }

            // 可注册公司
            if ($request->label == 3) {
                $res = $res->where('register_company',1);
            }

            // 可开发票
            if ($request->label == 4) {
                $res = $res->where('open_bill',1);
            }
        }

        // 楼层
        if ($request->floor) {
            $floor = explode('-', $request->floor);
            $res = $res->whereBetween('floor', $floor);
        }

        // 朝向
        if ($request->orientation) {
            $res = $res->where('orientation', $request->orientation);
        }

        // 装修
        if ($request->renovation) {
            $res = $res->where('renovation', $request->renovation);
        }

        // 类型
        if ($request->type) {
            $res = $res->where('type', $request->type);
        }

        // 配套(json查询)
        if ($request->supportFacilities) {
            $name = "[\"$request->supportFacilities\"]";
            $res = $res->whereRaw("JSON_CONTAINS(support_facilities,'".$name."')");
        }

        $res = $res->paginate($request->per_page??10);

        $houses = [];
        foreach ($res as $key => $v) {
            $houses[$key]['guid'] = $v->guid;
            $houses[$key]['img'] = $v->indoor_img_cn; //图片
            $houses[$key]['name'] = $v->buildingBlock->building->name;  //名称
            $houses[$key]['grade'] = $v->grade_cn; //级别
            $houses[$key]['price_unit'] = $v->price . '元/㎡/月'; //价格单位
            $houses[$key]['payment_type'] = $v->payment_type_cn; //付款方式
            $houses[$key]['acreage'] = $v->acreage_cn; //面积
            $houses[$key]['renovation'] = $v->renovation_cn;  //装修程度
            $houses[$key]['orientation'] = $v->orientation_cn; //朝向
            $houses[$key]['type'] = $v->type_cn; //类型
            $houses[$key]['floor'] = $v->floor. '层'; //楼层
            $houses[$key]['total_floor'] = $v->buildingBlock->total_floor?'共' . $v->buildingBlock->total_floor. '层':'-';
            $share = $v->shareRecord->sortByDesc('created_at')->first();
            if ($v->share == 1) {
                $houses[$key]['share'] = optional($share)->remarks;
           } elseif ($v->share == 2) {
                $houses[$key]['share'] = $v->lower_cn;
            }
            $houses[$key]['share_time'] = optional($share)->created_at->format('Y-m-d H:i:s');
        }
        return $res->setCollection(collect($houses));
    }

    // 公司共享房源详情
    public function getCompanyInfo($guid)
    {
        $house = House::where('guid', $guid)->with(['buildingBlock', 'buildingBlock.building', 'shareRecord'])->first();
        $data = [];
        $data['img'] = $house->indoor_img_cn??[]; // 图片
        $data['indoor_img'] = $house->indoor_img??[]; // 室内图未处理
        $data['house_type_img'] = $house->house_type_img??[]; // 户型图未处理
        $data['outdoor_img'] = $house->outdoor_img??[]; // 室外图未处理
        $data['indoor_img_url'] = $house->indoor_img_url??[]; // 室内图
        $data['house_type_img_url'] = $house->house_type_img_url??[]; // 户型图
        $data['outdoor_img_url'] = $house->outdoor_img_url??[]; // 室外图
        $data['buildingName'] = $house->buildingBlock->building->name??'暂无'; // 楼盘名
        // 门牌号
        if (empty($house->buildingBlock->unit)) {
            $data['house_number'] = $house->buildingBlock->name.$house->buildingBlock->name_unit.' '.$house->house_number.' '.$house->house_number;
        } else {
            $data['house_number'] = $house->buildingBlock->name.$house->buildingBlock->name_unit.' '.$house->buildingBlock->unit.$house->buildingBlock->unit_unit.' '.$house->house_number;
        }
        $data['grade'] = $house->grade_cn??'暂无'; // 级别
        $data['price_unit'] = $house->price . '元/㎡/月'; //价格单位
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
        $data['increasing_situation_remark'] = $house->increasing_situation_remark??'-'; // 递增情况
        $data['split'] = $house->split_cn; // 拆分
        $data['mini_acreage'] = empty($house->mini_acreage)?'暂无':$house->mini_acreage.'㎡'; // 最小面积
        $data['floor_height'] = empty($house->floor_height)?'暂无':$house->floor_height.'m'; // 层高
        $data['property_fee'] = $house->buildingBlock->property_fee_cn??'-'; // 物业费
        $data['register_company'] = $house->register_company_cn??'暂无'; // 是否注册
        $data['open_bill'] = $house->open_bill_cn??'暂无'; // 可开发票
        // 工位数量
        $data['station_number'] = empty($house->station_number)?'暂无':$house->station_number.'个';
        $data['rent_free'] = empty($house->rent_free)?'暂无':$house->rent_free.'天'; // 免租期
        $data['shortest_lease'] = $house->shortest_lease_cn??'暂无'; // 最短租期
        // 配套设施
        $data['support_facilities'] =empty($house->support_facilities)?'暂无':implode(',',$house->support_facilities);
        $share = $house->shareRecord->sortByDesc('created_at')->first();
        $data['share_info'] = $house->shareRecord; // 信息
        $data['share_time'] = optional($share)->created_at->format('Y-m-d H:i:s');
        $data['share'] = $house->share;
        return $data;
    }

    // 获取全部公司
    public function getCompany()
    {
        $company = Company::all();
        return $company->map(function ($v) {
            return [
                'label' => $v->name,
                'value' => $v->guid,
            ];
        });
    }



}
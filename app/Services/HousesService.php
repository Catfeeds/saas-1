<?php

namespace App\Services;

use App\Handler\Access;
use App\Handler\Common;
use App\Models\Area;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\Company;
use App\Models\House;
use App\Models\HouseOperationRecord;
use App\Models\HouseShareRecord;
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
        if ($buildingBlock->unit) {
            $buildingBlockName = $buildingBlock->name.$buildingBlock->name_unit.'-'.$buildingBlock->unit.$buildingBlock->unit_unit;
        } else {
            $buildingBlockName = $buildingBlock->name.$buildingBlock->name_unit;
        }

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
            'price' => $house->price . '元/㎡·月',
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
        $houses['name'] = $res->buildingBlock->building->name;  //名称
        $houses['public_private'] = $res->public_private_cn; //公私盘
        $houses['grade'] = $res->grade_cn; //级别
        $houses['key'] = $res->have_key == 1 ? true : false; //是否有钥匙
        $houses['price_unit'] = $res->price . '元/㎡·月'; //价格单位
        $houses['payment_type'] = $res->payment_type_cn; //付款方式
        $houses['acreage'] = $res->acreage_cn; //面积
        $houses['renovation'] = $res->renovation_cn;  //装修程度
        $houses['orientation'] = $res->orientation_cn; //朝向
        $houses['type'] = $res->type_cn; //类型
        $houses['floor'] = $res->floor. '层'; //楼层
        $houses['total_floor'] = $res->buildingBlock->total_floor?'共' . $res->buildingBlock->total_floor. '层':'-';
        $houses['top'] = $res->top == 1 ? true : false; // 置顶
        $houses['track_user'] = !$res->track->isEmpty() ? $res->track->sortByDesc('created_at')->first()->user->name : optional($res->entryPerson)->name;
        $houses['guardian_person'] = $res->guardianPerson->name;    // 维护人
        $houses['track_time'] = $res->track_time; //跟进时间
        $houses['share'] = $res->share == 1 ? true : false; //是否共享
        return $houses;
    }

    // 房源详情动态说明
    public function getDynamicInfo($house, $type)
    {
        $res = $house->load(['record' => function($query) use ($type) {
            $query->where('type', $type)->latest();
        }]);
        $user = $res->record->pluck('name','tel')->toArray();
        $count = count($user);
        $user = array_values($user);
        switch ($type) {
            case 1:
                if (!$count) {
                    return '最近暂无跟进信息';
                } elseif ($count == 1) {
                    return current($user).'最近跟进';
                } elseif ($count == 2) {
                    return current($user).'、'. end($user).'最近跟进';
                } else {
                    return $user[0].'、'.$user[1].'、'.$user[2].'等'.$count.'人最近跟进';
                }
                break;
            case 2:
                if (!$count) {
                    return '最近暂无带看信息';
                } elseif ($count == 1) {
                    return current($user).'最近带看';
                } elseif ($count == 2) {
                    return current($user).'、'. end($user).'最近带看';
                } else {
                    return $user[0].'、'.$user[1].'、'.$user[2].'等'.$count.'人最近带看';
                }
                break;
            case 3:
                if (!$count) {
                    return '最近暂无图片编辑';
                } elseif ($count == 1) {
                    return current($user).'最近编辑图片';
                } elseif ($count == 2) {
                    return current($user).'、'. end($user).'最近编辑图片';
                } else {
                    return $user[0].'、'.$user[1].'、'.$user[2].'等'.$count.'人最近编辑图片';
                }
                break;
            case 4:
                if (!$count) {
                    return '最近暂无查看信息';
                } elseif ($count == 1) {
                    return current($user).'最近查看核心信息';
                } elseif ($count == 2) {
                    return current($user).'、'. end($user).'最近查看核心信息';
                } else {
                    return $user[0].'、'.$user[1].'、'.$user[2].'等'.$count.'人最近查看核心信息';
                }
                break;
                default;
                break;
        }
    }

    // 房源详情数据处理
    public function getHouseInfo($house)
    {
        $permission['upload_pic'] = true; // 是否上传图片
        $permission['edit_pic'] = true; // 是否允许编辑图片
        $permission['submit_key'] = true; // 是否允许提交钥匙
        $permission['edit_return_key'] = true; // 是否允许编辑/退换钥匙
        $permission['see_documents'] = true; // 是否允许查看相关证件
        $permission['update_house_status'] = true; // 是否允许修改状态(转为无效)
        $permission['edit_house'] = true; // 是否允许编辑房源
        $permission['set_entry_person'] = true; // 是否允许修改录入人
        $permission['set_guardian_person'] = true; // 是否允许修改维护人
        $permission['set_pic_person'] = true; // 是否允许修改图片人
        $permission['set_key_person'] = true; // 是否允许修改钥匙人
        $permission['upload_document'] = true; // 是否允许上传证件
        $permission['del_documents'] = true; // 是否允许删除证件
        $permission['del_pic'] = true; // 是否允许删除图片
        $permission['public_to_private'] = true; // 是否允许公盘转为私盘
        $permission['private_to_public'] = true; // 是否允许私盘转为公盘
        $permission['set_top'] = true; // 是否允许置顶
        $permission['share'] = true; // 是否允许发布共享

        $share = Access::adoptGuardianPersonGetHouse('house_share');
        if (!in_array($house->guid, $share)) {
            $permission['share'] = false; // 是否允许共享房源
        }

        // 上传图片
        $uploadImage = Access::adoptGuardianPersonGetHouse('upload_pic');
        if (!in_array($house->guid, $uploadImage)) {
            $permission['upload_pic'] = false; // 是否允许上传图片
        }

        // 编辑图片
        $editPicture = Access::adoptGuardianPersonGetHouse('edit_pic');
        if (!in_array($house->guid, $editPicture)) {
            $permission['edit_pic'] = false; // 是否允许编辑图片
        }

        // 提交钥匙
        $submitKey = Access::adoptGuardianPersonGetHouse('submit_key');
        if (!in_array($house->guid, $submitKey)) {
            $permission['submit_key'] = false; // 是否允许提交钥匙
        }

        // 看房方式
        $editReturnKey = Access::adoptGuardianPersonGetHouse('edit_return_key');
        if (!in_array($house->guid, $editReturnKey)) {
            $permission['edit_return_key'] = false; // 是否允许编辑/退换钥匙
        }

        // 查看相关证件
        $viewDocuments = Access::adoptGuardianPersonGetHouse('see_documents');
        if (!in_array($house->guid, $viewDocuments)) {
            $permission['see_documents'] = false; // 是否允许查看相关证件
        }

        // 修改状态(转为无效)
        $modifyStatus = Access::adoptGuardianPersonGetHouse('update_house_status');
        if (!in_array($house->guid, $modifyStatus)) {
            $permission['update_house_status'] = false; // 是否允许修改状态(转为无效)
        }

        // 编辑房源
        $updateHouse = Access::adoptGuardianPersonGetHouse('edit_house');
        if (!in_array($house->guid, $updateHouse)) {
            $permission['edit_house'] = false; // 是否允许编辑房源
        }

        // 录入人
        $enteringPerson = Access::adoptPermissionGetUser('set_entry_person');
        if (!in_array($house->entry_person, $enteringPerson)) {
            $permission['set_entry_person'] = false; // 是否允许修改录入人
        }

        // 维护人
        $maintainer = Access::adoptGuardianPersonGetHouse('set_guardian_person');
        if (!in_array($house->guid, $maintainer)) {
            $permission['set_guardian_person'] = false; // 是否允许修改维护人
        }

        // 图片人
        $picturePerson = Access::adoptGuardianPersonGetHouse('set_pic_person');
        if (!in_array($house->guid, $picturePerson)) {
            $permission['set_pic_person'] = false; // 是否允许修改图片人
        }

        // 钥匙人
        $keyPerson = Access::adoptGuardianPersonGetHouse('set_key_person');
        if (!in_array($house->guid, $keyPerson) && $house->have_key != 1) {
            $permission['set_key_person'] = false; // 是否允许修改钥匙人
        }

        // 上传证件
        $uploadDocument = Access::adoptGuardianPersonGetHouse('upload_document');
        if (!in_array($house->guid, $uploadDocument)) {
            $permission['upload_document'] = false; // 是否允许上传证件
        }

        // 删除证件
        $delDocuments = Access::adoptGuardianPersonGetHouse('del_documents');
        if (!in_array($house->guid, $delDocuments)) {
            $permission['del_documents'] = false; // 是否允许删除证件
        }

        // 删除图片
        $delPic = Access::adoptGuardianPersonGetHouse('del_pic');
        if (!in_array($house->guid, $delPic)) {
            $permission['del_pic'] = false; // 是否允许删除图片
        }

        // 公盘转为私盘
        $publicToPrivate = Access::adoptGuardianPersonGetHouse('public_to_private');
        if (!in_array($house->guid, $publicToPrivate)) {
            $permission['public_to_private'] = false; // 是否允许公盘转为私盘
        }

        // 私盘转为公盘
        $privateToPublic = Access::adoptGuardianPersonGetHouse('private_to_public');
        if (!in_array($house->guid, $privateToPublic)) {
            $permission['private_to_public'] = false; // 是否允许私盘转为公盘
        }

        $setTop = Access::adoptGuardianPersonGetHouse('set_top');
        if (!in_array($house->guid, $setTop)) {
            $permission['set_top'] = false; // 是否允许置顶
        }

        $data = array();

        $data['permission'] = $permission;  // 权限

        // 房源
        $house = House::where('guid', $house->guid)->with(['buildingBlock', 'entryPerson.companyFramework', 'guardianPerson.companyFramework', 'picPerson.companyFramework', 'keyPerson.companyFramework', 'seeHouseWay', 'seeHouseWay.storefront'])->first();
        // 查看核心信息
        $data['see_info'] = $this->getDynamicInfo($house, 4);
        // 跟进信息
        $data['track_info'] = $this->getDynamicInfo($house, 1);
        // 上传图片信息
        $data['img_info'] = $this->getDynamicInfo($house, 3);
        // 带看信息
        $data['visit_info'] = $this->getDynamicInfo($house, 2);
        $data['top'] = $house->top == 1 ? true : false; // 置顶
        $data['img'] = $house->indoor_img_cn; // 图片
        $data['indoor_img'] = $house->indoor_img; // 室内图未处理
        $data['house_identifier'] = $house->house_identifier; // 房源编号
        $data['house_type_img'] = $house->house_type_img; // 户型图未处理
        $data['outdoor_img'] = $house->outdoor_img; // 室外图未处理
        $data['indoor_img_url'] = $house->indoor_img_url; // 室内图
        $data['house_type_img_url'] = $house->house_type_img_url; // 户型图
        $data['outdoor_img_url'] = $house->outdoor_img_url; // 室外图
        $data['relevant_proves_img'] = $house->relevant_proves_img_cn??array(); // 相关证件
        $data['buildingName'] = $house->buildingBlock->building->name; // 楼盘名
        $data['created_at'] = $house->created_at->format('Y-m-d H:i:s'); // 创建时间
//        // 门牌号
//        if (empty($house->buildingBlock->unit)) {
//            $data['house_number'] = $house->buildingBlock->name.$house->buildingBlock->name_unit.' '.$house->house_number.' '.$house->house_number;
//        } else {
//            $data['house_number'] = $house->buildingBlock->name.$house->buildingBlock->name_unit.' '.$house->buildingBlock->unit.$house->buildingBlock->unit_unit.' '.$house->house_number;
//        }
        $data['public_private'] = $house->public_private_cn; // 公私盘
        $data['grade'] = $house->grade_cn; // 级别
        $data['price_unit'] = $house->price . '元/㎡·月'; //价格单位
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
        // 费用明细
        $data['cost_detail'] = empty($house->cost_detail)?'暂无':implode(',', $house->cost_detail);
        $data['source'] = $house->source_cn; // 来源渠道
        $data['increasing_situation_remark'] = $house->increasing_situation_remark; // 递增情况
        $data['split'] = $house->split_cn; // 拆分
        $data['mini_acreage'] = empty($house->mini_acreage)?'暂无':$house->mini_acreage.'㎡'; // 最小面积
        $data['floor_height'] = empty($house->floor_height)?'暂无':$house->floor_height.'m'; // 层高
        $data['property_fee'] = $house->buildingBlock->property_fee_cn; // 物业费
        $data['register_company'] = $house->register_company_cn; // 是否注册
        $data['open_bill'] = $house->open_bill_cn; // 可开发票
        // 工位数量
        $data['station_number'] = empty($house->station_number)?'暂无':$house->station_number.'个';
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
            if ($house->entryPerson->rel_guid) $entryPersonStorefront = $house->entryPerson->companyFramework->name;
            // 录入人图像
            if ($house->entryPerson->pic) $entryPersonPic = config('setting.qiniu_url') . $house->entryPerson->pic;
        }
        $data['relevant']['entry_person']['name'] = $entryPersonName??'-';
        $data['relevant']['entry_person']['storefront'] = $entryPersonStorefront??'';
        $data['relevant']['entry_person']['pic'] = $entryPersonPic??config('setting.user_default_img');
        $data['relevant']['entry_person']['guid'] = $house->entry_person??''; // 录入人guid

        // 维护人
        if ($house->guardianPerson) {
            // 维护人姓名
            $guardianPersonName = $house->guardianPerson->name;
            // 维护人所属门店
            if ($house->guardianPerson->rel_guid) $guardianPersonStorefront = $house->guardianPerson->companyFramework->name;
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
            if ($house->picPerson->rel_guid) $picPersonStorefront = $house->picPerson->companyFramework->name;
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
            // 钥匙人图像
            if ($house->keyPerson->pic) $keyPersonPic = config('setting.qiniu_url') . $house->keyPerson->pic;
            // 钥匙人所属门店
            if ($house->keyPerson->rel_guid) $keyPersonStorefront = $house->keyPerson->companyFramework->name;
        }
        $data['relevant']['key_person']['name'] = $keyPersonName??'-';
        $data['relevant']['key_person']['storefront'] = $keyPersonStorefront??'';
        $data['relevant']['key_person']['pic'] = $keyPersonPic??config('setting.user_default_img');
        $data['relevant']['key_person']['guid'] = $house->key_person??'';

        // 看房方式 $data['seeHouseWay']['storefront_name']
        $storefront_name = $house->have_key == 1 ? $house->seeHouseWay->storefront->name : null;
        $data['seeHouseWay'] = optional($house->seeHouseWay)->setRelation('storefront',[]);
        $data['seeHouseWay']['storefront_name'] = $storefront_name;

        // 七牛url
        $data['qiNiuUrl'] = config('setting.qiniu_url');

        // 跟进
        $track = array();
        foreach ($house->track as $k => $v) {
            $track[$k]['guid'] = $v->guid;
            $track[$k]['user'] = $v->user->name;
            $track[$k]['tracks_info'] = $v->tracks_info;
            $track[$k]['created_at'] = $v->created_at->format('Y-m-d H:i');
            // 是否允许编辑标识  十分钟内  添加人必须是登录人
            $track[$k]['operation'] = false;
            if (time() - strtotime($v->created_at->format('Y-m-d H:i')) <= 60 * 30) {
                if ($v->user_guid == Common::user()->guid) {
                    $track[$k]['operation'] = true;
                }
            }
        }
        $data['track'] = $track;
        $data['status'] = $house->status;
        $data['status_cn'] = $house->status_cn;
        $data['share'] = $house->share; // 是否共享

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

            // 是否有钥匙
            if ($request->type == 4) {
                $haveKey = 1;
                $keyPerson = Common::user()->guid;
            } else {
                $haveKey = 2;
                $keyPerson = '';
            }

            // 修改房源钥匙人
            $house = House::where(['guid' => $request->house_guid])->update([
                'key_person' => $keyPerson,
                'have_key' => $haveKey
            ]);
            if (empty($house)) throw new \Exception('房源钥匙人修改失败');
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
            $status = '';
            if ($request->status == 3) {
                $status = '暂缓';
            } elseif ($request->status == 4) {
                $status = '内成交';
            } elseif ($request->status == 5) {
                $status = '外成交';
            } elseif ($request->status == 6) {
                $status = '信息有误';
            } elseif ($request->status == 7) {
                $status = '其他';
            }

            if ($request->remark) {
                $remarks = '将房源转为无效,原因是:'.$status. ',备注原因是:'. $request->remark;
            } else {
                $remarks = '将房源转为无效,原因是:'.$status;
            }

            $house = House::find($request->guid);

            $house->status = $request->status;
            // 如果是上架状态
            if ($house->share == 1) {
                $house->share = 2;
                $house->release_source = null;
                $house->lower_frame = 2;
                // 添加分享记录
                $res = HouseShareRecord::create([
                    'guid' => Common::getUuid(),
                    'house_guid' => $house->guid,
                    'remarks' => $status. ' 自动下架'
                ]);
                if (!$res) throw new \Exception('房源状态修改失败');
            }
            if (empty($house->save())) throw new \Exception('修改房源状态失败');
            $houseOperationRecords = Common::houseOperationRecords(Common::user()->guid,$request->guid,6, $remarks);
            if (empty($houseOperationRecords)) throw new \Exception('房源其他操作记录添加失败');

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
            $public_private = '';
            if ($request->type == 1) {
                $data['public_private'] = 1;
                $data['guardian_person'] = Common::user()->guid;
                $public_private = '私盘';
            } elseif ($request->type == 2) {
                $data['public_private'] = 2;
                $public_private = '公盘';
            }

            $houseStatus = House::where('guid',$request->guid)->update($data);
            if (empty($houseStatus)) throw new \Exception('修改房源状态失败');
            $remarks = '将房源转为有效房源,并设置为:'.$public_private;
            $houseOperationRecords = Common::houseOperationRecords(Common::user()->guid,$request->guid,6, $remarks);
            if (empty($houseOperationRecords)) throw new \Exception('房源其他操作记录添加失败');
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }
    
    // 获取业主信息
    public function getOwnerInfo($request)
    {
        \DB::beginTransaction();
        try {
            $house = House::where('guid',$request->guid)->with('guardianPerson')->first();
            if (empty($house)) throw new \Exception('获取业主信息失败');

            // 判断是否有权限
            if ($house->public_private == 1) {
                // 获取私盘业主信息
                $ownerInfo = Access::adoptGuardianPersonGetHouse('private_owner_info');
                if (!in_array($request->guid, $ownerInfo)) {
                    // 无权限
                    $data = [
                        [
                            'name' => $house->guardianPerson->name,
                            'tel' => $house->guardianPerson->tel,
                        ],
                            'type' => 2
                    ];
                } else {
                    $data = [
                        'data' => $house->owner_info,
                        'type' => 1
                    ];
                };
            } else {
                $data = [
                    'data' => $house->owner_info,
                    'type' => 1
                ];
            }

            $houseOperationRecords = Common::houseOperationRecords(Common::user()->guid, $request->guid,4,'查看了房源的业主信息');
            if (empty($houseOperationRecords)) throw  new \Exception('查看业主信息添加操作记录失败');

            \DB::commit();
            return $data;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 获取门牌号
    public function getHouseNumber($request)
    {
        \DB::beginTransaction();
        try {
            $house = House::where('guid',$request->guid)->first();
            $data = [];
            if (empty($house->buildingBlock->unit)) {
                $data['house_number'] = $house->buildingBlock->name.$house->buildingBlock->name_unit.' '.$house->house_number;
            } else {
                $data['house_number'] = $house->buildingBlock->name.$house->buildingBlock->name_unit.' '.$house->buildingBlock->unit.$house->buildingBlock->unit_unit.' '.$house->house_number;
            }
            if (empty($data)) throw new \Exception('获取门牌号失败');

            $houseOperationRecords = Common::houseOperationRecords(Common::user()->guid,$request->guid,4,'查看了房源的门牌号信息');
            if (empty($houseOperationRecords)) throw new \Exception('查看门牌号添加操作记录失败');
            \DB::commit();
            return $data;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 获取房源动态
    public function getDynamic($request)
    {
        $res = HouseOperationRecord::with(['user:guid,name,tel', 'visit.accompanyUser'])->where('house_guid', $request->house_guid);
        if (!empty($request->type)) $res = $res->where('type', $request->type);
        $res = $res->latest()->get();
        // 判断是否允许编辑
        foreach ($res as $v) {
            if (empty($request->type) || $request->type == 2) {
                $v->accompanyUser = empty($v->visit)?'':$v->visit->accompanyUser->name;
                $v->visitCustomer = empty($v->visit)?'':$v->visit->visitCustomerHouse->customer_info[0]['name'];
            }

            if ($v->type = 1) {
                $v->operation = false;
                if (time() - strtotime($v->created_at->format('Y-m-d H:i')) <= 60 * 30) {
                    if ($v->user_guid == Common::user()->guid) {
                        $v->operation = true;
                    }
                }
            }
        }

        return $res;
    }

    // 修改房源图片
    public function updateImg(
        $request,
        $picPerson
    )
    {
        \DB::beginTransaction();
        try {
            $data = [
                'house_type_img' => empty($request->house_type_img)?null:json_encode($request->house_type_img),
                'indoor_img' => empty($request->indoor_img)?null:json_encode($request->indoor_img),
                'outdoor_img' => empty($request->outdoor_img)?null:json_encode($request->outdoor_img),
            ];

            // 如果图片跟图片人都为空则为图片人
            if ((!empty($request->house_type_img) || !empty($request->indoor_img) || !empty($request->outdoor_img)) && empty($picPerson)) {
                $data['pic_person'] = Common::user()->guid;
            } elseif (empty($request->house_type_img) && empty($request->indoor_img) && empty($request->outdoor_img)) {
                $data['pic_person'] = '';
            }

            $house = House::where(['guid' => $request->guid])->update($data);
            if (empty($house)) throw new \Exception('房源编辑图片失败');

            $img = array_merge($request->house_type_img, $request->indoor_img, $request->outdoor_img);
            $houseOperationRecords = Common::houseOperationRecords(Common::user()->guid, $request->guid, 3,'修改了图片', $img);
            if (empty($houseOperationRecords)) throw new \Exception('编辑图片添加操作记录失败');

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 房源权限
    public function propertyPermission($house)
    {
        $permission['edit_owner_info'] = true;//是否允许修改删除房源业主信息
        $permission['update_house_number'] = true;//是否允许修改房源门牌号
        $permission['update_house_grade'] = true;//是否允许修改房源等级
        $permission['update_house_price'] = true;//是否允许修改房源价格
        $permission['update_house_other'] = true;//是否允许修改房源其他信息
        $editOwnerInfo = Access::adoptGuardianPersonGetHouse('edit_owner_info');
        if (!in_array($house->guid,$editOwnerInfo)) {
            $permission['edit_owner_info'] = false;
        }

        $updateHouseNumber = Access::adoptGuardianPersonGetHouse('update_house_number');
        if (!in_array($house->guid,$updateHouseNumber)) {
            $permission['update_house_number'] = false;
        }

        $updateHouseGrade = Access::adoptGuardianPersonGetHouse('update_house_grade');
        if (!in_array($house->guid,$updateHouseGrade)) {
            $permission['update_house_grade'] = false;
        }

        $updateHousePrice = Access::adoptGuardianPersonGetHouse('update_house_price');
        if (!in_array($house->guid,$updateHousePrice)) {
            $permission['update_house_price'] = false;
        }

        $updateHouseOther = Access::adoptGuardianPersonGetHouse('update_house_other');
        if (!in_array($house->guid,$updateHouseOther)) {
            $permission['update_house_other'] = false;
        }

        return $permission;
    }

    // 发布共享房源
    public function shareHouse($request)
    {
        $user = Common::user();
        \DB::beginTransaction();
        try {

            if ($request->type) {
                $release_source = '平台';
                $remarks = '平台 发布共享';
            } else {
                $release_source = $user->company_guid;
                $remarks = $user->name. '-'. optional($user->role)->name.' 发布共享';
            }

          $res = House::where(['guid' => $request->guid, 'status' => 1])->update([
              'release_source' => $release_source,
              'share' => 1,
              'share_time' => date('Y-m-d H:i:s', time())
          ]);
          if (!$res) throw new \Exception('房源共享失败');
          $record = HouseShareRecord::create([
              'guid' => Common::getUuid(),
              'house_guid' => $request->guid,
              'remarks' => $remarks
          ]);
          if (!$record) throw new \Exception('房源共享记录添加失败');

          \DB::commit();
          return true;
        } catch (\Exception $exception) {
          \DB::rollback();
          \Log::error('房源共享失败'.$exception->getMessage());
          return false;
        }
    }


    // 共享房源下架
    public function unShare($request)
    {
        $user = Common::user();

        \DB::beginTransaction();
        try {
            if ($request->type) {
                $lower_frame = 1;
                $remarks = '平台 下架';
            } else {
                $lower_frame = 2;
                $remarks = $user->name. '-'. optional($user->role)->name.' 下架';
            }

            $res = House::where('guid', $request->guid)->update([
                'release_source' => null,
                'share' => 2,
                'lower_frame' => $lower_frame,
                'share_time' => date('Y-m-d H:i:s', time())
            ]);
            if (!$res) throw new \Exception('房源下架失败');

            $record = HouseShareRecord::create([
                'guid' => Common::getUuid(),
                'house_guid' => $request->guid,
                'remarks' => $remarks
            ]);

            if (!$record) throw new \Exception('房源共享记录添加失败');
            \DB::commit();
            return $record;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('房源下架失败'.$exception->getMessage());
            return false;
        }
    }

    public function getHouse(
        $house,
        $request
    )
    {
        // 搜索查询
        if ($request->type && $request->condition) {
            if ($request->type == 1) {
                    // 获取下级用户
                    $user = Access::getUser(Common::user()->role->level);

                    $house = $house->where('public_private', 2)
                        ->whereRaw("JSON_CONTAINS(owner_info->'$[*].tel', '\"$request->condition\"', '$')")
                        ->orWhere('public_private', 1)
                        ->whereIn('guardian_person', $user)
                        ->whereRaw("JSON_CONTAINS(owner_info->'$[*].tel', '\"$request->condition\"', '$')");
            } elseif ($request->type == 2) {
                $house = $house->where('house_identifier', 'like', '%' . $request->condition . '%');
            } elseif ($request->type == 3) {
                $building = Building::with('buildingBlock')->where('name','like', '%' . $request->condition . '%')->get();
                $buildingBlockGuid = array();
                foreach ($building as $v) {
                    foreach ($v->buildingBlock as $val) {
                        $buildingBlockGuid[] = $val->guid;
                    }
                }
                $house = $house->whereIn('building_block_guid', $buildingBlockGuid);
            } elseif ($request->type == 4) {
                $company_guid = Company::where('name', 'like', '%'.$request->condition.'%')->pluck('guid')->toArray();
                $house = $house->whereIn('company_guid', $company_guid);
            } elseif ($request->type == 5) {
                $house = $house->where('house_number', $request->condition);
            }
        }

        // 状态
        if ($request->status) {
            if ($request->status == 2) {
                $house = $house->whereIn('status',[3,4,5,6,7]);
            } else {
                $house = $house->where('status', $request->status);
            }
        }

        // 盘别
        if ($request->disk) {
            $house = $house->where('public_private', $request->disk);
        }

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
            $house = $house->whereIn('building_block_guid', $buildingBlockGuid);
        }

        // 面积
        if ($request->area) {
            $acreage = explode('-', $request->area);
            $house = $house->whereBetween('acreage', $acreage);
        }

        // 价格
        if ($request->price) {
            $price = explode('-', $request->price);
            $house = $house->whereBetween('price', $price);
        }

        // 付款方式
        if ($request->paymode) {
            $house = $house->where('payment_type', $request->paymode);
        }

        // 最短租期
        if ($request->shortestLease) {
            $house = $house->where('shortest_lease', $request->shortestLease);
        }

        // 等级
        if ($request->grade) {
            $house = $house->where('grade', $request->grade);
        }

        // 标签
        if ($request->label) {
            // 有图
            if ($request->label == 1) {
//                $house = $house->where('house_type_img','!=',null)->where('indoor_img','!=',null)->where('outdoor_img','!=',null);

                $house = $house->whereNotNull('house_type_img')->whereNotNull('indoor_img')->whereNotNull('outdoor_img');
            }

            // 有钥匙
            if ($request->label == 2) {
                $house = $house->where('have_key',1);
            }

            // 可注册公司
            if ($request->label == 3) {
                $house = $house->where('register_company',1);
            }

            // 可开发票
            if ($request->label == 4) {
                $house = $house->where('open_bill',1);
            }
        }

        // 楼层
        if ($request->floor) {
            $floor = explode('-', $request->floor);
            $house = $house->whereBetween('floor', $floor);
        }

        // 朝向
        if ($request->orientation) {
            $house = $house->where('orientation', $request->orientation);
        }

        // 装修
        if ($request->renovation) {
            $house = $house->where('renovation', $request->renovation);
        }

        // 类型
        if ($request->builType) {
            $house = $house->where('type', $request->builType);
        }

        // 配套(json查询)
        if ($request->supportFacilities) {
            $name = "[\"$request->supportFacilities\"]";
            $house = $house->whereRaw("JSON_CONTAINS(support_facilities,'".$name."')");
        }
        return $house->paginate($request->per_page??10);
    }

    // 删除房源
    public function delHouse($house)
    {
        \DB::beginTransaction();
        try {
            $res = $house->delete();
            if (!$res) throw new \Exception('房源删除失败');

            // 删除操作记录
            if (!$house->record->isEmpty()) {
                $suc = HouseOperationRecord::where('house_guid', $house->guid)->delete();
                if (!$suc) throw new \Exception('房源操作记录添加失败');
            }
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('删除失败'.$exception->getMessage());
            return false;
        }
    }
    
}
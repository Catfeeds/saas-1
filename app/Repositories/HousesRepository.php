<?php
namespace App\Repositories;

use App\Handler\Access;
use App\Handler\Common;
use App\Models\ClwHouse;
use App\Models\House;
use App\Services\HousesService;
use Illuminate\Database\Eloquent\Model;

class HousesRepository extends Model
{
    //房源列表
    public function houseList($request, $service, $guardianPerson)
    {
        $house = House::with('track', 'entryPerson', 'track.user','buildingBlock', 'buildingBlock.building')->where('company_guid', Common::user()->company_guid)->orderBy('top','asc')->orderBy($request->sortKey,$request->sortValue);
        // 范围
        if ($request->range) {
            if ($request->range == 4) {
                $guardian_person[] = Common::user()->guid;
            } elseif ($request->range == 1 || $request->range == 2 || $request->range == 3) {
                $guardian_person = Access::getCompanyRange($request->range);
            } elseif ($request->range == 5) {
                $guardian_person = Access::getUser(1);
            }
            $house = $house->whereIn('guardian_person', $guardian_person);
        } else {
            $house = $house->whereIn('guardian_person', $guardianPerson);
        }

        $data = $service->getHouse($house, $request);
        $houses = [];
        foreach ($data as $key => $v) {
            $houses[$key] = $service->getData($v);
        }
        return $data->setCollection(collect($houses));
    }

    // 添加房源
    public function addHouse($request)
    {
        \DB::beginTransaction();
        try {
            // 获取最后一条数据
            $lastHouse = House::orderBy('created_at', 'asc')->get()->last();
            // 房源编号
            $houseIdentifier = Common::identifier($lastHouse);

            $house = House::create([
                'guid' => Common::getUuid(),
                'house_type' => 1,
                'company_guid' => Common::user()->company_guid,
                'house_identifier' => $houseIdentifier,
                'owner_info' => $request->owner_info,//业主电话
                'floor' => $request->floor,//所在楼层
                'house_number' => $request->house_number,//房号
                'building_block_guid' => $request->building_block_guid,//楼座guid
                'grade' => $request->grade,//房源等级
                'public_private' => $request->public_private,//盘别
                'price' => $request->price,//租金
                'payment_type' => $request->payment_type,//付款方式
                'increasing_situation_remark' => $request->increasing_situation_remark,//递增情况
                'cost_detail' => $request->cost_detail,//费用明细
                'acreage' => $request->acreage,//面积
                'split' => $request->split,//可拆分
                'mini_acreage' => $request->mini_acreage,//最小面积
                'floor_height' => $request->floor_height,//层高
                'register_company' => $request->register_company,//注册公司
                'type' => $request->type,//写字楼类型
                'orientation' => $request->orientation,//朝向
                'renovation' => $request->renovation,//装修
                'open_bill' => $request->open_bill,//可开发票
                'station_number' => $request->station_number,//工位数量
                'rent_free' => $request->rent_free,//免租期
                'support_facilities' => $request->support_facilities,//配套
                'source' => $request->source,//渠道来源
                'actuality' => $request->actuality,//现状
                'shortest_lease' => $request->shortest_lease,//最短租期
                'remarks' => $request->remarks,//备注
                'entry_person' => Common::user()->guid,
                'guardian_person' => Common::user()->guid,
                'track_time' => date('Y-m-d H:i:s',time()),  // 第一次跟进时间
            ]);
            if (empty($house)) throw new \Exception('房源添加失败');

            $housesService = new HousesService();
            $request->offsetSet('guid', $house->guid);

            // 房源共享
            if ($request->share == 1) {
                $share = $housesService->shareHouse($request);
                if (empty($share)) throw new \Exception('房源共享失败');

                $house->share = 1;
                if (!$house->save()) throw new \Exception('修改房源-房源共享失败');
            }

            // 房源上线
            if ($request->online == 2) {
                $online = $housesService->onlineHouse($request);
                if (empty($online)) throw new \Exception('房源上线失败');

                $house->online = 2;
                if (!$house->save()) throw new \Exception('修改房源-房源上线失败');
            }

            \DB::commit();
            return $house;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 更新房源
    public function updateHouse(
        $house,
        $request,
        $permission,
        $service
    )
    {
        \DB::beginTransaction();
        try {
            // 有修改业主信息权限
            if ($permission['edit_owner_info']) {
                $house->owner_info = $request->owner_info;//业主信息
            }
            // 有修改门牌号权限
            if ($permission['update_house_number']) {
                $house->floor = $request->floor;//楼层
                $house->house_number = $request->house_number;//房号
                $house->building_block_guid = $request->building_block_guid;//楼座
            }
            // 有修改房源等级权限
            if ($permission['update_house_grade']) {
                $house->grade = $request->grade;//房源等级
            }
            // 有修改房源价格权限
            if ($permission['update_house_price']) {
                $house->price = $request->price;//租金
            }
            // 有修改其他信息权限
            if ($permission['update_house_other']) {
                $house->house_type = 1;
                $house->public_private = $request->public_private;
                $house->payment_type = $request->payment_type;
                $house->increasing_situation_remark = $request->increasing_situation_remark;
                $house->cost_detail = $request->cost_detail;
                $house->acreage = $request->acreage;
                $house->split = $request->split;
                $house->mini_acreage = $request->mini_acreage;
                $house->floor_height = $request->floor_height;
                $house->register_company = $request->register_company;
                $house->type = $request->type;
                $house->orientation = $request->orientation;
                $house->renovation = $request->renovation;
                $house->open_bill = $request->open_bill;
                $house->station_number = $request->station_number;
                $house->rent_free = $request->rent_free;
                $house->support_facilities = $request->support_facilities;
                $house->source = $request->source;
                $house->actuality = $request->actuality;
                $house->shortest_lease = $request->shortest_lease;
                $house->remarks = $request->remarks;
            }
            $house->guardian_person = Common::user()->guid;
            if (!$house->save()) throw new \Exception('房源修改失败');

            if ($house->online == 2) {
                $clwHouse = ClwHouse::where('guid',$house->guid)->first();
//                $clwHouse->house_identifier = $request->house_identifier;
                $clwHouse->building_block_guid = $request->building_block_guid;
//                $clwHouse->building_guid = $request->buildingBlock->building_guid;
                $clwHouse->house_number = $request->house_number;
                $clwHouse->title = $service->getTitle($request);
                $clwHouse->owner_info = $request->owner_info;
                $clwHouse->constru_acreage = $request->acreage;
                $clwHouse->split = $request->split;
                $clwHouse->min_acreage = $request->mini_acreage;
                $clwHouse->floor = $request->floor;
                $clwHouse->station_number = $request->station_number;
                $clwHouse->office_building_type = $request->type;
                $clwHouse->register_company = $request->register_company;
                $clwHouse->open_bill = $request->open_bill;
                $clwHouse->renovation = $request->renovation;
                $clwHouse->orientation = $request->orientation;
                $clwHouse->support_facilities = $request->support_facilities;
                $clwHouse->house_description = $request->remarks;
                $clwHouse->unit_price = $request->price;
                $clwHouse->total_price = $request->price * $request->acreage;
                $clwHouse->payment_type = $request->payment_type;
                $clwHouse->shortest_lease = $request->shortest_lease;
                $clwHouse->rent_free = $request->rent_free;
                $clwHouse->increasing_situation_remark = $request->increasing_situation_remark;
                $clwHouse->cost_detail = $request->cost_detail??array();
                $clwHouse->house_busine_state = $request->actuality;

                if (!$clwHouse->save()) throw new \Exception('上线房源同步数据修改失败');
            }

            \DB::commit();
            return true;

        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('修改房源失败'.$exception->getMessage());
            return false;
        }

    }

    // 变更人员
    public function changePersonnel($request)
    {
        $house = House::where(['guid' => $request->house_guid])->first();
        if (empty($house)) return ['status' => false, 'message' => '暂无权限'];
        if ($request->entry_person) {
             $house->entry_person = $request->entry_person;
        } elseif($request->guardian_person) {
             $house->guardian_person = $request->guardian_person;
        } elseif($request->pic_person) {
             $house->pic_person = $request->pic_person;
        } elseif($request->key_person) {
             $house->key_person = $request->key_person;
             $house->have_key = 1;
        }
        if (!$house->save()) return ['status' => false, 'message' => '人员变更失败'];
        return ['status' => true, 'message' => '人员变更成功'];
    }

    // 房源置顶
    public function setTop($request)
    {
        return House::where(['guid' => $request->guid])->update(['top'
        => 1]);
    }
    
    // 取消置顶
    public function cancelTop($request)
    {
        return House::where('guid',$request->guid)->update(['top' => 2]);
    }
    
   // 通过楼座，楼层获取房源信息
    public function adoptAssociationGetHouse($request)
    {
        $house = House::where([
            'company_guid' => Common::user()->company_guid,
            'building_block_guid' => $request->building_block_guid
        ]);

        if ($request->floor) {
            $house = $house->where('floor', $request->floor);
        }
        return $house->get();
    }
    
    // 转移房源
    public function transferHouse($request)
    {
        return House::where('guid',$request->guid)->update(['guardian_person'=> $request->user_guid]);
    }

    // 转为公盘
    public function changeToPublic($request)
    {
         return House::where('guid',$request->guid)->update(['public_private' => 2]);
    }
    
    // 转为私盘
    public function switchToPrivate($request)
    {
        return House::where('guid',$request->guid)->update(['public_private'=> 1 ]);
    }
    
    // 修改证件图片
    public function relevantProves($request)
    {
        \DB::beginTransaction();
        try {
            $res = House::where('guid',$request->guid)->update(['relevant_proves_img' => json_encode($request->relevant_proves_img)]);
            $suc = Common::houseOperationRecords(Common::user()->guid, $request->guid, 6,'编辑了相关证件图片', $request->relevant_proves_img);
            if (!$suc) throw new \Exception('房源操作记录添加失败');
            \DB::commit();
            return $res;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('证件上传失败'.$exception->getMessage());
            return false;
        }
    }
}
<?php

namespace App\Services;

use App\Handler\Access;
use App\Handler\Common;
use App\Models\User;

class BusinessManageService
{
    public function BusinessList($request)
    {
        // 获取当前查看所有角色
        $usersGuid = Access::getUser(Common::user()->role->level);

        $users = User::whereIn('guid', $usersGuid);

        if ($request->name) {
            $users = $users->where('name', 'like', '%'.$request->name.'%');
        }

        if ($request->area_guid || $request->storefront_guid || $request->group_guid) {
            $companyFrameworksService = new CompanyFrameworksService();
            $guid = $companyFrameworksService->getUserAdoptCondition($request);
            $users = $users->whereIn('guid', $guid);
        } else {
            $users = $users->where('company_guid', Common::user()->company_guid);
        }

        if (empty($request->created_at)) {
            $request->offsetSet('created_at', [date('Y-m-d', strtotime('now')), date('Y-m-d', strtotime('+1day'))]);
        }

        // 获取所有角色相关信息
        $users = $users->withCount(['house' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['customer' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['houseTrack' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['customerTrack' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['houseVisit' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['customerVisit' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['seeHouseWay' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['recordImg' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['recordHouseNumber' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['recordOwnerInfo' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->paginate($request->per_page ?? 10);

        return $users;
    }
    
    
}
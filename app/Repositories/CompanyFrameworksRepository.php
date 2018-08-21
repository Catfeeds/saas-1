<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\CompanyFramework;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CompanyFrameworksRepository extends Model
{

    protected $user;

    public function __construct()
    {
        $this->user = Common::user();
    }

    public function getList($request)
    {
        //查询登录人公司全部人员
        $user = User::where('company_guid', $this->user->guid);
        //查出一级划分
        $area = CompanyFramework::where([
            'company_guid' => $this->user->company_guid,
            'parent_guid' => '',
            'level' => 1
        ])->get();
        dd($area);
    }

    //新增片区
    public function newArea($request)
    {
        \DB::beginTransaction();
        //查询门店的guid在公司组织架构表中的数据
        try {
           $area =  CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
               'level' => $request->level,
           ]);
        if (empty($area)) throw new \Exception('片区添加失败');

        $add_area = CompanyFramework::whereIn('guid',$request->arr)->all();

        if (!empty($add_area)) {
            foreach ($add_area as $v) {
                $res = CompanyFramework::create(['parent_guid' => $v->guid]);
            }
        }
        if (empty($area) && empty($res)) return true;
        \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    //新增门店
    public function newStore($request)
    {
        \DB::beginTransation();
        try {
            $store = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'level' => $request->level,
            ]);
            $add_store = CompanyFramework::whereIn('guid',$request->arr)->all();
            foreach ($add_store as $v) {
                if (empty($request->parent_guid)) {
                    $res = CompanyFramework::create(['parent_guid' => $v->guid]);
                } else {
                    $res = CompanyFramework::where(['parent_guid' => $request->parent_guid])->update(['parent_guid' =>
                        $v->guid]);
                }
            }
            if (empty($store) && empty($res)) return true;
            \DB::commit();
        }catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    //新增分组
    public function newGroup($request)
    {
        \DB::beginTransation();
        try {
            $group = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'level' => $request->level,
            ]);
            $add_group = CompanyFramework::whereIn('guid',$request->arr)->all();
            foreach ($add_group as $v) {
                if (empty($request->parent_guid)) {
                    $res = CompanyFramework::create(['parent_guid' => $v->guid]);
                } else {
                    $res = CompanyFramework::where(['parent_guid' => $request->parent_guid])->update(['parent_guid' =>
                        $v->guid]);
                }
            }
            if (empty($group) && empty($res)) return true;
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }
}
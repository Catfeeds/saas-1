<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use Illuminate\Http\Request;

class CustomersController extends APIBaseController
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    // 获取所有商圈
    public function allBuildingBlock()
    {
        $res = curl('http://192.168.0.198:0908/api/all_building_blocks','GET');
        return $res = $this->sendResponse($res,'所有商圈获取成功');
        return $res->map(function ($v){
            return [
                'value' => $v->id,
                'name' => $v->name,
            ];
        }) ;
    }

    // 获取所有楼盘
    public function buildingBlocksSelect()
    {
        $res = curl('http://192.168.0.198:0908/api/buildings_select','GET');
        return $this->sendResponse($res,'所有楼盘获取成功');
        return $res->map(function ($v){
            return [
                'value' => $v->value,
                'name' => $v->label,
            ];
        });
    }
}

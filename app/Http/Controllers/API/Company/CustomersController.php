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
    public function allBlock()
    {
        $res =  curl(config('hosts.building').'/api/all_block','GET');
        return $this->sendResponse($res,'获取所有商圈成功');
    }

    // 获取所有楼盘
    public function allBuilding()
    {
        $res = curl(config('hosts.building').'/api/all_building','GET');
        return $this->sendResponse($res,'所有楼盘获取成功');
    }
}

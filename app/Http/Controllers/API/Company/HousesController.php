<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\HousesRequest;
use App\Repositories\HousesRepository;
use Illuminate\Http\Request;

class HousesController extends APIBaseController
{
    // 添加房源
    public function store
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->addHouse($request);
        return $this->sendResponse($res,'添加房源成功');
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

    // 获取所有下拉数据
    public function getAllSelect()
    {
        $res = curl(config('hosts.building').'/api/get_all_select','GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }

}

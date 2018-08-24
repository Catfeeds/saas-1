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

    //  curl请求接口
    public function test()
    {
        $res = curl(config('hosts.building').'/api/get_all_select','GET');
        return $this->sendResponse($res,'请求成功');
    }

}

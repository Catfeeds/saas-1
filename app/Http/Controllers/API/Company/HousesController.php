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
        return curl('http://192.168.0.142/api/get_all_select','GET');
    }

}

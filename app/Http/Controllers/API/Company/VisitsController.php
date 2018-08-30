<?php

namespace App\Http\Controllers\API\Company;


use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\VisitsRequest;
use App\Repositories\VisitsRepository;

class VisitsController extends APIBaseController
{
    //房源或客源带看列表
    public function index
    (
        VisitsRepository $repository,
        VisitsRequest $request
    )
    {
        $res = $repository->visitsList($request);
        return $this->sendResponse($res, '带看获取成功');
    }

    //添加房源或客源带看
    public function store
    (
        VisitsRepository $repository,
        VisitsRequest $request
    )
    {
        $res = $repository->addVisit($request);
        return $this->sendResponse($res, '带看登记成功');
    }




}

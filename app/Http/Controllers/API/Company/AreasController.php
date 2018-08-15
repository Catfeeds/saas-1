<?php
namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\AreasRequest;
use App\Services\AreasService;

class AreasController extends APIBaseController
{
    public function store(
        AreasRequest $request,
        AreasService $service
    )
    {
        $res = $service->addArea($request);
        if (empty($res)) return $this->sendError('片区添加失败');
        return $this->sendResponse($res,'片区添加成功');
    }



}
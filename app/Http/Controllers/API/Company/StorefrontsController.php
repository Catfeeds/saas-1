<?php
namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\StorefrontsRequest;
use App\Services\StorefrontsService;

class StorefrontsController extends APIBaseController
{
    public function store(
        StorefrontsRequest $request,
        StorefrontsService $service
    )
    {
        $res = $service->addStorefront($request);
        if (empty($res)) return $this->sendError('门店添加失败');
        return $this->sendResponse($res,'门店添加成功');
    }

}
<?php
namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\GroupsRequest;
use App\Services\GroupsService;

class GroupsController extends APIBaseController
{
    public function store(
        GroupsRequest $request,
        GroupsService $service
    )
    {
        $res = $service->addGroup($request);
        if (empty($res)) return $this->sendError('分组添加失败');
        return $this->sendResponse($res,'分组添加成功');
    }

}
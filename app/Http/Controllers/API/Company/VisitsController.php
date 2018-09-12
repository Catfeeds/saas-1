<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\VisitsRequest;
use App\Models\Visit;
use App\Repositories\VisitsRepository;
use App\Services\VisitsService;

class VisitsController extends APIBaseController
{
    // 房源或客源带看列表
    public function index
    (
        VisitsRepository $repository,
        VisitsRequest $request,
        VisitsService $service
    )
    {
        $res = $repository->visitsList($request, $service);
        return $this->sendResponse($res, '带看获取成功');
    }

    // 添加房源或客源带看
    public function store
    (
        VisitsService $service,
        VisitsRequest $request
    )
    {
        $res = $service->addVisit($request);
        if (empty($res)) return $this->sendError('带看登记失败');
        return $this->sendResponse($res, '带看登记成功');
    }

    // 编辑带看
    public function update
    (
        VisitsService $service,
        VisitsRequest $request,
        Visit $visit
    )
    {
        $res = $service->updateVisit($request, $visit);
        if (!$res) return $this->sendError('带看修改失败');
        return $this->sendResponse($res, '带看修改成功');
    }


    // 客源删除带看
    public function destroy
    (
        Visit $visit,
        VisitsService $service
    )
    {
        $res = $service->delVisit($visit);
        if (!$res['status']) return $this->sendError($res['message']);
        return $this->sendResponse(true, $res['message']);
    }




}

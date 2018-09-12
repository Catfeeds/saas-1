<?php
namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\CompaniesRequest;
use App\Models\Company;
use App\Repositories\CompaniesRepository;

class CompanyController extends APIBaseController
{

    // 公司列表
    public function index
    (
        CompaniesRepository $repository
    )
    {
        $res = $repository->getList();
        return $this->sendResponse($res,'公司列表获取成功');
    }
    // 添加公司
    public function store
    (
        CompaniesRequest $request,
        CompaniesRepository $repository
    )
    {
        $res = $repository->addCompany($request);
        if ($res) return $this->sendResponse($res,'添加公司成功');
        return $this->sendError('添加公司失败');
    }

    // 编辑公司
    public function update
    (
        CompaniesRequest $request,
        CompaniesRepository $repository,
        Company $company
    )
    {
        $res = $repository->updateCompany($request,$company);
        if ($res) return $this->sendResponse($res,'修改公司成功');
        return $this->sendError('修改公司失败');
    }

    // 获取城市区域
    public function getArea()
    {
        $res = curl(config('hosts.building').'/api/get_all_select?number=2','GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }
}
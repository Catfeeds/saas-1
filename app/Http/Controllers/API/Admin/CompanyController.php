<?php
namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\CompaniesRequest;
use App\Models\Company;
use App\Repositories\CompaniesRepository;
use App\Services\QuartersService;

class CompanyController extends APIBaseController
{

    // 公司列表
    public function index
    (
        CompaniesRequest $request,
        CompaniesRepository $repository
    )
    {
        $res = $repository->getList($request);
        return $this->sendResponse($res,'公司列表获取成功');
    }
    // 添加公司
    public function store
    (
        CompaniesRequest $request,
        CompaniesRepository $repository,
        QuartersService $service
    )
    {
        $res = $repository->addCompany($request,$service);
        if ($res) return $this->sendResponse($res,'添加公司成功');
        return $this->sendError('添加公司失败');
    }

    // 修改之前原始数据
    public function edit
    (
        Company $company
    )
    {
        $company->username = $company->user->name;
        $company->tel = $company->user->tel;
        $company->remarks = $company->user->remarks;
        return $this->sendResponse($company,'修改之前原始数据获取成功');
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

    // 获取城市区域下拉数据
    public function getArea()
    {
        $res = curl(config('hosts.building').'/api/get_all_select?number=2','GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }

    // 账户启用状态
    public function enabledState
    (
        CompaniesRequest $request,
        CompaniesRepository $repository
    )
    {
        $res = $repository->enabledState($request);
        if (!$res) return $this->sendError('修改用户状态失败');
        return $this->sendResponse($res,'修改账户状态成功');
    }
}
<?php
namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\CompaniesRequest;
use App\Models\Company;
use App\Repositories\CompaniesRepository;

class CompanyController extends APIBaseController
{
    // 添加公司
    public function store
    (
        CompaniesRequest $request,
        CompaniesRepository $repository
    )
    {
        $res = $repository->addCompany($request);
        dd($res);
        return $this->sendResponse($res,'添加公司成功');
    }

    // 修改公司信息
    public function update
    (
        CompaniesRequest $request,
        Company $company,
        CompaniesRepository $repository
    )
    {
        $res = $repository->updateCompay($request,$company);
        return $this->sendResponse($res,'修改公司信息成功');
    }
    
    // 修改之前公司原始数据
    public function edit(Company $company)
    {
        return $this->sendResponse($company,'获取修改前原始数据成功');
    }

    // 删除公司信息
    public function destroy
    (
        Company $company,
        CompaniesRepository $repository
    )
    {
        $res = $repository->delCompany($company);
        return $this->sendResponse($res,'删除公司信息成功');
    }
}
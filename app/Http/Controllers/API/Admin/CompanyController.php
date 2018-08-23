<?php
namespace App\Http\Controllers\API\Admin;

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
        if ($res) return $this->sendResponse($res,'添加公司成功');
        return $this->sendError($res,'添加公司失败');
    }

}
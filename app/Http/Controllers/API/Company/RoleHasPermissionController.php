<?php
namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Repositories\RoleHasPermissionsRepository;

class RoleHasPermissionController extends APIBaseController
{
    // 岗位设置列表
    public function index(
        RoleHasPermissionsRepository $repository
    )
    {
        $res = $repository->roleHasPermissionList();
        return $this->sendResponse($res,'获取岗位设置列表成功');
    }

}
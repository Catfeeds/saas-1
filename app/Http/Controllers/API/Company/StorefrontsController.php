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
        if (empty($res))

    }



}
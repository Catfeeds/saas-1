<?php

namespace App\Services;


use App\Handler\Common;
use App\Models\Area;
use App\Models\RelStorefront;

class AreasService
{
    public function addArea(
        $request
    )
    {
        \DB::beginTransaction();
        try {
            $area = Area::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'company_guid' => ''    // TODO  登录人guid
            ]);
            if (empty($area)) throw new \Exception('片区添加失败');

            if (!empty($request->storefronts_guid)) {
                foreach ($request->storefronts_guid as $v) {
                    $rel = RelStorefront::create([
                        'guid' => Common::getUuid(),
                        'storefronts_guid' => $v,
                        'rel_guid' => $area->guid,  // 片区guid
                        'model_type' => 'App\Models\Area',  // 片区model
                    ]);
                    if (empty($rel)) throw new \Exception('guid为:'.$v.'的管辖门店添加失败');
                }
            }

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }




    }
}
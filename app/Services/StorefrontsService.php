<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\RelStorefront;
use App\Models\RelUser;
use App\Models\Storefront;

class StorefrontsService
{
    public function addStorefront(
        $request
    )
    {
        \DB::beginTransaction();
        try {
            $storefront = Storefront::create([
                'guid' => Common::getUuid(),
                'name' => $request->name
            ]);
            if (empty($storefront)) throw new \Exception('片区添加失败');

            if (!empty($request->area_guid)) {
                $relStorefront = RelStorefront::create([
                    'guid' => Common::getUuid(),
                    'storefronts_guid' => $storefront->guid,
                    'rel_guid' => $request->area_guid,
                    'model_type' => 'App\Models\Area'
                ]);
                if (empty($relStorefront)) throw new \Exception('片区与门店关联表');
            }

            if (!empty($request->user_guid)) {
                foreach ($request->user_guid as $value) {
                    $relUser = RelUser::create([
                        'user_guid' => $value,
                        'rel_guid' => $storefront->guid,
                        'model_type' => 'App\Models\Storefront'
                    ]);
                    if (empty($relUser)) throw new \Exception('门店与成员关联表');
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
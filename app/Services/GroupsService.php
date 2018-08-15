<?php

namespace App\Services;


use App\Handler\Common;
use App\Models\Group;
use App\Models\RelStorefront;
use App\Models\RelUser;

class GroupsService
{
    public function addGroup(
        $request
    )
    {
        \DB::beginTransaction();
        try {
            $group = Group::create([
                'guid' => Common::getUuid(),
                'name' => $request->name
            ]);
            if (empty($group)) throw new \Exception('分组添加失败');

            $relStorefront = RelStorefront::create([
                'guid' => Common::getUuid(),
                'storefronts_guid' => $request->storefronts_guid,
                'rel_guid' => $group->guid,
                'model_type' => 'App\Models\Group',
            ]);
            if (empty($relStorefront)) throw new \Exception('分组与门店关联表添加失败');

            if (!empty($request->user_guid)) {
                foreach ($request->user_guid as $value) {
                    $relUser = RelUser::create([
                        'guid' => Common::getUuid(),
                        'user_guid' => $value,
                        'rel_guid' => $group->guid,
                        'model_type' => 'App\Models\Storefront'
                    ]);
                    if (empty($relUser)) throw new \Exception('分组与成员关联表');
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
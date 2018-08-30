<?php

namespace App\Services;

class VisitsService
{
    //房源或客源跟进列表
    public function getData($v)
    {
        $data = [];
        //如果是房源
        $data['guid'] = $v->guid;
        $data['visit_user'] = $v->user->name;
        $data['accompany'] = $v->accompany ? $v->accompanyUser->name : '';
        $data['remarks'] = $v->remarks;
        $data['time'] = $v->visit_date;
        $data['visit_img'] = $v->visit_img_cn;
        if ($v->model_type == 'App\Models\Customer') {
            $data['house_guid'] = $v->house->guid;
            $data['house_title'] = $v->house->title;
            $data['house_img'] = $v->house->indoor_img_cn;
        }
        return $data;
    }
    
}
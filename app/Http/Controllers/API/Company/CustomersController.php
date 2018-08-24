<?php

namespace App\Http\Controllers\API\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomersController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    // 获取所有商圈
    public function allBuildingBlock()
    {
        $res = curl('http://192.168.0.198:0908/api/all_building_blocks','GET');
        return $res->map(function ($v){
            return [
                'value' => $v->id,
                'name' => $v->name,
            ];
        }) ;
    }

    // 获取所有楼盘
    public function buildingBlocksSelect()
    {
        $res = curl('http://192.168.0.198:0908/api/buildings_select','GET');
        return $res->map(function ($v){
            return [
                'value' => $v->value,
                'name' => $v->label,
            ];
        });
    }
}

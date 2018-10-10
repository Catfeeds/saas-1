<?php

namespace App\Console\Commands;

use App\Models\House;
use App\Models\HouseImgRecord;
use App\Models\User;
use Illuminate\Console\Command;

class MigrateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateHouse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更改房源图片维护人';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 查询全部的房子
        $house = House::all();
        $data = [];
        foreach ($house as $v) {
            // 匹配图片记录
            if ($v->indoor_img) {
                $indoor_img = json_encode($v->indoor_img);
                $img = HouseImgRecord::whereRaw("JSON_CONTAINS(indoor_img,'".$indoor_img."')")->first();
                if ($img) {
                   // 查询图片人
                    $guid = User::where('tel', $img->user->tel)->value('guid');
                    // 修改房源图片人
                    $v->pic_person = $guid;
                    if (!$v->save()) {
                        $data[] = $v->guid;
                    }
                }

            }
        }
        return $data;
    }
}

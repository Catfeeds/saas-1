<?php

namespace App\Console\Commands;

use App\Handler\Common;
use App\Models\House;
use App\Models\HouseShareRecord;
use Illuminate\Console\Command;

class HouseShare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'houseShare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将10.8号前的全部房源，设置为楚楼云房源';

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
        // 获取10月八号之前房源数据
        $houses = House::whereDate('created_at','<=','2018-10-08')
            ->where('share','1')
            ->orWhereNull('share')
            ->whereDate('created_at','<=','2018-10-08')
            ->get();

        // 设置楚楼云房源
        foreach ($houses as $v) {
            $v->share = 1;
            $v->release_source = '平台';
            $v->share_time = date('Y-m-d H:i:s', time());
            if (!$v->save()) \Log::error('房源guid为:'.$v->guid.'的房源设置失败');

            $record = HouseShareRecord::create([
                'guid' => Common::getUuid(),
                'house_guid' => $v->guid,
                'remarks' => '平台 发布共享'
            ]);
            if (empty($record)) \Log::error('房源guid为:'.$v->guid.'的房源共享记录添加失败');
        }
    }
}

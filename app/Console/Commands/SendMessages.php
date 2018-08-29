<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\House;
use App\Models\Remind;
use App\Models\User;
use Illuminate\Console\Command;

class SendMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送微信消息';

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
       //获取当前时间
        $current_time = date('Y-m-d H:i:s', time());
        //查询已经到期的提醒
        $res = Remind::where('remind_time', '<' , $current_time)->get();
        //拼接数据
        $data = [];
        foreach ($res as $key => $v) {
            $data[$key]['name'] = $v->model_type == 'App\Models\House' ? House::where('guid', $v->rel_guid)->value('title') : Customer::where('guid', $v->rel_guid)->value('guid');
            $data[$key]['info'] = $v->remid_info;
            $data[$key]['openid'] = User::where('guid', $v->user_guid)->value('openid');
            $data[$key]['remark'] = $v->model_type == 'App\Models\Houses' ? '点击查看房源详情' : '点击查看客源详情';
            $data[$key]['model_type'] = $v->model_type;
            $data[$key]['guid'] = $v->rel_guid;
        }

        //远程请求微信

    }
}

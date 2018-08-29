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
        //查询已经到期且未发送消息的提醒
        $remind = Remind::where('remind_time', '<' , $current_time)->where('status', 1)->get();
        //拼接数据
        $item = [];
        if (!$remind->isEmpty()) {
            foreach ($remind as $key => $v) {
                $item[$key][0] = $v->remind_info;  //标题
                $item[$key][1] = $v->model_type == 'App\Models\House' ? House::where('guid', $v->rel_guid)->value('guid') : Customer::where('guid', $v->rel_guid)->value('guid'); //名称
                $item[$key][2] = User::where('guid', $v->user_guid)->value('openid'); //接收人openid
                $item[$key][3] = $v->model_type == 'App\Models\House' ? '点击查看房源详情' : '点击查看客源详情'; //备注
                $item[$key][4] = $v->model_type; //模型
                $item[$key][5] = $v->rel_guid; //模型guid
                $item[$key][6] = $v->guid; //提醒guid
            }
            //远程请求微信
            $data['data'] = json_encode($item);
            $res = curl(config('setting.wechat_url').'/remind_notice','post', $data);
            //将发送成功的提醒的状态更改为已发送
            if (!empty($res)) {
                if ($res->success) {
                    Remind::whereIn('guid', $res->data)->update(['status' => 2]);
                }
            }
        }
    }
}

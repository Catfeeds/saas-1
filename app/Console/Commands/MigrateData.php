<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\House;
use App\Models\User;
use Illuminate\Console\Command;

class MigrateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '离职人员数据处理';

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
        \DB::beginTransaction();
        try {
            // 所有已经离职人员
            $user = User::where('status', 2)->pluck('guid')->toArray();
            // 把房子和客源转移到公盘
            House::whereIn('guardian_person', $user)->update(['public_private', 2]);
            Customer::whereIn('guardian_person', $user)->update(['guest' => 1]);
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('处理失败'.$exception->getMessage());
            return false;
        }
    }
}

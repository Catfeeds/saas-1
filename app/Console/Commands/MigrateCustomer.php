<?php

namespace App\Console\Commands;

use App\Models\MediaUser;
use Illuminate\Console\Command;

class MigrateCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateCustomer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移客源数据';

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
        // 只转移公司客源
        $guardian = MediaUser::where('ascription_store', '!=',6)->pluck('id')->toArray();
//        $customer =
    }
}

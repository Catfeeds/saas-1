<?php

namespace App\Console\Commands;

use App\Models\MediaUser;
use Illuminate\Console\Command;

class MigrateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移人员表';

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

    }
}

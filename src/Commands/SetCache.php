<?php

namespace Betterde\Permission\Commands;

use Illuminate\Console\Command;
use Betterde\Permission\Contracts\PermissionContract;

class SetCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache all permissions to memory';

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
        $model = app(PermissionContract::class);
        $model::fetchAll();
        $this->info('Congratulation! All permissions is cached');
    }
}

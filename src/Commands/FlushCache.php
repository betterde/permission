<?php

namespace Betterde\Permission\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class FlushCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush permissions cache';

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
        $redis = Redis::connection(config('permission.cache.database'));
        $keys = $redis->hkeys(config('permission.cache.prefix') . ':permissions');
        $redis->hdel(config('permission.cache.prefix') . ':permissions', $keys);
        $this->info('Permissions cache is cleared');
    }
}

<?php

namespace Betterde\Permission\Providers;

use Illuminate\Support\ServiceProvider;
use Betterde\Permission\Commands\SetCache;
use Betterde\Permission\Commands\FlushCache;
use Betterde\Permission\Contracts\PermissionContract;

/**
 * Date: 19/04/2018
 * @author George
 * @package Betterde\Role\Providers
 */
class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * 发布数据库迁移文件
         */
        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations'),
            __DIR__.'/../../config/permission.php' => config_path('permission.php')
        ], 'permission');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SetCache::class,
                FlushCache::class,
            ]);
        }

        $this->registerModelBindings();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * 绑定模型到接口
     *
     * Date: 19/04/2018
     * @author George
     */
    protected function registerModelBindings()
    {
        $this->app->bind(RoleContract::class, config('permission.model'));
    }
}

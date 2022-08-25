<?php

namespace Martialbe\LaravelDingtalk;

use Illuminate\Support\ServiceProvider;
use Martialbe\LaravelDingtalk\WorkBot\Application as WorkBot;
use Martialbe\LaravelDingtalk\CustomBot\Application as CustomBot;

class DingtalkServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__.'/config.php') => config_path('dingtalk.php'),
        ]);
    }

    public function register()
    {
        $this->setupConfig();
        $apps = [
            'work_bot'   => WorkBot::class,
            'custom_bot' => CustomBot::class,
        ];
        foreach ($apps as $name => $class) {
            if (empty(config('dingtalk.'.$name))) {
                continue;
            }
            $accounts = config('dingtalk.'.$name);
            foreach ($accounts as $account => $config) {
                $this->app->bind("dingtalk.{$name}.{$account}", function ($laravelApp) use ($name, $account, $config, $class) {
                    $app = new $class(array_merge(config('dingtalk.defaults', []), $config));
                    return $app;
                });
            }
            $this->app->alias("dingtalk.{$name}.default", 'dingtalk.'.$name);
            $this->app->alias('dingtalk.'.$name, $class);
        }
        $this->app->singleton('dingtalk.log', function () {
            $config = config( "dingtalk.log" );
            return \Log::build($config['channels'][$config['default']]);
        });
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/config.php');
        $this->mergeConfigFrom($source, 'dingtalk');
    }

}

<?php

namespace Martialbe\LaravelDingtalk;

use Illuminate\Support\Facades\Facade as LaravelFacade;


class Facade extends LaravelFacade
{
    /**
     * 默认为 Server.
     *
     * @return string
     */
    public static function getWorkBot()
    {
        return 'dingtalk.work_bot';
    }

    /**
     * @param string|array $config
     * @return \Martialbe\LaravelDingtalk\WorkBot\Application
     */
    public static function workBot($name = '')
    {
        if(is_array($name)) {
            return new \Martialbe\LaravelDingtalk\WorkBot\Application(array_merge(config('dingtalk.defaults', []), $name));
        }
        return $name ? app('dingtalk.work_bot.'.$name) : app('dingtalk.work_bot');
    }

    /**
     * @param string|array $config
     * @return \Martialbe\LaravelDingtalk\CustomBot\Application
     */
    public static function CustomBot($name = '')
    {
        if(is_array($name)) {
            return new \Martialbe\LaravelDingtalk\CustomBot\Application(array_merge(config('dingtalk.defaults', []), $name));
        }
        return $name ? app('dingtalk.custom_bot.'.$name) : app('dingtalk.custom_bot');
    }


}

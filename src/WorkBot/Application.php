<?php
namespace Martialbe\LaravelDingtalk\WorkBot;


use Martialbe\LaravelDingtalk\Kernel\ServiceContainer;

/**
 * Class Application
 *
 * @property \Martialbe\LaravelDingtalk\WorkBot\Messages\Client           $messages
 * @property \Martialbe\LaravelDingtalk\WorkBot\InteractiveCards\Client   $interactiveCards
 * @property \Martialbe\LaravelDingtalk\WorkBot\Server\Client             $server
 */
class Application extends ServiceContainer
{


    public function __construct(array $config = [])
    {
        parent::__construct($config);

    }

    public function getAppKey()
    {
        return $this->userConfig['app_key'];
    }

    public function getAppSecret()
    {
        return $this->userConfig['app_secret'];
    }

    public function __get(string $name)
    {
        $client = sprintf('\\Martialbe\\LaravelDingtalk\\WorkBot\\%s\\Client', \Str::studly($name));
        return new $client($this);
    }

}

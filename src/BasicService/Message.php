<?php

namespace Martialbe\LaravelDingtalk\BasicService;

/**
 * Class Message
 * @method static \Martialbe\LaravelDingtalk\BasicService\Messages\BtnsActionMessage  btnsAction(array $options = [], string $format = 'webhook')
 * @method static \Martialbe\LaravelDingtalk\BasicService\Messages\FeedMessage        feed(array $options = [], string $format = 'webhook')
 * @method static \Martialbe\LaravelDingtalk\BasicService\Messages\ImageMessage       image(array $options = [], string $format = 'webhook')
 * @method static \Martialbe\LaravelDingtalk\BasicService\Messages\LinkMessage        link(array $options = [], string $format = 'webhook')
 * @method static \Martialbe\LaravelDingtalk\BasicService\Messages\MarkdownMessage    markdown(array $options = [], string $format = 'webhook')
 * @method static \Martialbe\LaravelDingtalk\BasicService\Messages\SingleActionMessage  singleAction(array $options = [], string $format = 'webhook')
 * @method static \Martialbe\LaravelDingtalk\BasicService\Messages\TextMessage        text(array $options = [], string $format = 'webhook')
 */
class Message
{

    public static function make($name, ...$options)
    {
        $client = sprintf('\\Martialbe\\LaravelDingtalk\\BasicService\\Messages\\%sMessage', \Str::studly($name));

        return new $client(...$options);
    }


    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }



}

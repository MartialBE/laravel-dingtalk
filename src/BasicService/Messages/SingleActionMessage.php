<?php


namespace Martialbe\LaravelDingtalk\BasicService\Messages;


class SingleActionMessage extends BaseMessage
{
    protected $type = 'actionCard';
    protected $useAt = false;
    protected $defined = [
        'title'       => true,
        'text'        => true,
        'singleTitle' => true,
        'singleURL'   => false,
    ];
}

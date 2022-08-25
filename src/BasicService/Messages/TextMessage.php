<?php
namespace Martialbe\LaravelDingtalk\BasicService\Messages;


class TextMessage extends BaseMessage
{
    protected $type = "text";
    protected $useAt = true;
    protected $defined = [
        'content' => true,
    ];

}

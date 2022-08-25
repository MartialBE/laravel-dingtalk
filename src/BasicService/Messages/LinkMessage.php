<?php
namespace Martialbe\LaravelDingtalk\BasicService\Messages;


class LinkMessage extends BaseMessage
{
    protected $type = "link";
    protected $useAt = false;
    protected $defined = [
        'title'      => true,
        'text'       => true,
        'messageUrl' => true,
        'picUrl'     => false,
    ];

}

<?php
namespace Martialbe\LaravelDingtalk\BasicService\Messages;


class MarkdownMessage extends BaseMessage
{
    protected $type = "markdown";
    protected $useAt = true;
    protected $defined = [
        'title' => true,
        'text'  => true,
    ];






}

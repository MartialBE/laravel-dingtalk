<?php
namespace Martialbe\LaravelDingtalk\BasicService\Messages;


class ImageMessage extends BaseMessage
{
    protected $type = "imageMsg";
    protected $useAt = false;
    protected $supportFormat = ['api'];
    protected $defined = [
        'photoURL'      => true,
    ];

}

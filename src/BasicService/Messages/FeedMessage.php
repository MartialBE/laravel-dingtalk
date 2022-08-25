<?php
namespace Martialbe\LaravelDingtalk\BasicService\Messages;

use Martialbe\LaravelDingtalk\Kernel\Exceptions\InvalidArgumentException;

class FeedMessage extends BaseMessage
{
    protected $type = "feedCard";
    protected $supportFormat = ['webhook'];
    protected $useAt = false;
    protected $defined = [
        'links'      => true,
    ];

    protected $linksDefined = [
        'title'      => true,
        'messageURL' => true,
        'picURL'     => true,
    ];

    public function addLinks(array $links)
    {
        foreach ($links as $link) {
            $this->addBtn($link);
        }
        return $this;
    }

    public function addLink(array $link)
    {
        $this->options['links'][] = $link;
        return $this;
    }


    public function verify()
    {
        parent::verify();
        foreach ($this->options['links'] as $link) {
            foreach ($this->linksDefined as $linkKey => $required) {
                if( $required && !isset($link[$linkKey])){
                    throw new InvalidArgumentException("{$linkKey} is required");
                }
            }
        }
    }

}

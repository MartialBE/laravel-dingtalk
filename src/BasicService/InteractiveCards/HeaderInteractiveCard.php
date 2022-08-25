<?php
namespace Martialbe\LaravelDingtalk\BasicService\InteractiveCards;


class HeaderInteractiveCard extends BaseInteractiveCard
{
    protected $defined = [
        "ico"   => false,
        'text'  => true,
        'color' => false
    ];

    /**
     * 设置颜色
     *
     * @param string $light
     * @param string $dark
     * @return self
     */
    public function setColor(string $light, string $dark = "") : self
    {
        $this->options['color'] = [
            'light' => $light,
            'dark'  => $dark ?: $light,
        ];

        return $this;
    }

}

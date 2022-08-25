<?php

namespace Martialbe\LaravelDingtalk\BasicService;

use Martialbe\LaravelDingtalk\Kernel\Exceptions\InvalidArgumentException;
use Martialbe\LaravelDingtalk\BasicService\InteractiveCards\ActionsInteractiveCard;
use Martialbe\LaravelDingtalk\BasicService\InteractiveCards\ContentsInteractiveCard;
use Martialbe\LaravelDingtalk\BasicService\InteractiveCards\HeaderInteractiveCard;
/**
 * Class InteractiveCard
 */
class InteractiveCard
{
    /**
     * 头部
     *
     * @var array
     */
    protected $header = [];

    /**
     * 内容
     *
     * @var array
     */
    protected $contents = [];

    /**
     * 动作
     *
     * @var array
     */
    protected $actions = [];

    /**
     * 按钮方向
     *
     * @var string
     */
    protected $actionDirection;

    public function __construct()
    {

    }

    /**
     * 配置头部
     *
     * @param \Closure|array $config
     * @return self
     */
    public function setHeader( $config ) : self
    {
        $this->header = (new HeaderInteractiveCard($config))->getOption();

        return $this;
    }

    /**
     * 配置内容
     *
     * @param  \Closure|array $config
     * @return self
     */
    public function setContents( $config ) : self
    {
        $this->contents[] = (new ContentsInteractiveCard($config))->getOption();

        return $this;
    }

    /**
     * 配置动作
     *
     * @param \Closure|array $config
     * @return self
     */
    public function setActions( $config ) : self
    {
        $this->actions[] = (new ActionsInteractiveCard($config))->getOption();

        return $this;
    }

    /**
     * 设置按钮方向
     *
     * @param string $actionDirection
     * @return self
     */
    public function setActionDirection(string $actionDirection) : self
    {
        $actionDirection = strtoupper($actionDirection);
        if( !in_array($actionDirection, ['HORIZONTAL', 'VERTICAL']) ) {
            throw new InvalidArgumentException("Action Direction:{$actionDirection}  Not Supported");
        }
        $this->actionDirection = $actionDirection;
        return $this;
    }

    public function toArray()
    {
        return [
            "header"          => $this->header,
            "contents"        => $this->contents,
            "actions"         => $this->actions,
            "actionDirection" => $this->actionDirection
        ];
    }


    public static function __callStatic($name, $arguments)
    {
        return new self($name);
    }

}

<?php
namespace Martialbe\LaravelDingtalk\BasicService\InteractiveCards;


class ActionsInteractiveCard extends BaseInteractiveCard
{
    /**
     * 按钮模式
     */
    const ActionStatus = [
        "NORMAL",     // 正常模式的按钮
        "DISABLED",   // 禁用模式的按钮
        "WARNING",    // 警告模式的按钮
    ];

    const ActionType = [
        "URL",// 跳转类型按钮
        "LWP", // 交互类型按钮
        "DTMD",
    ];

    const ActionUrl = [
        "android",
        "ios",
        "pc",
        "all",
    ];
    protected $defined = [
        "id"                  => true,
        'text'                => true,
        'color'               => false,
        'afterClickText'      => false,
        'status'              => true,
        'actionType'          => true,
        'afterClickActionUrl' => false,
        'dtmdLink'            => false
    ];

    /**
     * id
     * @param string $id
     * @return self
     */
    public function setId(string $id) : self
    {
        $this->options['id'] = $id;
        return $this;
    }

    /**
     * 点击后的按钮文案
     *
     * @param string $content
     * @param string $lang
     * @return self
     */
    public function setAfterClickText( string $content, string $lang = "zh_Hans" ) : self
    {
        return $this->setOptionValue($content, 'afterClickText', $lang, "LANG");
    }

    /**
     * 设置按钮类型状态
     * @param string $status
     * @return self
     */
    public function setStatus(string $status) : self
    {
        $status = strtoupper($status);
        return $this->setOptionValue($status, 'status', "", "ActionStatus");
    }

    /**
     * 设置按钮类型
     * @param string $type
     * @return self
     */
    public function setActionType( string $type ) : self
    {
        $type = strtoupper($type);
        return $this->setOptionValue($type, 'actionType', "", "ActionType");
    }

    /**
     * 设置按钮动作
     * @param string $url
     * @param string $type
     * @return self
     */
    public function setActionUrl( string $url, string $type = "all" ) : self
    {
        return $this->setOptionValue($url, 'actionUrl', $type, "ActionUrl");
    }

    /**
     * 设置按下按钮后按钮的动作
     *
     * @param string $url
     * @param string $type
     * @return self
     */
    public function setAfterClickActionUrl( string $url, string $type = "all" ) : self
    {
        return $this->setOptionValue($url, 'afterClickActionUrl', $type, "ActionUrl");
    }

    /**
     * 设置DTMD链接
     *
     * @param string $link
     * @return self
     */
    public function setDtmdLink(string $link) : self
    {
        return $this->setOptionValue($link, 'dtmdLink');
    }

}

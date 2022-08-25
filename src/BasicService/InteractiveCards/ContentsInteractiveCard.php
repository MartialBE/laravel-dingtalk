<?php
namespace Martialbe\LaravelDingtalk\BasicService\InteractiveCards;

use Martialbe\LaravelDingtalk\Kernel\Exceptions\InvalidArgumentException;

class ContentsInteractiveCard extends BaseInteractiveCard
{
    protected $options = [
        "type" => ""
    ];
    protected $defined = [
        'text'  => true,
        "ico"   => false,
        'type'  => true,
        'image' => false,
    ];

    /**
     * 内容类型
     */
    const ContentType = [
        'PARAGRAPH',     // 普通文本内容
        'TITLE',         // 一级标题
        'DESCRIPTION',   // 一级描述内容
        'IMAGE',         // 图片
        'MARKDOWN',      // markdown
    ];

    /**
     * 设置类型
     *
     * @param string $type
     * @return self
     */
    public function setType( string $type ) : self
    {
        $type = strtoupper($type);
        return $this->setOptionValue($type, 'type', "", "ContentType");
    }

    /**
     * 设置图标
     *
     * @param string $light
     * @param string $dark
     * @return self
     */
    public function setIco(string $light, string $dark = "") : self
    {
        if( in_array($this->options['type'], ["IMAGE", "MARKDOWN"]) ) {
            throw new InvalidArgumentException("Content Type: {$this->options['type']} Not Supported Of ICO");
        }
        return parent::setIco($light, $dark);
    }

    /**
     * 设置文本
     *
     * @param string $content
     * @param string $lang
     * @return self
     */
    public function setText(string $content, string $lang = "zh_Hans") : self
    {
        if( in_array($this->options['type'], ["IMAGE", "MARKDOWN"]) ) {
            $this->defined['text'] = false;
            $key = strtolower($this->options['type']);
            $this->options[$key] = $content;
            return $this;
        }else{
            return parent::setText($content, $lang);
        }
    }

}

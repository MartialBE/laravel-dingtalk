<?php
namespace Martialbe\LaravelDingtalk\BasicService\InteractiveCards;

use Martialbe\LaravelDingtalk\Kernel\Exceptions\InvalidArgumentException;

class BaseInteractiveCard
{
    protected $options;
    /**
     * 语种
     */
    const LANG = [
        "en_US"   => "en_US",   // 英文
        "zh_Hans" => "zh_Hans", // 简体中文
        "zh_Hant" => "zh_Hant", // 繁体中文
        "ja_JP"   => "ja_JP",   // 日文
        "vi_VN"   => "vi_VN",   // 越南文
        "th_TH"   => "th_TH",   // 泰文
        "id_ID"   => "id_ID",   // 印尼文
    ];

    /**
     * @param \Closure|array $config
     */
    public function __construct( $config)
    {
        if($config instanceof \Closure) {
            call_user_func($config, $this);
        }else {
            foreach ($config as $key => $value) {
                $name = "set".ucfirst($key);
                $value = is_string($value) ? [$value] : $value;
                $this->$name( ...$value );
            }
        }
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
        return $this->setOptionValue($content, 'text', $lang, "LANG");
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
        $this->options['ico'] = [
            'light' => $light,
            'dark'  => $dark ?: $light,
        ];

        return $this;
    }

    /**
     * 赋值
     *
     * @param string $value
     * @param string $key
     * @param string $key2
     * @param string $verifyArgument
     * @return self
     */
    public function setOptionValue(string $value, string $key, string $key2 = "", string $verifyArgument = "") : self
    {
        if( $verifyArgument ) {
            $verifyKey = ($key2 ?: $value);
            if(!in_array($verifyKey, constant(get_called_class()."::".$verifyArgument))) {
                throw new InvalidArgumentException("{$verifyArgument}: {$verifyKey} Not Supported");
            }
        }
        if($key2){
            $this->options[$key][$key2] = $value;
        }else{
            $this->options[$key] = $value;
        }

        return $this;
    }

    public function getOption() : array
    {
        $this->verify();
        return $this->options;
    }


    public function verify()
    {
        foreach ($this->defined as $definedKey => $required) {
            if( $required && !isset($this->options[$definedKey])){
                throw new InvalidArgumentException("{$definedKey} is required");
            }
        }
    }
}

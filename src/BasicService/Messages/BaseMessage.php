<?php
namespace Martialbe\LaravelDingtalk\BasicService\Messages;

use Martialbe\LaravelDingtalk\Kernel\Exceptions\InvalidArgumentException;
use Martialbe\LaravelDingtalk\Kernel\Exceptions\Exception;

class BaseMessage
{
    /**
     *
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $format = 'webhook';

    /**
     * @var array
     */
    protected $supportFormat = ['api', 'webhook'];

    /**
     * @var array
     */
    protected $atDefined = [
        'atMobiles',
        'atUserIds',
        'isAtAll',
    ];

    /**
     * @var boolean
     */
    protected $useAt = false;

    public function __construct(array $options = [], string $format = 'webhook')
    {
        if(! in_array($format, $this->supportFormat)){
            throw new InvalidArgumentException('Message type does not support this format');
        }
        $this->format = $format;
        $this->setOptions($options);

    }

    /**
     * @param array $opentions
     * @return $this
     */
    public function setOptions(array $opentions = []) : self
    {
        foreach ($opentions as $key => $value) {
            $this->setOption($key, $value);
        }
        return $this;
    }

    /**
     * @param string $name
     * @param  $value
     * @return $this
     */
    public function setOption( string $name, $value ) : self
    {
        if(!isset($this->defined[$name]) && ( !$this->useAt ||( $this->useAt && !in_array($name, $this->atDefined)))) {
            throw new InvalidArgumentException('Invalid Argument:'.$name);
        }
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getApiFormat() :array
    {
        $data = [
            'msgKey' => "sample".ucfirst($this->type),
            'msgParam' => json_encode($this->getOption())
        ];
        $this->data = $data;
        return $data;
    }

    /**
     * @return array
     */
    public function getWebhookFormat() :array
    {
        $data = [
            'msgtype'   => $this->type,
            $this->type => $this->getOption()
        ];
        if($this->useAt && $at = $this->getAtOption()) {
            $data['at'] = $at;
        }
        $this->data = $data;
        return $data;
    }

    /**
     * @return array
     */
    public function getOption() :array
    {
        $options = [];
        foreach ($this->defined as $definedKey => $required) {
            if(!isset($this->options[$definedKey])) continue;
            $options[$definedKey] = $this->options[$definedKey];
        }
        return $options;
    }

    /**
     * @return array
     */
    public function getAtOption() :array
    {
        $atoption = [];
        foreach ($this->atDefined as $key) {
            if(!isset($this->options[$key])) continue;
            $atoption[$key] = $this->options[$key];
        }
        return $atoption;
    }



    /**
     * 验证必填项
     *
     * @return void
     */
    public function verify()
    {
        foreach ($this->defined as $definedKey => $required) {
            if( $required && !isset($this->options[$definedKey])){
                throw new InvalidArgumentException("{$definedKey} is required");
            }
        }
    }

    /**
     * 获取数组
     *
     * @return array
     */
    public function toArray()
    {
        $this->verify();
        $name = 'get'.ucfirst($this->format).'Format';

        return $this->$name();
    }


    public function __toString()
    {
        return json_encode($this->getContent());
    }

    public function __call($name, $arguments)
    {
        if( \Str::is('set*', $name) ) {

            $name = lcfirst(ltrim($name, 'set'));
            return $this->setOption($name, ...$arguments);
        }
        throw new Exception('Call to undefined method '.$name);
    }

}

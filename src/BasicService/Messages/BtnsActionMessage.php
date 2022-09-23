<?php
namespace Martialbe\LaravelDingtalk\BasicService\Messages;

use Martialbe\LaravelDingtalk\Kernel\Exceptions\InvalidArgumentException;

class BtnsActionMessage extends BaseMessage
{
    protected $type = "actionCard";
    protected $useAt = true;
    protected $maxBtns = 5;
    protected $minBtns = 1;
    protected $defined = [
        'title'          => true,
        'text'           => true,
        'btnOrientation' => false,
        'btns'           => true,
    ];
    protected $btnDefined = [
        'title'     => true,
        'actionURL' => true,
    ];

    /**
     * 配置按钮组
     *
     * @param array $btns
     * @return self
     */
    public function addBtns(array $btns) : self
    {
        foreach ($btns as $btn) {
            $this->addBtn($btn);
        }
        return $this;
    }

    /**
     * 配置按钮
     *
     * @param array $btn
     * @return self
     */
    public function addBtn(array $btn) : self
    {
        $this->options['btns'][] = $btn;
        return $this;
    }


    public function verify()
    {
        parent::verify();
        if(isset($this->options['btnOrientation']) && $this->options['btnOrientation'] == 1 ){
            $this->maxBtns = 2;
            $this->minBtns = 2;
        }
        if($this->format == 'api') {
            $this->minBtns = 2;
        }

        if(count($this->options['btns']) < $this->minBtns || count($this->options['btns']) > $this->maxBtns ) {
            throw new InvalidArgumentException("btn count error. min:{$this->minBtns},max:{$this->maxBtns}");
        }
        foreach ($this->options['btns'] as $btn) {
            foreach ($this->btnDefined as $btnKey => $required) {
                if( $required && !isset($btn[$btnKey])){
                    throw new InvalidArgumentException("{$btnKey} is required");
                }
            }
        }
    }


    public function getApiFormat() :array
    {
        $msgParam = [
            'title' => $this->options['title'],
            'text'  => $this->options['text'],
        ];
        if( !isset($this->options['btnOrientation']) || $this->options['btnOrientation'] == 0 ){
            $msgKey = "sample".ucfirst($this->type).count($this->options['btns']);
            $defaultKey = "action";
        }elseif( isset($this->options['btnOrientation']) && $this->options['btnOrientation'] == 1 ){
            $msgKey = "sample".ucfirst($this->type)."6";
            $defaultKey = "button";
        }

        foreach ($this->options['btns'] as $btnKey => $bnt) {
            $actionURLKey = $defaultKey."Url".($btnKey+1);
            $actionTitleKey = $defaultKey."Title".($btnKey+1);
            $msgParam[$actionURLKey] = $bnt['actionURL'];
            $msgParam[$actionTitleKey] = $bnt['title'];
        }

        $data = [
            'msgKey' => $msgKey,
            'msgParam' => json_encode($msgParam)
        ];
        $this->data = $data;
        return $data;
    }

}

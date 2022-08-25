<?php

namespace Martialbe\LaravelDingtalk\Kernel;

/**
 * class ServiceContainer
 */
class ServiceContainer
{
    /**
     * 配置
     *
     * @var array
     */
    protected $userConfig = [];

    /**
     * 默认配置
     *
     * @var array
     */
    protected $defaultConfig = [];

    /**
     * @var AccessToken
     */
    public $accessToken;

    /**
     * 是否使用缓存
     *
     * @var boolean
     */
    public $use_cache;

    public function __construct(array $config)
    {
        $this->userConfig = $config;
        $this->accessToken = new AccessToken($this);
        $this->use_cache = $config['use_laravel_cache'];
    }

    public function getConfig()
    {
        return array_replace_recursive($this->defaultConfig, $this->userConfig);
    }
}

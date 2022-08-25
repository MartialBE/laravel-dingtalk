<?php
namespace Martialbe\LaravelDingtalk\Kernel;

use Martialbe\LaravelDingtalk\Kernel\ServiceContainer;
use Martialbe\LaravelDingtalk\Kernel\Traits\HasHttpClient;

/**
 * Class AccessToken
 */
class AccessToken
{

    use HasHttpClient;
    /**
     *
     * @var \Martialbe\LaravelDingtalk\Kernel\ServiceContainer
     */
    protected $app;

    /**
     *
     * @var array
     */
    protected $token;

    /**
     * @var string
     */
    protected $tokenKey = 'accessToken';

    /**
     * @var string
     */
    protected $cachePrefix = "dingtalk.access_token.";


    /**
     *
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * 获取token
     *
     * @param boolean $refresh
     * @return array
     */
    public function getToken(bool $refresh = false)
    {
        $cacheKey = $this->getCacheKey();
        $cache = $this->app->use_cache ? cache() : false;
        if( (!$refresh && $cache && $cache->has($cacheKey)) && $result = $cache->get($cacheKey) ) {
            return $result;
        }
        // 重新获取token
        $token = $this->requestToken($this->getCredentials());
        $this->setToken($token[$this->tokenKey], $token['expireIn'] ?? 7200);
        $this->token = $token;
        return $token;
    }

    public function getAccessToken(bool $refresh = false)
    {
        $accessToken = $this->getToken($refresh);
        return $accessToken[$this->tokenKey];
    }

    /**
     * 缓存token
     *
     * @param string $token
     * @param integer $lifetime
     * @return self
     */
    public function setToken(string $token, int $lifetime = 7200)
    {
        $cache = $this->app->use_cache ? cache() : false;
        if( !$cache ) {
            return $this;
        }
        $cache->set($this->getCacheKey(),[
            $this->tokenKey => $token,
            'expireIn' => $lifetime,
        ], $lifetime);
        return $this;
    }

    /**
     * 请求获取token
     *
     * @param array $credentials
     * @return array
     */
    public function requestToken(array $credentials)
    {
        $response = $this->httpPostJson('v1.0/oauth2/accessToken', [], $credentials);
        $result = json_decode($response->getBody(), true);
        return $result;
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->cachePrefix.md5(json_encode($this->getCredentials()));
    }


    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'appKey'    => $this->app->getAppKey(),
            'appSecret' => $this->app->getAppSecret(),
        ];
    }

}

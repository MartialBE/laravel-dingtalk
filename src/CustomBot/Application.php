<?php
namespace Martialbe\LaravelDingtalk\CustomBot;


use Martialbe\LaravelDingtalk\Kernel\Traits\HasHttpClient;

/**
 * Class Application
 */
class Application
{
    use HasHttpClient;

    /**
     * @var string
     */
    protected $accessToken;
    /**
     * @var string
     */
    protected $secret;

    /**
     * @var array
     */
    protected $message;




    public function __construct(array $config = [])
    {
        $this->setConfig($config);
        $this->setHttp();
    }

    public function setHttp( )
    {
        $httpOption = [
            "base_uri" => "https://oapi.dingtalk.com",
        ];
        $this->setHttpOption($httpOption);
    }

    public function setConfig(array $config)
    {
        !isset($config['access_token']) ?: $this->setAccessToken($config['access_token']);
        !isset($config['secret']) ?: $this->setSecret($config['secret']);
    }


    /**
     * @param string $accessToken
     * @return self
     */
    public function setAccessToken(string $accessToken) :self
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @param string $secret
     * @return self
     */
    public function setSecret(string $secret) :self
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @param array $message
     * @return self
     */
    public function setMessage(array $message) :self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function send( string $uri = "robot/send" ) :array
    {
        $response = $this->httpPostJson($uri, $this->getQuery(), $this->message);
        return json_decode($response->getBody(), true);
    }

    /**
     * @return array
     */
    protected function getQuery() :array
    {
        $query = [
            "access_token" => $this->accessToken
        ];
        if( $this->secret ) {
            $timestamp = time().sprintf('%03d', random_int(1, 999));
            $query['timestamp'] = $timestamp;
            $query['sign'] = generate_sign($this->secret, $timestamp, true);
        }

        return $query;
    }

}

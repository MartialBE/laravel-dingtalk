<?php

namespace Martialbe\LaravelDingtalk\Kernel;

use Martialbe\LaravelDingtalk\Kernel\ServiceContainer;
use Martialbe\LaravelDingtalk\Kernel\Traits\HasHttpClient;
use Martialbe\LaravelDingtalk\Kernel\Exceptions\HttpException;

class BaseClient
{
    use HasHttpClient {
        request as performRequest;
    }
    /**
     *
     * @var \Martialbe\LaravelDingtalk\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @var AccessToken
     */
    protected $accessToken;

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
        $this->accessToken = $app->accessToken;
        $this->setHttp();
    }

    public function setHttp(bool $refresh = false)
    {
        $httpOption = [
            "headers" => [
                "x-acs-dingtalk-access-token" => $this->accessToken->getAccessToken($refresh)
            ]
        ];
        $this->setHttpOption($httpOption);
    }


    public function request(string $method, string $url = '', array $options = [])
    {
        $log = app('dingtalk.log');
        try {
            $response = $this->performRequest($method, $url, $options);
        } catch (\GuzzleHttp\Exception\RequestException $err) {
            if( $err->hasResponse() && $body = json_decode($err->getResponse()->getBody()->getContents(), true) && isset($body['code']) && $body['code'] == "InvalidAuthentication") {
                $this->setHttp(true);
                $response =  $this->performRequest($method, $url, $options);
            }else{
                $resErr = [
                    'Request' => [
                        "method"       => $method,
                        "base_options" => $this->httpOptions,
                        "url"          => $url,
                        "options"      => $options
                    ],
                ];
                if( $err->hasResponse() ) {
                    $resErr['Response'] = [
                        'code' => $err->getCode(),
                        'body' => json_decode($err->getResponse()->getBody()->getContents(), true) ?: $err->getResponse()->getBody()->getContents(),
                        'Headers' => $err->getResponse()->getHeaders()
                    ];
                }
                $log->error("Dingtalk Request Error:", $resErr);
                throw new HttpException('Dingtalk Request Error:'.json_encode($resErr));
            }
        }
        $log->debug("Dingtalk Request Success:", [$response->getBody()]);
        return $response;
    }


}

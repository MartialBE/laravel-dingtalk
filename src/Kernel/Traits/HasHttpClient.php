<?php
namespace Martialbe\LaravelDingtalk\Kernel\Traits;

use GuzzleHttp\Client as HttpClient;

trait HasHttpClient
{
    protected $httpClient;
    protected $httpOptions = [
        "timeout" => 30.0,
        "base_uri" => "https://api.dingtalk.com/",
        "headers" => [
            "Content-Type" => "application/json"
        ]
    ];

    /**
     * 设置参数
     *
     * @param array $option
     * @return Martialbe\LaravelDingtalk\Kernel\Http\Client
     */
    public function setHttpOption(array $option)
    {
        $this->httpOptions = array_replace_recursive($this->httpOptions, $option);
        $this->httpClient = new HttpClient($this->httpOptions);
        return $this;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient() : HttpClient
    {
        if (! $this->httpClient instanceof Client) {
            $this->httpClient = new HttpClient($this->httpOptions);
        }

        return $this->httpClient;
    }

    public function httpGet(string $url, array $query = [])
    {
        return $this->request( 'GET', $url, ['query' => $query]);
    }


    public function httpPost(string $url, array $data = [])
    {
        return $this->request( 'POST', $url, ['form_params' => $data]);
    }

    public function httpPostJson(string $url, array $query = [], array $data = [])
    {
        return $this->request('POST', $url, ['query' => $query, 'json' => $data]);
    }

    public function httpPutJson(string $url, array $query = [], array $data = [])
    {
        return $this->request('PUT', $url, ['query' => $query, 'json' => $data]);
    }

    /**
     *
     * @param string $method
     * @param string $url
     * @param array $options
     *
     * @throws HttpException
     */
    public function request(string $method, string $url = '', array $options = [])
    {
            $response = $this->getHttpClient()->request($method, $url, $options);
            $response->getBody()->rewind();
        return $response;
    }
}

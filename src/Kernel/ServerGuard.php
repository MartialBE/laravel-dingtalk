<?php

namespace Martialbe\LaravelDingtalk\Kernel;

use Martialbe\LaravelDingtalk\Kernel\Exceptions\BadRequestException;
use Martialbe\LaravelDingtalk\Kernel\ServiceContainer;
use Illuminate\Http\Response;
use Martialbe\LaravelDingtalk\Kernel\Traits\HasWithHandlers;
/**
 * class ServerGuard
 */
class ServerGuard
{
    use HasWithHandlers;

    /**
     *
     * @var \Martialbe\LaravelDingtalk\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Empty string.
     */
    public const SUCCESS_EMPTY_RESPONSE = 'success';


    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
        $this->request = request();
    }

    public function server() :Response
    {
        $log = app('dingtalk.log');
        $log->debug("Dingtalk CallBack Request:", [
            "headers"      => $this->request->header(),
            "method"       => $this->request->getMethod(),
            'uri'          => $this->request->getUri(),
            'content-type' => $this->request->getContentType(),
            'content'      => $this->request->getContent(),
        ]);
        $response = $this->validate()->resolve();

        $log->debug('Dingtalk Server response:', ['content' => $response->getContent()]);
        return $response;
    }


    /**
     *
     * @return self
     */
    public function validate() :self
    {
        // 有些请求没有验证
        if( !$this->request->get('outTrackId') && $this->request->header('sign') !== generate_sign($this->app->getAppSecret(), $this->request->header('timestamp')) ) {
            throw new BadRequestException('Invalid request signature.', 400);
        }
        return $this;
    }


    public function getMessage()
    {
        $message = json_decode($this->request->getContent(false), true);
        return $message;
    }

    protected function resolve()
    {
        $response = $this->handleRequest();
        if(! ($response instanceof Response) ) {
            if (empty($response)) {
                $response = [];
            }
            $response = new Response($response, 200, ['Content-Type' => 'application/json']);
        }
        return $response;
    }


    public function handleRequest()
    {
        $message = $this->getMessage();
        $response = $this->handle(new Response(self::SUCCESS_EMPTY_RESPONSE, 200, ['Content-Type' => 'application/json']), $message);

        return $response;
    }

}

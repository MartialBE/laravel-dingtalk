<?php
namespace Martialbe\LaravelDingtalk\WorkBot\InteractiveCards;


use Martialbe\LaravelDingtalk\Kernel\BaseClient;
use Martialbe\LaravelDingtalk\Kernel\Http\Client as HttpClient;
use Martialbe\LaravelDingtalk\CustomBot\Application as CustomBot;

/**
 * 互动卡片
 */
class Client extends BaseClient
{
    /**
     * @var string
     */
    protected $outTrackId;

    /**
     * 注册回调地址
     *
     * @param array $params
     * @return array
     */
    public function register(array $params)
    {
        $customBot = new CustomBot(['access_token' => $this->accessToken->getAccessToken()]);
        $data = $customBot->setMessage($params)->send("topapi/im/chat/scencegroup/interactivecard/callback/register");
        return $data;
    }

    /**
     * 单聊发送
     *
     * @param string $cardTemplateId
     * @param array|string $users
     * @param array $params
     * @return array
     */
    public function personSend(string $cardTemplateId, $users, array $params )
    {
        $data = ["conversationType" => 0];
        $data['receiverUserIdList'] = !is_array($users) ? [$users] : $users;
        $params = array_merge($data, $params);

        return $this->send($cardTemplateId, $params);
    }


    /**
     * 群聊发送
     *
     * @param string $cardTemplateId
     * @param string $openConversationId
     * @param array $params
     * @return array
     */
    public function groupSend(string $cardTemplateId, string $openConversationId, array $params)
    {
        $data = [
            "conversationType" => 1,
            "openConversationId" => $openConversationId
        ];
        $params = array_merge($data, $params);

        return $this->send($cardTemplateId, $params);
    }

    /**
     * 卡片发送
     *
     * @param string $cardTemplateId
     * @param array $params
     * @return array
     */
    public function send( string $cardTemplateId, array $params )
    {
        $data = [
            "cardTemplateId" => $cardTemplateId,
            "robotCode" => $this->app->getAppKey(),
        ];
        if(!isset($params['outTrackId'])) {
            $data['outTrackId'] = $this->generateOutTrackId();
        }else{
            $this->outTrackId = $data['outTrackId'];
        }
        $params = array_merge($data, $params);
        $data = $this->httpPostJson('v1.0/im/interactiveCards/send', [], $params);
        return json_decode($data->getBody(), true);
    }

    /**
     * 更新卡片
     *
     * @param array $params
     * @return array
     */
    public function update(array $params)
    {
        $data = $this->httpPutJson('v1.0/im/interactiveCards', [], $params);
        return json_decode($data->getBody(), true);

    }

    /**
     * 单聊发送
     *
     * @param string $cardTemplateId
     * @param array|string $users
     * @param array $params
     * @return array
     */
    public function personSendTemplates(string $cardTemplateId, $users, array $params )
    {
        $data = ["singleChatReceiver" => json_encode( !is_array($users) ? ["userId" => $users] : $users )];
        $params = array_merge($data, $params);

        return $this->sendTemplates($cardTemplateId, $params);
    }

    /**
     * 群聊发送
     *
     * @param string $cardTemplateId
     * @param string $openConversationId
     * @param array $params
     * @return array
     */
    public function groupSendTemplates(string $cardTemplateId, string $openConversationId, array $params)
    {
        $data = [
            "openConversationId" => $openConversationId
        ];
        $params = array_merge($data, $params);
        return $this->sendTemplates($cardTemplateId, $params);
    }

    public function sendTemplates(string $cardTemplateId, array $params)
    {
        $data = [
            "cardTemplateId" => $cardTemplateId,
            "robotCode" => $this->app->getAppKey(),
        ];
        if(!isset($params['outTrackId'])) {
            $data['outTrackId'] = $this->generateOutTrackId();
        }else{
            $this->outTrackId = $data['outTrackId'];
        }
        $params = array_merge($data, $params);
        $data = $this->httpPostJson('v1.0/im/interactiveCards/templates/send', [], $params);
        return json_decode($data->getBody(), true);
    }

    public function getOutTrackId()
    {
        return $this->outTrackId;
    }

    public function generateOutTrackId()
    {
        $this->outTrackId = \Str::random(32);

        return $this->outTrackId;
    }

}

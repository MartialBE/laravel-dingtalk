<?php
namespace Martialbe\LaravelDingtalk\WorkBot\Messages;


use Martialbe\LaravelDingtalk\Kernel\BaseClient;

/**
 * 消息发送
 */
class Client extends BaseClient
{
    /**
     * 批量发送单聊消息
     *
     * @param array|string $users
     * @param array $params
     * @return array
     */
    public function batchSend( $users, array $params )
    {
        if(!is_array($users)) {
            $users = [$users];
        }
        $params = array_merge(["robotCode" => $this->app->getAppKey(),"userIds" => $users], $params);
        $data = $this->httpPostJson('v1.0/robot/oToMessages/batchSend', [], $params);
        return json_decode($data->getBody(), true);
    }

    /**
     * 批量撤回单聊消息
     *
     * @param array|string $processQueryKeys
     * @return array
     */
    public function batchRecall($processQueryKeys)
    {
        if(!is_array($processQueryKeys)) {
            $processQueryKeys = [$processQueryKeys];
        }
        $data = $this->httpPostJson('v1.0/robot/otoMessages/batchRecall', [], ["robotCode" => $this->app->getAppKey(),"processQueryKeys" => $processQueryKeys]);
        return json_decode($data->getBody(), true);
    }

    /**
     * 查看单聊阅读状态
     *
     * @param string $processQueryKey
     * @return array
     */
    public function readStatus(string $processQueryKey)
    {
        $data = $this->httpGet('v1.0/robot/oToMessages/readStatus', ["robotCode" => $this->app->getAppKey(),"processQueryKey" => $processQueryKey]);
        return json_decode($data->getBody(), true);
    }

    /**
     * 发送群消息
     *
     * @param string $openConversationId
     * @param array $params
     * @return array
     */
    public function groupMessagesSend(string $openConversationId, array $params)
    {
        $params = array_merge(["robotCode" => $this->app->getAppKey(),"openConversationId" => $openConversationId], $params);
        $data = $this->httpPostJson('v1.0/robot/groupMessages/send', [], $params);
        return json_decode($data->getBody(), true);
    }

    public function groupMessagesRecall(string $openConversationId, $processQueryKeys)
    {
        if(!is_array($processQueryKeys)) {
            $processQueryKeys = [$processQueryKeys];
        }
        $data = $this->httpPostJson('v1.0/robot/groupMessages/recall', [], ["robotCode" => $this->app->getAppKey(),"openConversationId" => $openConversationId, "processQueryKeys" => $processQueryKeys]);
        return json_decode($data->getBody(), true);
    }

    /**
     * 查询群消息状态
     *
     * @param string $openConversationId
     * @param string $processQueryKey
     * @param integer $maxResults
     * @param string $nextToken
     * @return array
     */
    public function groupMessagesQuery(string $openConversationId, string $processQueryKey, int $maxResults = 200, string $nextToken = "")
    {
        $data = $this->httpPostJson('v1.0/robot/groupMessages/query', [], [
            "robotCode"          => $this->app->getAppKey(),
            "openConversationId" => $openConversationId,
            "processQueryKey"    => $processQueryKey,
            "maxResults"         => $maxResults,
            "nextToken"          => $nextToken,
        ]);
        return json_decode($data->getBody(), true);
    }


}

# LaravelDingtalk

自用的Laravel Dingtalk包。

目前只实现了内部机器人和webhook机器人的使用方法。
还有很多功能没有完善

---

## 要求

- PHP >= 7.4
- Laravel >=8.5

---

## 安装

```bash
composer require martialbe/laravel-dingtalk
```

---

## 开始使用

1. 创建配置文件

```bash
php artisan vendor:publish --provider="Martialbe\LaravelDingtalk\DingtalkServiceProvider"
```

2. 添加别名

```php
'aliases' => [
    // ...
    Martialbe\LaravelDingtalk\DingtalkServiceProvider::class,
],
```

3. 使用

```php
    \Dingtalk::CustomBot()
        ->setMessage(\Martialbe\LaravelDingtalk\BasicService\Message::text(["content" => "你好"])->toArray())->send();
```

4. 多帐号

可在配置文件中建立多个帐号，默认是`default`。
配置多个帐号后，可以通过别名直接使用
```php
    \Dingtalk::CustomBot('other');
```

---

## 内部机器人

### 开始

```php
$config = [
    'app_key'    => 'sdfsdfrgert',
    'app_secret' => 'ertefdrgdfgdgtryrt',
];

$app = \Dingtalk::WorkBot($config);

// OR

$app = \Dingtalk::WorkBot('default');

```


### 发送普通消息
消息组装请看下面`普通消息类型`

#### 批量发送单聊消息
```php
use Martialbe\LaravelDingtalk\BasicService\Message;

$app = \Dingtalk::WorkBot();
$app->messages->batchSend("userId", Message::text(['content' => "这是文本测试"], 'api')->toArray());
//OR
$app->messages->batchSend(["userId 1", "userId 2"], Message::text(['content' => "这是文本测试"], 'api')->toArray());
```

#### 批量撤回单聊消息
```php
$app = \Dingtalk::WorkBot();
$app->messages->batchRecall("processQueryKey");
//OR
$app->messages->batchRecall(["processQueryKey 1", "processQueryKey 2"]);
```

#### 查看单聊阅读状态
```php
$app = \Dingtalk::WorkBot();
$app->messages->readStatus("processQueryKey");
```

#### 发送群消息
```php
use Martialbe\LaravelDingtalk\BasicService\Message;

$app = \Dingtalk::WorkBot();
$app->messages->groupMessagesSend("openConversationId", Message::text(['content' => "这是文本测试"], 'api')->toArray());
```

#### 撤回群消息
```php
$app = \Dingtalk::WorkBot();
$app->messages->groupMessagesRecall("openConversationId", "processQueryKey");
```

#### 查看群阅读状态
```php
$app = \Dingtalk::WorkBot();
$app->messages->groupMessagesQuery("openConversationId", "processQueryKey", $maxResults = 200, $nextToken = "");
```

### 发送互动卡片

关于 `outTrackId`， 包会自动生成`outTrackId`,你也可以通过直接在参数中包含自己的`outTrackId`

```php
$app = \Dingtalk::WorkBot();
$app->interactiveCards->personSend("cardTemplateId", "usersId", $params);
$outTrackId = $app->interactiveCards->getOutTrackId();
```

#### 注册回调地址
```php
$app = \Dingtalk::WorkBot();
$app->interactiveCards->register($params);
```

#### 发送单聊消息
```php
use Martialbe\LaravelDingtalk\BasicService\InteractiveCard;

$params = (new InteractiveCard())
        ->setHeader(function ($header)
        {
            $header->setText("公告：测试TuWenCard01")
                ->setIco("https://static.dingtalk.com/media/lALPDe7syH8nG_wcHA_28_28.png")
                ->setColor("#00B853");
        })
        ->setContents(function ($content)
        {
            $content->setType("DESCRIPTION")
            ->setText("大家按照这个格式填写下，每周我会做一个统计和公布哈，和大家同步下我们的进展");
        })
        ->setContents(function ($content)
        {
            $content->setType("IMAGE")
            ->setText("@lALPDeREVttTpCrNA6rNA6o");
        })
        ->setContents(function ($content)
        {
            $content->setType("MARKDOWN")
            ->setText("#测试无序列表\n* ✅预览区域代码高亮\n* ✅所有选项自动记忆\n开始**加粗**结束\n开始*斜体*结束\n开始***加粗与斜体***结束\n<font color=#00B042 size=15>测试：【正向文字：用于表达上涨上升、正向反馈文字，禁止大面积使用。】【15号字体】**【加粗】**</font>\n<font color=#FF5219 size=12>测试：【报错：用户内容报错、警示内容，禁止大面积使用。】【12号字体】*【斜体】*</font>");
        })
        ->setActions(function ($action)
        {
            $action->setId(1)
                ->setText("同意")
                ->setAfterClickText("已同意")
                ->setIco("@lALPDeREVttTpCrNA6rNA6o")
                ->setStatus("NORMAL")
                ->setActionType("LWP");
        })
        ->setActions(function ($action)
        {
            $action->setId(2)
                ->setText("不同意")
                ->setAfterClickText("已拒绝")
                ->setIco("@lALPDeREVttTpCrNA6rNA6o")
                ->setStatus("NORMAL")
                ->setActionType("LWP");
        })
        ->setActionDirection("HORIZONTAL")
        ->toArray();

$app = \Dingtalk::WorkBot();
$app->interactiveCards->personSend("cardTemplateId", "usersId", $params);
$outTrackId = $app->interactiveCards->getOutTrackId();
```

#### 发送群消息
```php
$app = \Dingtalk::WorkBot();
$app->interactiveCards->groupSend("cardTemplateId", "openConversationId", $params);
$outTrackId = $app->interactiveCards->getOutTrackId();
```

#### 更新卡片
```php
$app = \Dingtalk::WorkBot();
$data = [
    "outTrackId" => "VWQKWfWD7aIltxEZp6eIvhytdCsyzo1L",
    "cardData" => [
        "cardParamMap" => [
                        "title" => "更新",
                        "type" => "审核成功了吧",
                        "status" => "成功了😄",
                        "reason" => "# 嗯嗯",
                    ]
    ]
];

$app->interactiveCards->update($data);
```

#### 发送模板单聊消息
```php
$app = \Dingtalk::WorkBot();

$app->interactiveCards->personSendTemplates("TuWenCard02", "userId", [ "cardData" => json_encode( $msg ), "callbackUrl" => "you callback"])
$outTrackId = $app->interactiveCards->getOutTrackId();
```

#### 发送模板群聊消息
```php
$app = \Dingtalk::WorkBot();

$app->interactiveCards->groupSendTemplates("TuWenCard02", "openConversationId", [ "cardData" => json_encode( $msg ), "callbackUrl" => "you callback"])
$outTrackId = $app->interactiveCards->getOutTrackId();
```

## webhook机器人

```php
use Martialbe\LaravelDingtalk\BasicService\Message;
\Dingtalk::CustomBot()
        ->setMessage(Message::text(["content" => "这是通过wehook发送的消息"])->toArray())->send();

```

## 普通消息类型

1. 文本消息
```php
use Martialbe\LaravelDingtalk\BasicService\Message;

// 内部机器人
$sendMessage = Message::text(["content" => "这是通过wehook发送的消息"], 'api')->toArray();

// webhook机器人 默认
$sendMessage = Message::text(["content" => "这是通过wehook发送的消息"])->toArray();
```

2. Markdown消息
```php
use Martialbe\LaravelDingtalk\BasicService\Message;

// 内部机器人
$sendMessage = Message::markdown(['title' => "我会发送markdown了~~", 'text' => "# 我会发送markdown了\n > 😸🤣"], 'api')->toArray();
// webhook机器人 默认
$sendMessage = Message::markdown(['title' => "我会发送markdown了~~", 'text' => "# 我会发送markdown了\n > 😸🤣"], 'api')->toArray();
```

3. Link消息
```php
use Martialbe\LaravelDingtalk\BasicService\Message;

// 内部机器人
$sendMessage = Message::link(['title' => "sampleLink消息测试", "text" => "消息内容测试", "picUrl" => "http://img", "messageUrl" => "https://google.com"], 'api')->toArray();
// webhook机器人 默认
$sendMessage = Message::link(['title' => "sampleLink消息测试", "text" => "消息内容测试", "picUrl" => "http://img", "messageUrl" => "https://google.com"])->toArray();
```

4. SingleAction消息
```php
use Martialbe\LaravelDingtalk\BasicService\Message;

// 内部机器人
$sendMessage = Message::singleAction(['title' => "乔布斯 20 年前想打造一间苹果咖啡厅，而它正是 Apple Store 的前身", "text" => "![screenshot](https://img)\n#### 乔布斯 20 年前想打造的苹果咖啡厅 \n\n Apple Store 的设计正从原来满满的科技感走向生活化，而其生活化的走向其实可以追溯到 20 年前苹果一个建立咖啡馆的计划", "singleTitle" => "查看详情", "singleURL" => "https://google.com"], 'api')->toArray();
// webhook机器人 默认
$sendMessage = Message::singleAction(['title' => "乔布斯 20 年前想打造一间苹果咖啡厅，而它正是 Apple Store 的前身", "text" => "![screenshot](https://img)\n#### 乔布斯 20 年前想打造的苹果咖啡厅 \n\n Apple Store 的设计正从原来满满的科技感走向生活化，而其生活化的走向其实可以追溯到 20 年前苹果一个建立咖啡馆的计划", "singleTitle" => "查看详情", "singleURL" => "https://google.com"])->toArray();
```

4. BtnsAction消息
```php
use Martialbe\LaravelDingtalk\BasicService\Message;

// 内部机器人
$sendMessage = Message::btnsAction(["btnOrientation" => 0, 'title' => "乔布斯 20 年前想打造一间苹果咖啡厅，而它正是 Apple Store 的前身", "text" => "![screenshot](https://img)\n#### 乔布斯 20 年前想打造的苹果咖啡厅 \n\n Apple Store 的设计正从原来满满的科技感走向生活化，而其生活化的走向其实可以追溯到 20 年前苹果一个建立咖啡馆的计划", "btns" => [['title' => "查看详情", "actionURL" => "https://google.com"], ['title' => "不感兴趣", "actionURL" => "https://google.com"]]], 'api')->toArray();
// webhook机器人 默认
$sendMessage = Message::btnsAction(["btnOrientation" => 0, 'title' => "乔布斯 20 年前想打造一间苹果咖啡厅，而它正是 Apple Store 的前身", "text" => "![screenshot](https://img)\n#### 乔布斯 20 年前想打造的苹果咖啡厅 \n\n Apple Store 的设计正从原来满满的科技感走向生活化，而其生活化的走向其实可以追溯到 20 年前苹果一个建立咖啡馆的计划", "btns" => [['title' => "查看详情", "actionURL" => "https://google.com"], ['title' => "不感兴趣", "actionURL" => "https://google.com"]]])->toArray();
```

6. Image消息
```php
use Martialbe\LaravelDingtalk\BasicService\Message;

// 内部机器人
$sendMessage =  Message::image(['photoURL' => "https://img"], 'api')->toArray();
// webhook机器人 不支持该类型
```

7. Feed消息
```php
use Martialbe\LaravelDingtalk\BasicService\Message;

// 内部机器人 不支持该类型

// webhook机器人 默认
$sendMessage = Message::feed(["links" => [ "title" => "时代的火车向前开", "messageURL" => "https://www.dingtalk.com/", "picURL" => "https://img.alicdn.com/tfs/TB1NwmBEL9TBuNjy1zbXXXpepXa-2400-1218.png"] ])->toArray();
```

## 服务端
默认处理了服务端验证的逻辑

### 接收消息
```php

$app = \Dingtalk::WorkBot();
$server = $bot->server;
// 直接返回 Illuminate\Http\Request
$response = $server->server();
```

### 中间件

```php
$app = \Dingtalk::WorkBot();
$server = $bot->server;

$server->with(function($message, \Closure $next) {
    // 你的自定义逻辑
    return $next($message);
});
$response = $server->server();

```

你可以注册多个中间件来处理不同的情况：

```php
$app = \Dingtalk::WorkBot();
$server = $bot->server;

$server
    ->with(function($message, \Closure $next) {
        // 你的自定义逻辑1
        return $next($message);
    })
    ->with(function($message, \Closure $next) {
        // 你的自定义逻辑2
        return $next($message);
    })
    ->with(function($message, \Closure $next) {
        // 回复消息
        return json_encode(\Martialbe\LaravelDingtalk\BasicService\Message::text(['content' => "这是文本测试"], 'api')->toArray());
    });
$response = $server->server();
```

你也可以直接编写一个中间件类：

中间件必须包含`__invoke`函数

```php
use Martialbe\LaravelDingtalk\Kernel\Support\Handler;

class MyHandler extends Handler
{
    public function __invoke($message, \Closure $next)
    {
        // 你的自定义逻辑
        return $next($message);
    }
}

```
注册中间件：

```php
$app = \Dingtalk::WorkBot();
$server = $bot->server;
$server->with(MyHandler::class);
//OR
$server->with(new MyHandler());

```


## 致谢
本项目是仿照[easywechat](https://github.com/w7corp/easywechat)编写的。
消息组装是参考[notify](https://github.com/guanguans/notify)。

以上两个项目都是在`MIT`许可下授权。

## License

MIT

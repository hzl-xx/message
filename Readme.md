### 消息推送服务调用
### 安装
```$xslt
composer require aiqbg/message
```

### 发布
```$xslt
php artisan vendor:publish
```

### 注册服务
`config/app.php`添加
```$xslt
Aiqbg\Message\MessageProvider::class
```
```$xslt
'Message' => Aiqbg\Message\Facades\Message::class
```

### 使用
#### RPC请求

##### sendMessage 参数

#### Http请求
##### sendMessageHttp
```php
use Aiqbg\Message\Facades\Message;

Message::sendMessage("测试插件", "啦啦啦啦啦啦");
```
- title: 消息标题 必传
- message: 消息内容 必传
- user: 消息接收人 非必传
- group: 消息接收组 非必传
- receive: 接收终端 默认 qywx, 在配置文件中配置

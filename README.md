# 对网易云信 IM 服务端 API 接口的 PHP 封装
[![Latest Stable Version](https://poser.pugx.org/singiu/neteaseim/v/stable)](https://packagist.org/packages/singiu/neteaseim)
[![Total Downloads](https://poser.pugx.org/singiu/neteaseim/downloads)](https://packagist.org/packages/singiu/neteaseim)
[![Latest Unstable Version](https://poser.pugx.org/singiu/neteaseim/v/unstable)](https://packagist.org/packages/singiu/neteaseim)
[![License](https://poser.pugx.org/singiu/neteaseim/license)](https://packagist.org/packages/singiu/neteaseim)
[![composer.lock available](https://poser.pugx.org/phpunit/phpunit/composerlock)](https://packagist.org/packages/phpunit/phpunit)

只封装了部分接口（其实就是我项目中使用到的接口），所以下载使用前请务必先详细阅读此文档，看看有没有你想要的功能。（当然你也可以自己补充你自己的需求，源码灰常简单的，欢迎发起 Pull request 帮助完善哦 :smirk: ）

如果没有你想要的功能，可以在 Issue 上发起需求，我会尽量满足。

## 安装
```bash
composer require singiu/neteaseim "dev-master as ^1.0.0-dev"
```

## 使用
实例化类需要 appKey 和 appSecret，这两个东西需要云网易云申请。
```php
$app_key = 'Your App Key';
$app_secret = 'Your App Secret';

$im = new NeteaseIM($app_key, $app_secret);
```

### 网易云通信 ID
#### 创建网易通信云 ID
```php
$acc_id = 'singiu';

$data = array(
    'name' => 'User nick name',
    'icon' => 'Avatar url',
    // ...
    // 其它参数可以参考网易云的文档：http://dev.netease.im/
);

$im->create($acc_id, $data);
```
#### 更新网易云通信ID
官网描述：网易云通信ID基本信息更新。
但其实我自己并不太清楚这个接口的用意 :sleeping:，有用的是可以指定修改用户的登录 token。
而 refreshToken 方法是官方后台刷新一个 token 并返回给你。
```php
$acc_id = 'singiu';
// 只接受两个参数：
$data = array(
    'props' => 'JSON 属性，第三方可选填，最大长度 1024 字符。',
    'token' => '网易云通信 ID 可以指定登录 token 值，最大长度 128 字符。',
);

$im->update($acc_id, $data);
```

#### 更新并获取新的 token
这里的 token 指的是*网易云通信 ID*的登录密码，可以理解为这是修改登录密码的接口。
```php
$acc_id = 'singiu';
$result = $im->refreshToken($acc_id);
$new_token = $result['token'];
```

#### 封禁网易云信 ID
 - 1.第三方禁用某个网易云通信ID的IM功能；
 - 2.封禁网易云通信ID后，此ID将不能登陆网易云通信imserver。
```php
$acc_id = 'singiu';
$need_kick = true; // 是否踢掉被禁用户（强迫下线），可以不传这个参数，不传默认为 false。
$im->block($acc_id, $need_kick);
```

#### 解禁网易云通信 ID
```php
$acc_id = 'singiu';
$im->unblock($acc_id);
```

### 用户名片
#### 获取用户名片
获取单一用户名片资料
```php
$acc_id = 'singiu';
$user_info = $im->getUserInfo($acc_id);
echo $user_info['email']; // junxing.lin@foxmail.com
```
获取多个用户名片资料（只要传一个acc_id的数据就行，注意方法名也不一样，多一个 **s** :smiling_imp: ）
```php
$acc_ids = ['singiu', 'jue'];
$users_info = $im->getUsersInfo($acc_ids);
var_dump($users_info);
```

#### 更新用户名片
```php
// 请求方式和参数都与 `$im->create($accId, $data)` 一样，只是方法名改为 `updateUserInfo()`。
$acc_id = 'singiu';
$data = array(
    'name' => 'Star',
    'email' => 'junxing.lin@foxmail.com',
    // ...
);

$im->updateUserInfo($acc_id, $data);
```

### 消息功能
#### 发送普通文本消息
```php
$from = 'singiu'; // 发送者 accid
$to = 'jue'; // 接收者 accid
$text = 'Hello World!';

$im->sendTextMessage($from, $to, $text);
```

#### 发送自定义系统通知
这里的自定义系统通知类似于透传消息。
```php
$from = 'singiu';
$to = 'jue';
$attach = array(
    'customDataKey1' => 'customDataValue1',
    'customDataKey2' => 'customDataValue2',
    // ...
    // 这里的数据都是自定义的，方法会把它转成 JSON 格式透传给客户端。
);

$im->sendAttachMessage($from, $to, $attach);
```

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.

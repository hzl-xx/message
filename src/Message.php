<?php
namespace Aiqbg\Message;

use Grpc\ChannelCredentials;
use GuzzleHttp\Client;
use Rpc\Auth\AuthClient;
use Rpc\Auth\Key;
use Rpc\Message\Common;
use Illuminate\Config\Repository;

class Message
{
    private $config;
    private $cli;
    public function __construct(Repository $config)
    {
        $this->config = $config->get("messageserver");
    }


    public function initCli($clientName)
    {
        $token = $this->getToken();
        $this->cli = new $clientName(
            $this->config['grpc_host'],
            [
                'credentials'=>ChannelCredentials::createInsecure(),
                'update_metadata' => function($metaData) use($token){
                    $metaData['token'] = [$token];
                    return $metaData;
                }
            ]
        );
    }

    public function getToken()
    {
        $cli = new AuthClient(
            $this->config['grpc_host'],
            [
                'credentials'=>ChannelCredentials::createInsecure(),
                'update_metadata' => function($metaData) {
                    $metaData['Micro-Method'] = ["Auth.GetToken"];
                    return $metaData;
                }
            ]
        );
        $key = new Key();
        $key->setKey($this->getKey());
        list($res, $status) = $cli->GetToken($key)->wait();
        return $res->getToken()->getToken();
    }

    public function getKey() {
        return md5(json_encode(date("Y-m-d", time())));
    }

    public function simpleRequest($clientName, $method, $request) {
        $this->initCli($clientName);
        list($res, $status) = $this->cli->$method($request)->wait();
        return [
            "code"=>$res->getCode(),
            "message"=>$res->getMsg()
        ];
    }

    public function sendMessage($title, $message, $user="", $group="", $receive = "") {
        if ($receive == "") {
            $receive = $this->config["receive"];
        }
        if ($user == "" && $group == "") {
            $user = $this->config["group_strategy"]["default"];
        }
        $req = new Common();
        $req->setReceive($receive);
        $req->setTitle($title);
        $req->setMessage($message);
        $req->setSend($user);
        $req->setSendGroup($group);
        return $this->simpleRequest("Rpc\Message\SendMessageServiceClient", "SendMessage", $req);
    }

    /**
     *
     *User:
     *DateTime:
     * @param string $group
     * @param $title
     * @param $message
     * @param $receive
     * @param string $send 以英文逗号组装，比如XiaoXiongWei,HuangZhen
     */
    function sendMessageHttp($title,$message,$user='',$group='',$receive='')
    {
        $data['title']=$title;
        $data['message']=$message;
        $data['receive']=$receive ? : $this->config["receive"];
        $data['send']=($user == "" && $group == "")?$this->config["group_strategy"]["default"] : $user;
        $data['send_group']=$group;
        $token=app(Client::class)->get($this->config['http_host'].'/auth?key='.$this->getKey())->getBody()->getContents();
        $token = json_decode($token, true)['access_token'];

        $query = json_encode(array_filter($data));
        $result = app(Client::class)->post($this->config['http_host'].'/v1/send/message', [
            'body' => $query,
            'headers'=>[
                'token'=>$token
            ]
        ])->getBody()->getContents();
        return $result;
    }
}

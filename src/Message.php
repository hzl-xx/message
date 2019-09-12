<?php
namespace Aiqbg\Message;

use Grpc\ChannelCredentials;
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
}
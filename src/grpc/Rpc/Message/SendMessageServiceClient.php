<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Rpc\Message;

/**
 */
class SendMessageServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Rpc\Message\Common $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function SendMessage(\Rpc\Message\Common $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/rpc.message.SendMessageService/SendMessage',
        $argument,
        ['\Rpc\Message\Reponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Rpc\Message\Sentry $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function SendMessageSentry(\Rpc\Message\Sentry $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/rpc.message.SendMessageService/SendMessageSentry',
        $argument,
        ['\Rpc\Message\Reponse', 'decode'],
        $metadata, $options);
    }

}

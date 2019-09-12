<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Rpc\Auth;

/**
 */
class AuthClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Rpc\Auth\Key $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function GetToken(\Rpc\Auth\Key $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/rpc.auth.Auth/GetToken',
        $argument,
        ['\Rpc\Auth\Response', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Rpc\Auth\Token $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function ValidateToken(\Rpc\Auth\Token $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/rpc.auth.Auth/ValidateToken',
        $argument,
        ['\Rpc\Auth\Response', 'decode'],
        $metadata, $options);
    }

}

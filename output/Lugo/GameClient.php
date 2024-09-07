<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Lugo;

/**
 * Service provided by the game service to the players (clients).
 */
class GameClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * JoinATeam allows the player to listen the server during the match.
     * @param \Lugo\JoinRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function JoinATeam(\Lugo\JoinRequest $argument,
      $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/lugo.Game/JoinATeam',
        $argument,
        ['\Lugo\GameSnapshot', 'decode'],
        $metadata, $options);
    }

    /**
     * SendOrders allows the player to send others to the server when the game is on listening state.
     * @param \Lugo\OrderSet $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function SendOrders(\Lugo\OrderSet $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Game/SendOrders',
        $argument,
        ['\Lugo\OrderResponse', 'decode'],
        $metadata, $options);
    }

}

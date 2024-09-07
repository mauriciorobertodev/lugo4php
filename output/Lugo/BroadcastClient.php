<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Lugo;

/**
 * Service to be consumed by clients (e.g. frontend, app, etc) to watch the match.
 * The game server implements a Broadcast service. This service may help you to control or watch the game during
 * training sessions.
 */
class BroadcastClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * Keep an open stream that publish all important events in the match.
     * @param \Lugo\WatcherRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function OnEvent(\Lugo\WatcherRequest $argument,
      $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/lugo.Broadcast/OnEvent',
        $argument,
        ['\Lugo\GameEvent', 'decode'],
        $metadata, $options);
    }

    /**
     * Returns the game setup configuration.
     * @param \Lugo\WatcherRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function GetGameSetup(\Lugo\WatcherRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Broadcast/GetGameSetup',
        $argument,
        ['\Lugo\GameSetup', 'decode'],
        $metadata, $options);
    }

    /**
     * StartGame allows the master watcher to start the match.
     * See the Game Server starting mode to understand how it works.
     * @param \Lugo\StartRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function StartGame(\Lugo\StartRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Broadcast/StartGame',
        $argument,
        ['\Lugo\GameSetup', 'decode'],
        $metadata, $options);
    }

}

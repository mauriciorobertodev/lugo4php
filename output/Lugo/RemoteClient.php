<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Lugo;

/**
 * The game server implements a Remote service that allows you to control the game flow.
 * This service may help you to control or watch the game during training sessions.
 * The game server only offers this service on debug mode on.
 */
class RemoteClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Lugo\PauseResumeRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function PauseOrResume(\Lugo\PauseResumeRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Remote/PauseOrResume',
        $argument,
        ['\Lugo\CommandResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Lugo\NextTurnRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function NextTurn(\Lugo\NextTurnRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Remote/NextTurn',
        $argument,
        ['\Lugo\CommandResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Lugo\NextOrderRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function NextOrder(\Lugo\NextOrderRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Remote/NextOrder',
        $argument,
        ['\Lugo\CommandResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Lugo\BallProperties $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function SetBallProperties(\Lugo\BallProperties $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Remote/SetBallProperties',
        $argument,
        ['\Lugo\CommandResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Lugo\PlayerProperties $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function SetPlayerProperties(\Lugo\PlayerProperties $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Remote/SetPlayerProperties',
        $argument,
        ['\Lugo\CommandResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Lugo\GameProperties $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function SetGameProperties(\Lugo\GameProperties $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Remote/SetGameProperties',
        $argument,
        ['\Lugo\CommandResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Lugo\ResumeListeningRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function ResumeListeningPhase(\Lugo\ResumeListeningRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Remote/ResumeListeningPhase',
        $argument,
        ['\Lugo\ResumeListeningResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Lugo\ResetPlayerPositionsRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function ResetPlayerPositions(\Lugo\ResetPlayerPositionsRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Remote/ResetPlayerPositions',
        $argument,
        ['\Lugo\ResetPlayerPositionsResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Lugo\GameSnapshotRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function GetGameSnapshot(\Lugo\GameSnapshotRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/lugo.Remote/GetGameSnapshot',
        $argument,
        ['\Lugo\GameSnapshotResponse', 'decode'],
        $metadata, $options);
    }

}

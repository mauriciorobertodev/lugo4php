<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: server.proto

namespace Lugo;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * GameSnapshot stores all game elements data.
 *
 * Generated from protobuf message <code>lugo.GameSnapshot</code>
 */
class GameSnapshot extends \Google\Protobuf\Internal\Message
{
    /**
     * The game state defines which phase the game is. The phase determine what the server is doing, are going to do, or
     * what it is waiting for.
     *
     * Generated from protobuf field <code>.lugo.GameSnapshot.State state = 1;</code>
     */
    private $state = 0;
    /**
     * Turns counter. It starts from 1, but before the match starts, it may be zero.
     *
     * Generated from protobuf field <code>uint32 turn = 2;</code>
     */
    private $turn = 0;
    /**
     * Store the home team elements.
     *
     * Generated from protobuf field <code>.lugo.Team home_team = 3;</code>
     */
    private $home_team = null;
    /**
     * Store the away team elements.
     *
     * Generated from protobuf field <code>.lugo.Team away_team = 4;</code>
     */
    private $away_team = null;
    /**
     * Store the ball element.
     *
     * Generated from protobuf field <code>.lugo.Ball ball = 5;</code>
     */
    private $ball = null;
    /**
     * number of turns the ball is in a goal zone
     *
     * Generated from protobuf field <code>uint32 turns_ball_in_goal_zone = 6;</code>
     */
    private $turns_ball_in_goal_zone = 0;
    /**
     * Store the shot clock to control ball possession limit
     *
     * Generated from protobuf field <code>.lugo.ShotClock shot_clock = 7;</code>
     */
    private $shot_clock = null;

    public function __construct() {
        \GPBMetadata\Server::initOnce();
        parent::__construct();
    }

    /**
     * The game state defines which phase the game is. The phase determine what the server is doing, are going to do, or
     * what it is waiting for.
     *
     * Generated from protobuf field <code>.lugo.GameSnapshot.State state = 1;</code>
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * The game state defines which phase the game is. The phase determine what the server is doing, are going to do, or
     * what it is waiting for.
     *
     * Generated from protobuf field <code>.lugo.GameSnapshot.State state = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setState($var)
    {
        GPBUtil::checkEnum($var, \Lugo\GameSnapshot_State::class);
        $this->state = $var;

        return $this;
    }

    /**
     * Turns counter. It starts from 1, but before the match starts, it may be zero.
     *
     * Generated from protobuf field <code>uint32 turn = 2;</code>
     * @return int
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * Turns counter. It starts from 1, but before the match starts, it may be zero.
     *
     * Generated from protobuf field <code>uint32 turn = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setTurn($var)
    {
        GPBUtil::checkUint32($var);
        $this->turn = $var;

        return $this;
    }

    /**
     * Store the home team elements.
     *
     * Generated from protobuf field <code>.lugo.Team home_team = 3;</code>
     * @return \Lugo\Team
     */
    public function getHomeTeam()
    {
        return $this->home_team;
    }

    /**
     * Store the home team elements.
     *
     * Generated from protobuf field <code>.lugo.Team home_team = 3;</code>
     * @param \Lugo\Team $var
     * @return $this
     */
    public function setHomeTeam($var)
    {
        GPBUtil::checkMessage($var, \Lugo\Team::class);
        $this->home_team = $var;

        return $this;
    }

    /**
     * Store the away team elements.
     *
     * Generated from protobuf field <code>.lugo.Team away_team = 4;</code>
     * @return \Lugo\Team
     */
    public function getAwayTeam()
    {
        return $this->away_team;
    }

    /**
     * Store the away team elements.
     *
     * Generated from protobuf field <code>.lugo.Team away_team = 4;</code>
     * @param \Lugo\Team $var
     * @return $this
     */
    public function setAwayTeam($var)
    {
        GPBUtil::checkMessage($var, \Lugo\Team::class);
        $this->away_team = $var;

        return $this;
    }

    /**
     * Store the ball element.
     *
     * Generated from protobuf field <code>.lugo.Ball ball = 5;</code>
     * @return \Lugo\Ball
     */
    public function getBall()
    {
        return $this->ball;
    }

    /**
     * Store the ball element.
     *
     * Generated from protobuf field <code>.lugo.Ball ball = 5;</code>
     * @param \Lugo\Ball $var
     * @return $this
     */
    public function setBall($var)
    {
        GPBUtil::checkMessage($var, \Lugo\Ball::class);
        $this->ball = $var;

        return $this;
    }

    /**
     * number of turns the ball is in a goal zone
     *
     * Generated from protobuf field <code>uint32 turns_ball_in_goal_zone = 6;</code>
     * @return int
     */
    public function getTurnsBallInGoalZone()
    {
        return $this->turns_ball_in_goal_zone;
    }

    /**
     * number of turns the ball is in a goal zone
     *
     * Generated from protobuf field <code>uint32 turns_ball_in_goal_zone = 6;</code>
     * @param int $var
     * @return $this
     */
    public function setTurnsBallInGoalZone($var)
    {
        GPBUtil::checkUint32($var);
        $this->turns_ball_in_goal_zone = $var;

        return $this;
    }

    /**
     * Store the shot clock to control ball possession limit
     *
     * Generated from protobuf field <code>.lugo.ShotClock shot_clock = 7;</code>
     * @return \Lugo\ShotClock
     */
    public function getShotClock()
    {
        return $this->shot_clock;
    }

    /**
     * Store the shot clock to control ball possession limit
     *
     * Generated from protobuf field <code>.lugo.ShotClock shot_clock = 7;</code>
     * @param \Lugo\ShotClock $var
     * @return $this
     */
    public function setShotClock($var)
    {
        GPBUtil::checkMessage($var, \Lugo\ShotClock::class);
        $this->shot_clock = $var;

        return $this;
    }

}


<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: server.proto

namespace Lugo;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Stores all player attributes
 *
 * Generated from protobuf message <code>lugo.Player</code>
 */
class Player extends \Google\Protobuf\Internal\Message
{
    /**
     * Number of this player in its team (1-11)
     *
     * Generated from protobuf field <code>uint32 number = 1;</code>
     */
    private $number = 0;
    /**
     * Current player position
     *
     * Generated from protobuf field <code>.lugo.Point position = 2;</code>
     */
    private $position = null;
    /**
     * Current player velocity
     *
     * Generated from protobuf field <code>.lugo.Velocity velocity = 3;</code>
     */
    private $velocity = null;
    /**
     * Team side which its playing in (it's used to speed up some readings since the player element will be in a list
     * of players of a team)
     *
     * Generated from protobuf field <code>.lugo.Team.Side team_side = 4;</code>
     */
    private $team_side = 0;
    /**
     * Default position when it's position is reset
     *
     * Generated from protobuf field <code>.lugo.Point init_position = 5;</code>
     */
    private $init_position = null;
    /**
     * indicates the the player is jumping (goalkeepers only)
     *
     * Generated from protobuf field <code>bool is_jumping = 6;</code>
     */
    private $is_jumping = false;

    public function __construct() {
        \GPBMetadata\Server::initOnce();
        parent::__construct();
    }

    /**
     * Number of this player in its team (1-11)
     *
     * Generated from protobuf field <code>uint32 number = 1;</code>
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Number of this player in its team (1-11)
     *
     * Generated from protobuf field <code>uint32 number = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setNumber($var)
    {
        GPBUtil::checkUint32($var);
        $this->number = $var;

        return $this;
    }

    /**
     * Current player position
     *
     * Generated from protobuf field <code>.lugo.Point position = 2;</code>
     * @return \Lugo\Point
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Current player position
     *
     * Generated from protobuf field <code>.lugo.Point position = 2;</code>
     * @param \Lugo\Point $var
     * @return $this
     */
    public function setPosition($var)
    {
        GPBUtil::checkMessage($var, \Lugo\Point::class);
        $this->position = $var;

        return $this;
    }

    /**
     * Current player velocity
     *
     * Generated from protobuf field <code>.lugo.Velocity velocity = 3;</code>
     * @return \Lugo\Velocity
     */
    public function getVelocity()
    {
        return $this->velocity;
    }

    /**
     * Current player velocity
     *
     * Generated from protobuf field <code>.lugo.Velocity velocity = 3;</code>
     * @param \Lugo\Velocity $var
     * @return $this
     */
    public function setVelocity($var)
    {
        GPBUtil::checkMessage($var, \Lugo\Velocity::class);
        $this->velocity = $var;

        return $this;
    }

    /**
     * Team side which its playing in (it's used to speed up some readings since the player element will be in a list
     * of players of a team)
     *
     * Generated from protobuf field <code>.lugo.Team.Side team_side = 4;</code>
     * @return int
     */
    public function getTeamSide()
    {
        return $this->team_side;
    }

    /**
     * Team side which its playing in (it's used to speed up some readings since the player element will be in a list
     * of players of a team)
     *
     * Generated from protobuf field <code>.lugo.Team.Side team_side = 4;</code>
     * @param int $var
     * @return $this
     */
    public function setTeamSide($var)
    {
        GPBUtil::checkEnum($var, \Lugo\Team_Side::class);
        $this->team_side = $var;

        return $this;
    }

    /**
     * Default position when it's position is reset
     *
     * Generated from protobuf field <code>.lugo.Point init_position = 5;</code>
     * @return \Lugo\Point
     */
    public function getInitPosition()
    {
        return $this->init_position;
    }

    /**
     * Default position when it's position is reset
     *
     * Generated from protobuf field <code>.lugo.Point init_position = 5;</code>
     * @param \Lugo\Point $var
     * @return $this
     */
    public function setInitPosition($var)
    {
        GPBUtil::checkMessage($var, \Lugo\Point::class);
        $this->init_position = $var;

        return $this;
    }

    /**
     * indicates the the player is jumping (goalkeepers only)
     *
     * Generated from protobuf field <code>bool is_jumping = 6;</code>
     * @return bool
     */
    public function getIsJumping()
    {
        return $this->is_jumping;
    }

    /**
     * indicates the the player is jumping (goalkeepers only)
     *
     * Generated from protobuf field <code>bool is_jumping = 6;</code>
     * @param bool $var
     * @return $this
     */
    public function setIsJumping($var)
    {
        GPBUtil::checkBool($var);
        $this->is_jumping = $var;

        return $this;
    }

}


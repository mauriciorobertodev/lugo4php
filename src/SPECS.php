<?php
namespace Lugo4php;

class SPECS
{
    private const BASE_UNIT = 100;
    public const PLAYER_SIZE = 4 * self::BASE_UNIT;
    public const PLAYER_RECONNECTION_WAIT_TIME = 20;
    public const MAX_PLAYERS = 11;
    public const MIN_PLAYERS = 6;
    public const PLAYER_MAX_SPEED = 100.0;
    public const MAX_X_COORDINATE = 200 * self::BASE_UNIT;
    public const MAX_Y_COORDINATE = 100 * self::BASE_UNIT;
    public const FIELD_WIDTH = 200 * self::BASE_UNIT + 1;
    public const FIELD_HEIGHT = 100 * self::BASE_UNIT + 1;
    public const FIELD_NEUTRAL_CENTER = 100;
    public const BALL_SIZE = 2 * self::BASE_UNIT;
    public const BALL_DECELERATION = 10.0;
    public const BALL_MAX_SPEED = 4.0 * self::BASE_UNIT;
    public const BALL_MIN_SPEED = 2;
    public const BALL_TIME_IN_GOAL_ZONE = 40; // 40 / 20 fps : 2 seconds
    public const GOAL_WIDTH = 30 * self::BASE_UNIT;
    public const GOAL_MIN_Y = (100 * self::BASE_UNIT - 30 * self::BASE_UNIT) / 2;
    public const GOAL_MAX_Y = ((100 * self::BASE_UNIT - 30 * self::BASE_UNIT) / 2) + 30 * self::BASE_UNIT;
    public const GOAL_ZONE_RANGE = 14 * self::BASE_UNIT;
    public const GOAL_KEEPER_JUMP_DURATION = 3;
    public const GOALKEEPER_JUMP_DURATION = 3;
    public const GOAL_KEEPER_JUMP_SPEED = 2 * 100.0;
    public const GOALKEEPER_JUMP_SPEED = 2 * 100.0;
    public const GOALKEEPER_NUMBER = 1;
    public const GOALKEEPER_SIZE = 4 * self::BASE_UNIT * 2.3;
    public const SHOT_CLOCK_TIME = 300;
}

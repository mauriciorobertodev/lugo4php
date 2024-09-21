<?php

use Lugo4php\GameInspector;
use Lugo4php\Player;
use Lugo4php\PlayerState;
use Lugo4php\Side;
use Lugo4php\SPECS;
use Lugo\Ball as LugoBall;
use Lugo\GameSnapshot as LugoGameSnapshot;
use Lugo\Player as LugoPlayer;
use Lugo\Point as LugoPoint;
use Lugo\Vector as LugoVector;
use Lugo\Team as LugoTeam;
use Lugo\Team_Side;
use Lugo\Velocity as LugoVelocity;
use Tests\Classes\Positionable;

if (!function_exists('randomPositionable')) {
    function randomPositionable(): Positionable
    {
        $p = new Positionable();
		$p->setX(rand(0, SPECS::FIELD_WIDTH));
		$p->setY(rand(0, SPECS::FIELD_HEIGHT));
		return $p;
    }
}


if (!function_exists('randomPlayer')) {
    function randomPlayer(): Player
    {
        return new Player(
            rand(1, 11),
            false,
            Side::HOME,
            randomPoint(),
            randomPoint(),
            randomVelocity()
        ); 
    }
}

if (!function_exists('randomLugoPlayer')) {
    function randomLugoPlayer(
        int $number = null,
        LugoPoint $initialPosition = null,
        LugoPoint $position = null,
        LugoPoint $velocity = null,
        int $side = null,
    ): LugoPlayer
    {
        $player = new LugoPlayer();

        $player->setNumber($number ?? rand(1, SPECS::MAX_PLAYERS));
        $player->setInitPosition($initialPosition ?? randomLugoPoint());
        $player->setPosition($position ?? randomLugoPoint());
        $player->setVelocity($velocity ?? randomLugoVelocity());

        if($side === null) {
            $side =  (rand(0, 1) === 0 ? Team_Side::HOME :Team_Side::AWAY);
        }

        $player->setTeamSide($side);

        return $player;
    }
}

if (!function_exists('randomLugoPoint')) {
    function randomLugoPoint(): LugoPoint
    {
        $p = new LugoPoint();
		$p->setX(rand(0, SPECS::FIELD_WIDTH));
		$p->setY(rand(0, SPECS::FIELD_HEIGHT));
		return $p;
    }
}

if (!function_exists('randomLugoVector')) {
    function randomLugoVector(): LugoVector
    {
        $p = new LugoVector();
		$p->setX(rand(0, SPECS::FIELD_WIDTH));
		$p->setY(rand(0, SPECS::FIELD_HEIGHT));
		return $p;
    }
}

if (!function_exists('randomLugoVelocity')) {
    function randomLugoVelocity(float $maxSpeed = SPECS::BALL_MAX_SPEED): LugoVelocity
    {
        $velocity = new LugoVelocity();
        $velocity->setDirection(randomLugoVector());
        $velocity->setSpeed($maxSpeed);
        return $velocity;
    }
}

if (!function_exists('randomLugoTeam')) {
    function randomLugoTeam(int $side): LugoTeam
    {
        $team = new LugoTeam();
        $team->setSide($side);
        $team->setScore(rand(0, 100));
        $team->setPlayers([
            randomLugoPlayer(1, null, null, null, $side),
            randomLugoPlayer(2, null, null, null, $side),
            randomLugoPlayer(3, null, null, null, $side),
            randomLugoPlayer(4, null, null, null, $side),
            randomLugoPlayer(5, null, null, null, $side),
            randomLugoPlayer(6, null, null, null, $side),
            randomLugoPlayer(7, null, null, null, $side),
            randomLugoPlayer(8, null, null, null, $side),
            randomLugoPlayer(9, null, null, null, $side),
            randomLugoPlayer(10, null, null, null, $side),
            randomLugoPlayer(11, null, null, null, $side),
        ]);
        return $team;
    }
}

if (!function_exists('randomLugoBall')) {
    function randomLugoBall(): LugoBall
    {
        $ball = new LugoBall();

        $ball->setVelocity(randomLugoVelocity());
        $ball->setPosition(randomLugoPoint());

        return $ball;
    }
}

if (!function_exists('randomLugoGameSnapshot')) {
    function randomLugoGameSnapshot(): LugoGameSnapshot
    {
        $snapshot = new LugoGameSnapshot();

        $snapshot->setHomeTeam(randomLugoTeam(Team_Side::HOME));
        $snapshot->setAwayTeam(randomLugoTeam(Team_Side::AWAY));
        $snapshot->setBall(randomLugoBall());

        return $snapshot;
    }
}

if (!function_exists('removeAnsiCodes')) {
    function removeAnsiCodes(string $text): string {
        return preg_replace('/\033\[[0-9;]*m/', '', $text);
    }
}

if (!function_exists('randomLugoGameSnapshotInState')) {
    function randomGameInspectorInState(Side $side, int $number, PlayerState $playerState): LugoGameSnapshot {
        $snapshot = randomLugoGameSnapshot();

        $playerTeam = $side === Side::HOME ? $snapshot->getHomeTeam() : $snapshot->getAwayTeam();
        $opponentTeam = $side === Side::HOME ? $snapshot->getAwayTeam() : $snapshot->getHomeTeam();

        // A bola não tem nenhum holder
        if($playerState  === PlayerState::DISPUTING) {
            $ball = randomLugoBall();
            $ball->setHolder(null);
            $snapshot->setBall($ball);
        }
        
        // A bola está com o adversário
        if($playerState  === PlayerState::DEFENDING) {
            $ball = randomLugoBall();
            $ball->setHolder($opponentTeam->getPlayers()[rand(0, SPECS::MAX_PLAYERS -1)]);
            $snapshot->setBall($ball);
        }

        // A bola está com o bot atual
        if($playerState === PlayerState::HOLDING) {
            $ball = randomLugoBall();
            $ball->setHolder(array_values(array_filter([...$playerTeam->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $number))[0]);
            $snapshot->setBall($ball);
        }

        // A bola está com um bot to time do bot atual
        if($playerState  === PlayerState::SUPPORTING) {
            $friends = array_values(array_filter([...$playerTeam->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() != $number));

            $ball = randomLugoBall();
            $ball->setHolder($friends[rand(0, count($friends) - 1)]);
            $snapshot->setBall($ball);
        }

        return $snapshot;
    }
}

if (!function_exists('randomGameInspectorInDisputing')) {
    function randomGameInspectorInDisputing(Side $side, int $number, LugoGameSnapshot|null $snapshot = null): GameInspector {
        $snapshot = randomLugoGameSnapshot($side, $number, PlayerState::DISPUTING);
        return new GameInspector($side, $number, $snapshot);
    }
}

if (!function_exists('randomGameInspectorInHolding')) {
    function randomGameInspectorInHolding(Side $side, int $number, LugoGameSnapshot|null $snapshot = null): GameInspector {
        $snapshot = randomLugoGameSnapshot($side, $number, PlayerState::HOLDING);
        return new GameInspector($side, $number, $snapshot);
    }
}

if (!function_exists('randomGameInspectorInDefending')) {
    function randomGameInspectorInDefending(Side $side, int $number, LugoGameSnapshot|null $snapshot = null): GameInspector {
        $snapshot = randomLugoGameSnapshot($side, $number, PlayerState::DEFENDING);
        return new GameInspector($side, $number, $snapshot);
    }
}

if (!function_exists('randomGameInspectorInSupporting')) {
    function randomGameInspectorInSupporting(Side $side, int $number, LugoGameSnapshot|null $snapshot = null): GameInspector {
        $snapshot = randomLugoGameSnapshot($side, $number, PlayerState::SUPPORTING);
        return new GameInspector($side, $number, $snapshot);
    }
}

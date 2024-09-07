<?php

namespace Lugo4php;

use Exception;
use Lugo\GameSnapshot;
use Lugo\Order;
use Lugo4php\Interfaces\IGameInspector;
use Lugo4php\Side;

class GameInspector implements IGameInspector {
    private int $myNumber;
    private Player $me;
    private Side $mySide;
    private GameSnapshot $snapshot;

    public function __construct(Side $botSide, int $playerNumber, GameSnapshot $gameSnapshot) {
        $this->mySide = $botSide;
        $this->myNumber = $playerNumber;
        $this->snapshot = $gameSnapshot;

        $me = $this->getPlayer($this->mySide, $this->myNumber);

        if (!$me) {
            throw new \RuntimeException("Não foi possível encontrar o jogador {$botSide}-{$playerNumber}");
        }

        $this->me = $me;
    }

    public function getSnapshot(): GameSnapshot {
        return $this->snapshot;
    }

    public function getTurn(): int {
        return $this->snapshot->getTurn();
    }

    public function getMe(): Player {
        return $this->me;
    }

    public function getBall(): ?Ball {
        return $this->snapshot->getBall() ? Ball::fromLugoBall($this->snapshot->getBall()) : null;
    }

    public function getPlayer(Side $side, int $number): ?Player {
        $team = $this->getTeam($side);

        if($team) {
            foreach ($team->getPlayers() as $playerItem) {
                if($number === $playerItem->getNumber()) {
                    return $playerItem;
                };
            }
        }

        throw new Exception('Não tem o team ou o player');

        return null;
    }

    public function getTeam(Side $side): ?Team {
        if($side === Side::HOME) {
            return $this->snapshot->getHomeTeam() ? Team::fromLugoTeam($this->snapshot->getHomeTeam()) : null;
        }
        return $this->snapshot->getAwayTeam() ? Team::fromLugoTeam($this->snapshot->getAwayTeam()) : null;
    }

    public function getMyTeam(): ?Team {
        return $this->getTeam($this->mySide);
    }

    public function getOpponentTeam(): ?Team {
        return $this->getTeam($this->getOpponentSide());
    }

    public function getMySide(): Side {
        return $this->mySide;
    }

    public function getOpponentSide(): Side {
        return $this->mySide === Side::HOME ? Side::AWAY : Side::HOME;
    }

    /** @return Player[] */
    public function getMyPlayers(): array {
        return $this->getMyTeam()?->getPlayers() ?? [];
    }

    /** @return Player[] */
    public function getOpponentPlayers(): array {
        return $this->getOpponentTeam()?->getPlayers() ?? [];
    }

    public function getMyTeamGoalkeeper(): ?Player {
        return $this->getPlayer($this->getMySide(), SPECS::GOALKEEPER_NUMBER);
    }

    public function getOpponentGoalkeeper(): ?Player {
        return $this->getPlayer($this->getOpponentSide(), SPECS::GOALKEEPER_NUMBER);
    }

    public function getDefenseGoal(): Goal {
        return $this->mySide === Side::HOME ? Goal::HOME() : Goal::AWAY();
    }

    public function getAttackGoal(): Goal {
        return $this->mySide === Side::AWAY ? Goal::AWAY() : Goal::HOME();
    }

    public function makeOrderMove($target, $speed): Order {
        throw new \RuntimeException('Method not implemented');
    }

    public function makeOrderMoveMaxSpeed($target): Order {
        throw new \RuntimeException('Method not implemented');
    }

    public function makeOrderMoveFromPoint($origin, $target, $speed): Order {
        throw new \RuntimeException('Method not implemented');
    }

    public function makeOrderMoveFromVector($direction, $speed): Order {
        throw new \RuntimeException('Method not implemented');
    }

    public function makeOrderMoveByDirection($direction, $speed = null): Order {
        throw new \RuntimeException('Method not implemented');
    }

    public function makeOrderMoveToStop(): Order {
        throw new \RuntimeException('Method not implemented');
    }

    public function makeOrderJump($target, $speed): Order {
        throw new \RuntimeException('Method not implemented');
    }

    public function makeOrderKick($target, $speed): Order {
        throw new \RuntimeException('Method not implemented');
    }

    public function makeOrderKickMaxSpeed($target): Order {
        throw new \RuntimeException('Method not implemented');
    }

    public function makeOrderCatch(): Order {
        throw new \RuntimeException('Method not implemented');
    }
}

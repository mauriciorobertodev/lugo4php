<?php
namespace Lugo4php\Interfaces;

use Lugo\GameSnapshot;
use Lugo4php\Ball;
use Lugo4php\Side;
use Lugo4php\Goal;
use Lugo4php\Player;
use Lugo4php\Team;

interface IGameInspector {
    public function getSnapshot(): ?GameSnapshot;
    public function getTurn(): int;
    public function getMe(): Player;
    public function getBall(): ?Ball;
    public function getPlayer(Side $side, int $number): ?Player;
    public function getTeam(Side $side): ?Team;
    public function getMyTeam(): ?Team;
    public function getOpponentTeam(): ?Team;
    public function getMySide(): Side;
    public function getOpponentSide(): Side;
    public function getMyPlayers(): array;
    public function getOpponentPlayers(): array;
    public function getMyTeamGoalkeeper(): ?Player;
    public function getOpponentGoalkeeper(): ?Player;
    public function getDefenseGoal(): Goal;
    public function getAttackGoal(): Goal;
}

<?php
namespace Lugo4php\Interfaces;

use Lugo4php\Side;

interface ITeam {
	/** @return Lugo4php\Player[] */
    public function getPlayers(): array;
    public function getName(): string;
    public function getScore(): int;
    public function getSide(): Side;
    public function hasPlayer(int $number): bool;
}
<?php
namespace Lugo4php;

use Lugo4php\Interfaces\ITeam;
use Lugo\Team as LugoTeam;
use Lugo\Player as LugoPlayer;
use Lugo4php\Side;

class Team implements ITeam {
    public function __construct(
        private string $name,
        private int $score,
        private Side $side,
        private array $players,
    ) {}

    /** @return Player[] */
    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getSide(): Side
    {
        return $this->side;
    }

    public static function fromLugoTeam(LugoTeam $lugoTeam): Team
    {
        return new Team(
            $lugoTeam->getName(),
            $lugoTeam->getScore(),
            Side::from($lugoTeam->getSide()),
            array_map(fn(LugoPlayer $LugoPlayer) => Player::fromLugoPlayer($LugoPlayer), iterator_to_array($lugoTeam->getPlayers()))
        ); 
    }
}

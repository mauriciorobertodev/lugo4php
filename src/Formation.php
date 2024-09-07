<?php

namespace Lugo4php;

use InvalidArgumentException;
use Lugo4php\Interfaces\IFormation;

class Formation implements IFormation
{
    public function __construct(private array $positions, private string $name = '', private FormationType|null $type = null) {
		if(!$name) $this->name = uniqid();
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): Formation
	{
		$this->name = $name;
		return $this;
	}

	public function getType(): FormationType
	{
		return $this->type ?? FormationType::NOT_DEFINED;
	}

	public function setType(FormationType $type): IFormation
	{
		$this->type = $type;
		return $this;	
	}

    public function getPositionOf(int $playerNumber): Point
    {
        return $this->positions[$playerNumber] ?? throw new InvalidArgumentException("A posição do jogador {$playerNumber} não foi definida");
    }

    public function setPositionOf(int $playerNumber, Point $position): Formation
    {
        $this->positions[$playerNumber] = $position;
        return $this;
    }

    public function definePositionOf(int $playerNumber, float $x, float $y): Formation
    {
        $this->positions[$playerNumber] = new Point($x, $y);
        return $this;
    }

    public function getPositionOf01(): Point
    {
        return $this->getPositionOf(1);
    }

    public function setPositionOf01(Point $position): Formation
    {
        return $this->setPositionOf(1, $position);
    }

    public function definePositionOf01(float $x, float $y): Formation
    {
        return $this->definePositionOf(1, $x, $y);
    }

    public function getPositionOf02(): Point
    {
        return $this->getPositionOf(2);
    }

    public function setPositionOf02(Point $position): Formation
    {
        return $this->setPositionOf(2, $position);
    }

    public function definePositionOf02(float $x, float $y): Formation
    {
        return $this->definePositionOf(2, $x, $y);
    }

    public function getPositionOf03(): Point
    {
        return $this->getPositionOf(3);
    }

    public function setPositionOf03(Point $position): Formation
    {
        return $this->setPositionOf(3, $position);
    }

    public function definePositionOf03(float $x, float $y): Formation
    {
        return $this->definePositionOf(3, $x, $y);
    }

    public function getPositionOf04(): Point
    {
        return $this->getPositionOf(4);
    }

    public function setPositionOf04(Point $position): Formation
    {
        return $this->setPositionOf(4, $position);
    }

    public function definePositionOf04(float $x, float $y): Formation
    {
        return $this->definePositionOf(4, $x, $y);
    }

    public function getPositionOf05(): Point
    {
        return $this->getPositionOf(5);
    }

    public function setPositionOf05(Point $position): Formation
    {
        return $this->setPositionOf(5, $position);
    }

    public function definePositionOf05(float $x, float $y): Formation
    {
        return $this->definePositionOf(5, $x, $y);
    }

    public function getPositionOf06(): Point
    {
        return $this->getPositionOf(6);
    }

    public function setPositionOf06(Point $position): Formation
    {
        return $this->setPositionOf(6, $position);
    }

    public function definePositionOf06(float $x, float $y): Formation
    {
        return $this->definePositionOf(6, $x, $y);
    }

    public function getPositionOf07(): Point
    {
        return $this->getPositionOf(7);
    }

    public function setPositionOf07(Point $position): Formation
    {
        return $this->setPositionOf(7, $position);
    }

    public function definePositionOf07(float $x, float $y): Formation
    {
        return $this->definePositionOf(7, $x, $y);
    }

    public function getPositionOf08(): Point
    {
        return $this->getPositionOf(8);
    }

    public function setPositionOf08(Point $position): Formation
    {
        return $this->setPositionOf(8, $position);
    }

    public function definePositionOf08(float $x, float $y): Formation
    {
        return $this->definePositionOf(8, $x, $y);
    }

    public function getPositionOf09(): Point
    {
        return $this->getPositionOf(9);
    }

    public function setPositionOf09(Point $position): Formation
    {
        return $this->setPositionOf(9, $position);
    }

    public function definePositionOf09(float $x, float $y): Formation
    {
        return $this->definePositionOf(9, $x, $y);
    }

    public function getPositionOf10(): Point
    {
        return $this->getPositionOf(10);
    }

    public function setPositionOf10(Point $position): Formation
    {
        return $this->setPositionOf(10, $position);
    }

    public function definePositionOf10(float $x, float $y): Formation
    {
        return $this->definePositionOf(10, $x, $y);
    }

    public function getPositionOf11(): Point
    {
        return $this->getPositionOf(11);
    }

    public function setPositionOf11(Point $position): Formation
    {
        return $this->setPositionOf(11, $position);
    }

    public function definePositionOf11(float $x, float $y): Formation
    {
        return $this->definePositionOf(11, $x, $y);
    }

    public function toArray(): array
    {
        return $this->positions;
    }

    public static function createZeroed(): Formation
    {
        return new Formation(array_fill(1, SPECS::MAX_PLAYERS, new Point()));
    }

	public static function createFromArray(array $array): Formation
    {
        $formation = Formation::createZeroed();

        foreach ($array as $key => $value) {
            if (!is_int($key) || $key < 1 || $key > SPECS::MAX_PLAYERS) {
                throw new InvalidArgumentException("Chave inválida para o número do jogador: {$key}");
            }

            if (!is_array($value) || count($value) < 2) {
                throw new InvalidArgumentException("O valor para a posição do jogador {$key} deve ser um array com pelo menos 2 elementos.");
            }

			$value = array_values($value);
            $x = $value[0];
            $y = $value[1];

            if (!is_numeric($x) || !is_numeric($y)) {
                throw new InvalidArgumentException("As coordenadas para o jogador {$key} devem ser numéricas.");
            }

            $formation->definePositionOf($key, (float) $x, (float) $y);
        }

        return $formation;
    }
}

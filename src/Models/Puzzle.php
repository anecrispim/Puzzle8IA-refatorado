<?php
namespace Models;

class Puzzle {
    private array $state;
    private array $goal = [1,2,3,4,5,6,7,8,0];

    public function __construct(array $state) {
        if (count($state) !== 9) {
            throw new \InvalidArgumentException("O estado do puzzle deve ter 9 posições.");
        }
        $this->state = $state;
    }

    public function getState(): array {
        return $this->state;
    }

    public function getGoal(): array {
        return $this->goal;
    }

    public function isGoal(): bool  {
        return $this->state === $this->goal;
    }
}

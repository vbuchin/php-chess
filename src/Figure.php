<?php

abstract class Figure {
    protected $isBlack;

    protected $movesCount = 0;

    abstract public function isMoveAllowed(Board $board, Move $move) : bool;

    public function __construct($isBlack) {
        $this->isBlack = $isBlack;
    }

    /** @noinspection PhpToStringReturnInspection */
    public function __toString() {
        throw new \Exception("Not implemented");
    }

    public function getIsBlack() : bool {
        return $this->isBlack;
    }

    public function isFirstMove() : bool {
        return $this->movesCount === 0;
    }

    public function increaseMovesCount() {
        $this->movesCount++;
    }

}

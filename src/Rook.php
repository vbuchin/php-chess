<?php

class Rook extends Figure {
    public function __toString() {
        return $this->isBlack ? '♜' : '♖';
    }

    public function isMoveAllowed(Board $board, Move $move): bool
    {
        // TODO: Implement isMoveAllowed() method.
        return true;
    }
}

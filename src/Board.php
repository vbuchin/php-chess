<?php

final class Board {
    /**
     * @var Figure[]
     */
    private $figures = [];

    /**
     * @var Move[]
     */
    private $moves = [];

    private $bordersX = [ 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];

    private $bordersY = [1,2,3,4,5,6,7,8];

    public function __construct() {
        $this->figures['a'][1] = new Rook(false);
        $this->figures['b'][1] = new Knight(false);
        $this->figures['c'][1] = new Bishop(false);
        $this->figures['d'][1] = new Queen(false);
        $this->figures['e'][1] = new King(false);
        $this->figures['f'][1] = new Bishop(false);
        $this->figures['g'][1] = new Knight(false);
        $this->figures['h'][1] = new Rook(false);

        $this->figures['a'][2] = new Pawn(false);
        $this->figures['b'][2] = new Pawn(false);
        $this->figures['c'][2] = new Pawn(false);
        $this->figures['d'][2] = new Pawn(false);
        $this->figures['e'][2] = new Pawn(false);
        $this->figures['f'][2] = new Pawn(false);
        $this->figures['g'][2] = new Pawn(false);
        $this->figures['h'][2] = new Pawn(false);

        $this->figures['a'][7] = new Pawn(true);
        $this->figures['b'][7] = new Pawn(true);
        $this->figures['c'][7] = new Pawn(true);
        $this->figures['d'][7] = new Pawn(true);
        $this->figures['e'][7] = new Pawn(true);
        $this->figures['f'][7] = new Pawn(true);
        $this->figures['g'][7] = new Pawn(true);
        $this->figures['h'][7] = new Pawn(true);

        $this->figures['a'][8] = new Rook(true);
        $this->figures['b'][8] = new Knight(true);
        $this->figures['c'][8] = new Bishop(true);
        $this->figures['d'][8] = new Queen(true);
        $this->figures['e'][8] = new King(true);
        $this->figures['f'][8] = new Bishop(true);
        $this->figures['g'][8] = new Knight(true);
        $this->figures['h'][8] = new Rook(true);
    }

    public function move($movesInput) {
        if (!preg_match('/^([a-h])(\d)-([a-h])(\d)$/', $movesInput, $match)) {
            throw new \Exception("Incorrect move (regexp parsing)");
        }

        $xFrom = $match[1];
        $yFrom = $match[2];
        $xTo   = $match[3];
        $yTo   = $match[4];

        $spotFrom = new Spot($xFrom, $yFrom);
        $spotTo = new Spot($xTo, $yTo);
        $figure = $this->getFigureByCoordinates($spotFrom);
        $figureAtTargetPosition = $this->getFigureByCoordinates($spotTo);

        if ($figure === null) {
            throw new \Exception("Incorrect move (no figure at from position)");
        }

        if (!$this->isOrderCorrect($figure)){
            throw new \Exception("Incorrect move (wrong order)");
        }

        if ($figureAtTargetPosition !== null && ($figureAtTargetPosition->getIsBlack() === $figure->getIsBlack())) {
            throw new \Exception("Incorrect move (friendly fire)");
        }

        if (!$this->isMoveWithinBorders($xTo, $yTo)) {
            throw new \Exception("Incorrect move (out of borders)"); //a0 case
        }

        $move = new Move($figure, $spotFrom, $spotTo);

        if (!$figure->isMoveAllowed($this, $move)){
            throw new \Exception("Incorrect move (figure rules)");
        }

        $this->figures[$xTo][$yTo] = $figure;
        unset($this->figures[$xFrom][$yFrom]);

        $this->moves[] = $move;
        $figure->increaseMovesCount();
    }

    public function dump() {
        for ($y = 8; $y >= 1; $y--) {
            echo "$y ";
            for ($x = 'a'; $x <= 'h'; $x++) {
                if (isset($this->figures[$x][$y])) {
                    echo $this->figures[$x][$y];
                } else {
                    echo '-';
                }
            }
            echo "\n";
        }
        echo "  abcdefgh\n";
    }

    public function getFigureByCoordinates(Spot $spot) : ?Figure {
        if (isset($this->figures[$spot->getX()][$spot->getY()])) {
            return $this->figures[$spot->getX()][$spot->getY()];
        }

        return null;
    }

    private function isOrderCorrect($figure) : bool {

        //Black tries to do first move
        if (count($this->moves) === 0 && $figure->getIsBlack()) {
            return false;
        }

        //White goes first
        if (count($this->moves) === 0 && !$figure->getIsBlack()) {
            return true;
        }

        //Next move by the same color as previous
        $move = end($this->moves);
        if ($move->getFigure()->getIsBlack() === $figure->getIsBlack()) {
            return false;
        }

        return true;
    }

    public function isMoveWithinBorders($x, $y) : bool {
        if (in_array($x, $this->bordersX) && in_array($y, $this->bordersY)) {
            return true;
        }

        return false;
    }

    public function getLatestMove() : ?Move {
        return count($this->moves) > 0 ? $this->moves[array_key_last($this->moves)] : null;
    }
}

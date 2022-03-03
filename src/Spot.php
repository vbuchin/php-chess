<?php

class Spot {
    private string $x;
    private int $y;

    private ?Figure $figure = null;

    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    public function setFigure(Figure $figure) {
        $this->figure = $figure;
    }

    public function getFigure() : ?Figure {
        return $this->figure;
    }

    public function getX () : string {
        return $this->x;
    }

    public function getY () : string {
        return $this->y;
    }

    public function getKey () : string {
        return $this->x . $this->y;
    }

    /**
     * @param bool $isBlack
     * @param Board $board
     * @return Spot[]
     */
    public function getPossibleForwardMoves (bool $isBlack, Board $board) : array {
        $moves = [];

        $spot = $this->moveForward($isBlack, $board);

        while ($spot !== null) {

            $moves[] = $spot;

            if($spot->figure !== null) {
                break;
            }

            $spot = $spot->moveForward($isBlack, $board);
        }

        return $moves;
    }

    public function getPossibleBackwardMoves () {}

    /**
     * @param bool $isBlack
     * @param Board $board
     * @return Spot[]
     */
    public function getPossibleForwardRightMoves (bool $isBlack, Board $board) : array {
        $moves = [];

        $spot = $this->moveForwardLeft($isBlack, $board);

        while ($spot !== null) {

            $moves[] = $spot;

            if($spot->figure !== null) {
                break;
            }

            $spot = $spot->moveForwardLeft($isBlack, $board);
        }

        return $moves;
    }

    /**
     * @param bool $isBlack
     * @param Board $board
     * @return Spot[]
     */
    public function getPossibleForwardLeftMoves (bool $isBlack, Board $board) : array {
        $moves = [];

        $spot = $this->moveForwardLeft($isBlack, $board);

        while ($spot !== null) {

            $moves[] = $spot;

            if($spot->figure !== null) {
                break;
            }

            $spot = $spot->moveForwardLeft($isBlack, $board);
        }

        return $moves;
    }

    public function getPossibleBackwardRightMoves () {

    }

    public function getPossibleBackwardLeftMoves () {}

    public function moveForward(bool $isBlack, Board $board) : ?Spot {
        if ($isBlack) {
            $newY = $this->y - 1;
        } else {
            $newY = $this->y + 1;
        }

        return $this->move($this->x, $newY, $board);
    }

    public function moveBackward() {

    }

    public function moveLeft(bool $isBlack, Board $board) : ?Spot {
        if ($isBlack) {
            $newX = chr(ord($this->x) + 1);
        } else {
            $newX = chr(ord($this->x) - 1);
        }

        return $this->move($newX, $this->y, $board);
    }

    public function moveRight(bool $isBlack, Board $board) : ?Spot {
        if ($isBlack) {
            $newX = chr(ord($this->x) - 1);
        } else {
            $newX = chr(ord($this->x) + 1);
        }

        return $this->move($newX, $this->y, $board);
    }

    public function moveForwardLeft(bool $isBlack, Board $board) :? Spot {
        if ($isBlack) {
            $newY = $this->y - 1;
            $newX = chr(ord($this->x) + 1);
        } else {
            $newY = $this->y + 1;
            $newX = chr(ord($this->x) - 1);
        }

        return $this->move($newX, $newY, $board);
    }

    public function moveForwardRight(bool $isBlack, Board $board) :? Spot {
        if ($isBlack) {
            $newY = $this->y - 1;
            $newX = chr(ord($this->x) - 1);
        } else {
            $newY = $this->y + 1;
            $newX = chr(ord($this->x) + 1);
        }

        return $this->move($newX, $newY, $board);
    }

    public function moveBackwardLeft () {

    }

    public function moveBackwardRight() {

    }

    private function move($x, $y, Board $board) : ?Spot {
        if (!$board->isMoveWithinBorders($x, $y)) {
            return null;
        }

        $figure = $board->getFigureByCoordinates(new Spot($x, $y));
        $spot = new Spot($x, $y);
        if ($figure) {
            $spot->setFigure($figure);
        }

        return $spot;
    }

    public static function getDiffBetweenSpotYAxis(Spot $spot1, Spot $spot2) : int {
       return abs($spot1->getY() - $spot2->getY());
    }

    public static function compareSpotsSamePosition(Spot $spot1, Spot $spot2) : bool {
        return $spot1->getX() === $spot2->getX() && $spot1->getY() === $spot2->getY();
    }

}
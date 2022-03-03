<?php

final class Pawn extends Figure {
    public function __toString() {
        return $this->isBlack ? '♟' : '♙';
    }

    const SPOT_MOVES = 1;
    const SPOT_MOVES_FIST_MOVE = 2;

    public function isMoveAllowed(Board $board, Move $move) : bool
    {
        $allowedMoves = [];

        $forwardMoves = $move->getSpotFrom()->getPossibleForwardMoves($move->getFigure()->getIsBlack(), $board);

        // move
        $slice = array_slice($forwardMoves, 0, self::SPOT_MOVES);
        if ($this->isFirstMove()) {
           $slice = array_slice($forwardMoves, 0, self::SPOT_MOVES_FIST_MOVE);
        }

        //if there is a figure at last spot unset from possible moves
        $lastElement = end($slice);
        if ($lastElement && $lastElement->getFigure()) {
            array_pop($slice);
        }

        foreach ($slice as $item) {
            $allowedMoves[$item->getKey()] = $item;
        }

        // capture move
        $forwardLeftMoves = $move->getSpotFrom()->getPossibleForwardLeftMoves($move->getFigure()->getIsBlack(), $board);

        $lastElement = end($forwardLeftMoves);
        if ($lastElement && $lastElement->getFigure()) {
            $allowedMoves[$lastElement->getKey()] = $lastElement;
        }

        $forwardRightMoves = $move->getSpotFrom()->getPossibleForwardRightMoves($move->getFigure()->getIsBlack(), $board);

        $lastElement = end($forwardRightMoves);
        if ($lastElement && $lastElement->getFigure()) {
            $allowedMoves[$lastElement->getKey()] = $lastElement;
        }

        // En passant special move
        // last move has been made by pawn (get spotTo)
        // last move by pawn was long (diff spotFrom and spotTO == 2)
        // current pawn has left or right pawn position (instance of pawn)
        // position from history to == current position of figures (right or left)
        $latestMove = $board->getLatestMove();
        if ($latestMove && is_a($latestMove->getFigure(), Pawn::class)) {
            $spotYDifference = Spot::getDiffBetweenSpotYAxis($latestMove->getSpotTo(), $latestMove->getSpotFrom());
            if ($spotYDifference === self::SPOT_MOVES_FIST_MOVE) {

                $spotLeft = $move->getSpotFrom()->moveLeft($move->getFigure()->getIsBlack(), $board);
                $spotRight = $move->getSpotFrom()->moveRight($move->getFigure()->getIsBlack(), $board);

                if ($spotLeft) {
                    $this->checkSpecialMove($spotLeft,$board,$move,$latestMove,$allowedMoves);
                }
                if ($spotRight) {
                    $this->checkSpecialMove($spotRight,$board,$move,$latestMove,$allowedMoves);
                }
            }
        }

        // check for allowed moves
        if (array_key_exists($move->getSpotTo()->getKey(), $allowedMoves)) {
            return true;
        }

        return false;
    }

    private function checkSpecialMove(Spot $spot, Board $board, Move $move, Move $latestMove, &$allowedMoves) : void {
        $figure = $board->getFigureByCoordinates($spot);

        if (!$figure && !is_a($figure, Pawn::class)) {
            return;
        }

        if(Spot::compareSpotsSamePosition($latestMove->getSpotTo(), $spot)) {
            $allowedMoves[$move->getSpotTo()->getKey()] = $move->getSpotTo();
        }
    }
}

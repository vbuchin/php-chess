<?php

class Move {
    private Spot $spotFrom;

    private Spot $spotTo;

    private Figure $figure;

    public function __construct(Figure $figure, Spot $spotFrom, Spot $spotTo) {
        $this->figure = $figure;
        $this->spotFrom = $spotFrom;
        $this->spotTo = $spotTo;
    }

    public function getFigure() : Figure {
        return $this->figure;
    }

    public function getSpotFrom() : Spot {
        return $this->spotFrom;
    }

    public function getSpotTo() : Spot {
        return $this->spotTo;
    }
}
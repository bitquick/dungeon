<?php

namespace App\Dungeon\Math;

trait CoordinateTrait
{
    /**
     * @var Coordinate|null
     */
    protected $coordinate = null;

    /**
     * Set the coordinate
     * @param Coordinate $coordinate
     */
    public function setCoordinate(Coordinate $coordinate) {
        $this->coordinate = $coordinate;
    }

    /**
     * Get the coordinate
     * @return Coordinate|null
     */
    public function getCoordinate() {
        return $this->coordinate;
    }

    /**
     * Get adjacent coordinates for the coordinate.
     * @return array
     */
    public function getAdjacentCoordinates() {
        $adjacentCoordinates = [];
        if ($this->coordinate) {
            $adjacentCoordinates[] = $this->coordinate->add(1,0);
            $adjacentCoordinates[] = $this->coordinate->add(-1,0);
            $adjacentCoordinates[] = $this->coordinate->add(0,1);
            $adjacentCoordinates[] = $this->coordinate->add(0,-1);
        }
        return $adjacentCoordinates;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/4/2020
 * Time: 5:59 PM
 */

namespace App\Dungeon\Components;


use App\Dungeon\Math\Coordinate;

/**
 * Trait DoorsTrait
 *
 * An object with this trait may have a number of doors positioned on 4 sides
 *
 * @package App\Dungeon\Components
 */
trait DoorsTrait
{
    protected $maxDoors = 4;

    protected $doors = [];

    public function getMaxDoors() {
        return $this->maxDoors;
    }

    public function hasTopDoor() {
        return $this->hasDoor(new Coordinate(0,1));
    }

    public function hasBottomDoor() {
        return $this->hasDoor(new Coordinate(0,-1));
    }

    public function hasRightDoor() {
        return $this->hasDoor(new Coordinate(1,0));
    }

    public function hasLeftDoor() {
        return $this->hasDoor(new Coordinate(-1,0));
    }

    public function hasDoor(Coordinate $coordinate) : bool {
        foreach($this->doors as $existingDoor) {
            if ($coordinate->equals($existingDoor->x, $existingDoor->y)) {
                return true;
            }
        }
        return false;
    }
}

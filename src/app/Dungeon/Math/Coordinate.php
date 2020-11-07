<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/1/2020
 * Time: 4:12 PM
 */

namespace App\Dungeon\Math;


class Coordinate
{
    public $x = 0;
    public $y = 0;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX() {
        return $this->x;
    }

    public function getY() {
        return $this->y;
    }

    public function x() {
        return $this->getX();
    }

    public function y() {
        return $this->getY();
    }

    public function add($x, $y) {
        return new Coordinate($this->x + $x, $this->y + $y);
    }

    public function diff($x, $y) {
        return new Coordinate($this->x - $x, $this->y - $y);
    }

    public function equals($x, $y) {
        if ($this->x == $x && $this->y == $y) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->x . "," . $this->y;
    }
}

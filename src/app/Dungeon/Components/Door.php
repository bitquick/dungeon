<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/3/2020
 * Time: 2:41 PM
 */

namespace App\Dungeon\Components;

use App\Dungeon\Math\Coordinate;

class Door
{
    /**
     * @var Coordinate
     */
    protected $position;

    /**
     * @var bool
     */
    protected $locked = false;
}

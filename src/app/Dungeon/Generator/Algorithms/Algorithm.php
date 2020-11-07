<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/3/2020
 * Time: 9:02 AM
 */

namespace App\Dungeon\Generator\Algorithms;


use App\Dungeon\Generator\Algorithms\DungeonModelOne;
use App\Dungeon\Generator\Algorithms\DungeonModelZero;
use InvalidArgumentException;

abstract class Algorithm
{
    const ROOM_PLACEMENT_ALGORITHMS = [
        DungeonModelZero::class,
        DungeonModelOne::class,
    ];

    public static function get($key) {
        if (empty(self::ROOM_PLACEMENT_ALGORITHMS[$key])) {
            throw new InvalidArgumentException("Algorithm {$key} doesn't exist");
        }
        return self::ROOM_PLACEMENT_ALGORITHMS[$key];
    }
}

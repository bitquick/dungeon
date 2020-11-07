<?php

namespace App\Dungeon\Generator\Algorithms;

use App\Dungeon\Generator\Algorithms\DungeonModelInterface;
use App\Dungeon\DungeonMap;
use App\Dungeon\Math\Coordinate;

abstract class AbstractAlgorithm implements DungeonModelInterface
{

    /**
     * @param array $input
     * @return mixed
     */
    public static function pluck_random(array &$input) {
        $count = count($input);
        if ($count === 0) {
            return null;
        }

        if ($count === 1) {
            return array_pop($input);
        }

        $offset = rand(0, $count-1);
        $plucked = array_splice($input, $offset, 1);
        return $plucked[0];
    }

    public static function countAdjacentRooms(DungeonMap $map, Coordinate $coordinate) {
        $test = [];
        $test[] = $coordinate->add(1,0);
        $test[] = $coordinate->add(-1,0);
        $test[] = $coordinate->add(0,1);
        $test[] = $coordinate->add(0,-1);

        $roomCount = 0;
        foreach($test as $t) {
            if (!self::empty($map, $t)) {
                $roomCount++;
            }
        }
        return $roomCount;
    }

    public static function empty(DungeonMap $map, Coordinate $coordinate) {
        return $map->getRoom($coordinate) ? false : true;
    }
}

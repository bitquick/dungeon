<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/5/2020
 * Time: 7:16 AM
 */

namespace App\Dungeon\Generator\Algorithms;


use App\Dungeon\Components\Rooms\Room;
use App\Dungeon\Dungeon;
use App\Dungeon\Math\Coordinate;

interface DungeonModelInterface
{
    public static function determinePositionForNextRoom(Dungeon $dungeon, Room $room, array &$log = []);
    public static function configureRoomForPlacement(Room $room, Coordinate $coordinate, &$log = []);

}


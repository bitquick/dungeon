<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/2/2020
 * Time: 12:18 AM
 */

namespace App\Dungeon\Factory;

use App\Dungeon\Components\Rooms\BossRoom;
use App\Dungeon\Components\Rooms\EntryRoom;
use App\Dungeon\Components\Rooms\HallwayRoom;
use App\Dungeon\Components\Rooms\Room;
use App\Dungeon\Components\Rooms\TreasureRoom;
use App\Dungeon\DungeonMap;

class RoomFactory
{
    public const RANDOM_ROOMS = [
        HallwayRoom::class,
        Room::class
    ];

    public static function createRoom() {
        $roll = rand(0,20);
        if ($roll > 10) {
            return new HallwayRoom;
        } else {
            return new Room;
        }
    }

    public function getRooms(int $rooms, DungeonMap $map) {
        $collection = [];

        if ($rooms > 0) {
            $collection[] = new EntryRoom($map);
            $rooms--;
        }

        while($rooms > 2) {
            $collection[] = $this->getRandomRoom($map);
            $rooms--;
        }

        if ($rooms === 2) {
            $collection[] = new BossRoom($map);
            $collection[] = new TreasureRoom($map);
        } elseif ($rooms === 1) {
            $collection[] = $this->getRandomRoom($map);
        }

        return $collection;
    }

    public function getRandomRoom(?DungeonMap &$map) {
        $roomClassKey = array_rand(RoomFactory::RANDOM_ROOMS);
        $roomClass = RoomFactory::RANDOM_ROOMS[$roomClassKey];
        return new $roomClass($map);
    }
}

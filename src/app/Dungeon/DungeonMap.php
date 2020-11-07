<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/1/2020
 * Time: 3:58 PM
 */

namespace App\Dungeon;

use App\Dungeon\Components\Rooms\Room;
use App\Dungeon\Math\Coordinate;

class DungeonMap
{
    private $rooms = [];
    private $coordinateList = [];

    public function insertRoom(Room $room, $x, $y) {
        if (!isset($this->rooms[$x])) {
            $this->rooms[$x] = [];
        }

        $this->rooms[$x][$y] = $room;
        $this->coordinateList[] = new Coordinate($x, $y);
    }

    /**
     * @param null $index
     * @return array|Coordinate
     */
    public function getAllRoomCoordinates($index = null) {
        if ($index === null) {
            return $this->coordinateList;
        } else {
            return $this->coordinateList[$index];
        }
    }

    public function getRoomList() {
        $roomList = [];
        foreach($this->coordinateList as $coordinate) {
            $roomList[] = $this->getRoom($coordinate);
        }
        return $roomList;
    }

    /**
     * @param Coordinate $coordinate
     * @return Room|null
     */
    public function getRoom(Coordinate $coordinate) : ?Room {
        return $this->rooms[$coordinate->x][$coordinate->y] ?? null;
    }

    public function getRooms() {
        return $this->rooms;
    }

    public function getMapRange() {
        $xMin = 0;
        $xMax = 0;
        $yMin = 0;
        $yMax = 0;

        $roomIndex = $this->getAllRoomCoordinates();
        foreach($roomIndex as $coordinate) {
            $x = $coordinate->x;
            $y = $coordinate->y;
            if ($x > $xMax) {
                $xMax = $x;
            } elseif ($x < $xMin) {
                $xMin = $x;
            }
            if ($y > $yMax) {
                $yMax = $y;
            } elseif ($y < $yMin) {
                $yMin = $y;
            }
        }

        return [$xMin, $xMax, $yMin, $yMax];
    }
}

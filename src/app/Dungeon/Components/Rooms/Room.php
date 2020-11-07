<?php

namespace App\Dungeon\Components\Rooms;

use App\Dungeon\Components\DoorsTrait;
use App\Dungeon\Components\DungeonMapReferenceTrait;
use App\Dungeon\DungeonMap;
use App\Dungeon\Math\CoordinateTrait;

class Room
{
    /** This room may belong to a dungeon and will contain a reference to it */
    use DungeonMapReferenceTrait;

    /** This room has coordinates specific to it's dungeon map reference */
    use CoordinateTrait;

    /** This room has doors **/
    use DoorsTrait;

    /**
     * Create a room and optionally set the map it belongs to.
     * @param DungeonMap|null $map
     */
    public function __construct(?DungeonMap &$map = null) {
        $this->setMap($map);
    }

    /**
     * This room is a starting point to this map.
     * @var bool
     */
    protected $startingPoint = false;

    /**
     * Whether or not this room is a starting point.
     * @return bool
     */
    public function startingPoint() {
        return $this->startingPoint;
    }



    public function getAdjacentRooms() {
        $adjacentRooms = [];
        $possibleCoordinates = $this->getAdjacentCoordinates();
        foreach($possibleCoordinates as $possibleCoordinate) {
            if ($room = $this->map->getRoom($possibleCoordinate)) {
                $adjacentRooms[] = $room;
            }
        }
        return $adjacentRooms;
    }

    public function canConnect() {
        return count($this->doors) < $this->maxDoors;
    }

    public function canConnectTo(Room $room) {
        return $this->canConnect() && count($room->doors) < $room->maxDoors;
    }

    public function connectTo(Room $room, $reciprocate = true) {
        $door = $room->coordinate->diff($this->coordinate->x, $this->coordinate->y);
        $this->doors[] = $door;
        if ($reciprocate) {
            $room->connectTo($this, false);
        }
    }

    public function getPrettyRoomName() {
        $fullType = get_class($this);
        $typeParts = explode('\\', $fullType);
        return array_pop($typeParts);
    }

    public function getRoomType() {
        return get_class($this);
    }


}

<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/1/2020
 * Time: 7:57 PM
 */

namespace App\Dungeon\Generator\Algorithms;


use App\Dungeon\Components\Rooms\BossRoom;
use App\Dungeon\Components\Rooms\BridgeRoom;
use App\Dungeon\Components\Rooms\Room;
use App\Dungeon\Components\Rooms\TreasureRoom;
use App\Dungeon\Dungeon;
use App\Dungeon\DungeonMap;
use App\Dungeon\Generator\Algorithms\AbstractAlgorithm;
use App\Dungeon\Generator\DungeonGenerator;
use App\Dungeon\Math\Coordinate;
use Exception;

class DungeonModelOne extends AbstractAlgorithm
{
    public static $roomsTried = [];
    public static $coordinatesTried = [];

    /**
     * @param Dungeon $dungeon
     * @param Room $room
     * @return Coordinate
     * @throws Exception
     */
    public static function determinePositionForNextRoom(Dungeon $dungeon, Room $room, array &$log = []) {
        $currentRoomType = $room->getPrettyRoomName();
        $roomIndex = $dungeon->getMap()->getAllRoomCoordinates();

        if (empty($roomIndex)) {
            $coordinate = new Coordinate(0,0);
            DungeonGenerator::log("Selected placement for {$currentRoomType} at position {$coordinate}");
            return $coordinate;
        }

        if ($room->getRoomType() === TreasureRoom::class) {
            foreach($roomIndex as $coordinate) {
                $room = $dungeon->getMap()->getRoom($coordinate);
                if ($room->getRoomType() === BossRoom::class) {
                    $possibleCoordinates = $room->getAdjacentCoordinates();
                    while(!empty($possibleCoordinates) && $possibleCoordinate = self::pluck_random($possibleCoordinates)) {
                        if ($possibleCoordinate->equals(0, -1)) {
                            DungeonGenerator::log('"Cannot select tile below EntryRoom"');
                            continue;
                        }
                        $room = $dungeon->getMap()->getRoom($possibleCoordinate);
                        if (!$room) {
                            DungeonGenerator::log("Selected placement for {$currentRoomType} at position {$coordinate}");
                            return $possibleCoordinate;
                        }
                    }
                }
            }
        }

        while(!empty($roomIndex) && $roomCoordinate = self::pluck_random($roomIndex)) {
            DungeonGenerator::log("Searching for a room to connect to...");
            DungeonGenerator::log("Selected $roomCoordinate as possible candidate");
            $possibleCoordinates = [];
            $room = $dungeon->getMap()->getRoom($roomCoordinate);
            $possibleCoordinates = $room->getAdjacentCoordinates();

            while(!empty($possibleCoordinates) && $possibleCoordinate = self::pluck_random($possibleCoordinates)) {
                if ($possibleCoordinate->equals(0, -1)) {
                    DungeonGenerator::log("Cannot select tile below EntryRoom");
                    continue;
                }

                if (self::surrounded($dungeon->getMap(), $possibleCoordinate, $room)) {
                    DungeonGenerator::log("Cannot place a room that would be entirely surrounded at position {$possibleCoordinate}");
                    continue;
                }

                if (!self::wontSurround($dungeon->getMap(), $possibleCoordinate)) {
                    DungeonGenerator::log("Cannot palce a room that would cause another room to be entirely surrounded {$possibleCoordinate}");
                    continue;
                }

                $room = $dungeon->getMap()->getRoom($possibleCoordinate);
                if (!$room) {
                    DungeonGenerator::log("Selected placement for new room at position {$possibleCoordinate}");
                    return $possibleCoordinate;
                    break;
                }
            }
        }
        throw new Exception("Unable to determine location of next room.");
    }

    /**
     * Connects room to neighboring rooms
     * @param Room $room
     * @param Coordinate $coordinate
     * @param array $log
     * @return Room
     */
    public static function configureRoomForPlacement(Room $room, Coordinate $coordinate, &$log = []) {
        $room->setCoordinate($coordinate);
        $adjacentRooms = $room->getAdjacentRooms();
        shuffle($adjacentRooms); // shuffle the rooms so we don't always connect them in the same order

        /* @var $adjacentRoom Room */
        $connectedOnce = false;
        foreach($adjacentRooms as $adjacentRoom) {
            if (!$room->canConnect() || ($connectedOnce && rand(0,1))) {
                break;
            }

            if ($room->canConnectTo($adjacentRoom) && $adjacentRoom->canConnectTo($room)) {
                // echo "Connecting " . $room->getCoordinate() . " to room " . $adjacentRoom->getCoordinate() . "<br />";
                $room->connectTo($adjacentRoom);
                $connectedOnce = true;
            }
        }
        return $room;
    }

    public static function surrounded(DungeonMap $map, Coordinate $coordinate, Room $room) {
        $maxRooms = $room->getMaxDoors();
//        if ($room && is_subclass_of($room, BridgeRoom::class)) {
//            $maxRooms = 2;
//        } else {
//            $maxRooms = 3;
//        }

        $tests = [];
        $tests[] = $coordinate->add(1,0);
        $tests[] = $coordinate->add(-1,0);
        $tests[] = $coordinate->add(0,1);
        $tests[] = $coordinate->add(0,-1);

        $count = 0;
        foreach($tests as $test) {
            if (!self::empty($map, $test)) {
                $count++;
            }
        }

        if ($count === $maxRooms) {
            return true;
        } else {
            return false;
        }
    }

    public static function wontSurround(DungeonMap $map, Coordinate $coordinate) {
        $tests = [];
        $tests[] = $coordinate->add(1,0);
        $tests[] = $coordinate->add(-1,0);
        $tests[] = $coordinate->add(0,1);
        $tests[] = $coordinate->add(0,-1);

        foreach($tests as $test) {
            $room = $map->getRoom($test);
            if ($room) {
                $maxRooms = $room->getMaxDoors();
            } else {
                $maxRooms = 3;
            }

            if (self::countAdjacentRooms($map, $test) === $maxRooms) {
                return false;
            }
        }
        return true;
    }



}

<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/1/2020
 * Time: 7:57 PM
 */

namespace App\Dungeon\Generator\Algorithms;


use App\Dungeon\Components\Rooms\BridgeRoom;
use App\Dungeon\Components\Rooms\Room;
use App\Dungeon\Dungeon;
use App\Dungeon\DungeonMap;
use App\Dungeon\Generator\Algorithms\AbstractAlgorithm;
use App\Dungeon\Generator\DungeonGenerator;
use App\Dungeon\Math\Coordinate;
use Exception;

class DungeonModelZero extends AbstractAlgorithm
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
        $roomIndex = $dungeon->getMap()->getAllRoomCoordinates();

        if (empty($roomIndex)) {
            $coordinate = new Coordinate(0,0);
            DungeonGenerator::log("Selected placement for first room at position {$coordinate}");
            return $coordinate;
        }

        while(!empty($roomIndex) && $roomCoordinate = self::pluck_random($roomIndex)) {
            DungeonGenerator::log("Searching for a room to connect to...");
            DungeonGenerator::log("Selected $roomCoordinate as possible candidate");
            $possibleCoordinates = [];
            $room = $dungeon->getMap()->getRoom($roomCoordinate);
            $possibleCoordinates[] = $roomCoordinate->add(1,0);
            $possibleCoordinates[] = $roomCoordinate->add(-1,0);
            $possibleCoordinates[] = $roomCoordinate->add(0,1);
            $possibleCoordinates[] = $roomCoordinate->add(0,-1);

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
        return $room;
    }

    public static function surrounded(DungeonMap $map, Coordinate $coordinate, Room $room) {
        if ($room && is_subclass_of($room, BridgeRoom::class)) {
            $maxRooms = 2;
        } else {
            $maxRooms = 3;
        }

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
            if ($room && is_subclass_of($room, BridgeRoom::class)) {
                $maxRooms = 2;
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

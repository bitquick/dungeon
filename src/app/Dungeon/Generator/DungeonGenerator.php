<?php

namespace App\Dungeon\Generator;

use App\Dungeon\Dungeon;
use App\Dungeon\Generator\Algorithms\Algorithm;
use App\Dungeon\Components\Rooms\Room;
use App\Dungeon\Math\Coordinate;
use App\Dungeon\Factory\RoomFactory;
use Closure;
use Illuminate\Support\Collection;
use SebastianBergmann\Timer\Duration;
use SebastianBergmann\Timer\Timer;

/**
 * Class DungeonGenerator
 *
 * Generate a dungeon object.
 *
 * @package App\Dungeon
 */
class DungeonGenerator
{
    /**
     * Callback to be run after each build step.
     * @var Closure
     */
    private $stepCallback = null;

    private $roomPlacementAlgorithm = 1;

    private $generationDuration = null;

    private $recordBuildSteps = false;

    private $buildSteps;

    private static $log = [];

    public function __construct()
    {
        $this->buildSteps = new Collection();
    }

    public function recordBuildSteps($value = true) {
        $this->recordBuildSteps = $value;
    }

    public function getBuildSteps() {
        return $this->buildSteps;
    }

    /**
     * Generates a dungeon with specified number of rooms
     * @param int $numberOfRooms
     * @return Dungeon
     */
    public function generateDungeon(int $numberOfRooms) {
        $timer = new Timer();
        $timer->start();
        $this->buildSteps = new Collection();
        $dungeon = new Dungeon();
        $roomFactory = new RoomFactory();
        $roomList = $roomFactory->getRooms($numberOfRooms, $dungeon->getMap());
        foreach($roomList as $room) {
            $dungeon = $this->addRoom($dungeon, $room);
            $this->runCallback($dungeon);
            $this->recordBuildStep($dungeon);
        }

        $this->generationDuration = $timer->stop();

        return $dungeon;
    }

    /**
     * Get the amount of time it took to generate the most recent dungeon
     * @return Duration
     */
    public function getDuration() : Duration {
        return $this->generationDuration;
    }

    /**
     * Set the build step callback
     * @param Closure $callback
     */
    public function setStepCallback(Closure $callback) {
        $this->stepCallback = $callback;
    }

    /**
     * Run the build step callback
     * @param $dungeon
     */
    private function runCallback($dungeon) {
        if (!empty($this->stepCallback)) {
            $function = $this->stepCallback;
            $function($dungeon);
        }
    }

    /**
     * Set the algorithm used to configure and place rooms in the dungeon
     * @param $algorithm
     */
    public function setRoomPlacementAlgorithm($algorithm) {
        $this->roomPlacementAlgorithm = $algorithm;
    }

    /**
     * Add a room to the dungeon
     * @param Dungeon $dungeon
     * @param Room $room
     * @return Dungeon
     */
    public function addRoom(Dungeon $dungeon, Room $room) {
        $log = [];
        $roomType = $room->getPrettyRoomName();
        self::log("Attempting to add a {$roomType}");
        $coordinate = $this->determinePositionForNextRoom($dungeon, $room, $log);
        $room = $this->configureRoom($room, $coordinate, $log);

        self::log("Inserting a room of type {$roomType} at position {$coordinate}");
        $dungeon->getMap()->insertRoom($room, $coordinate->x, $coordinate->y);
        $dungeon->log($log);
        return $dungeon;
    }

    /**
     * Based on the existing dungeon, add a room to it.
     * @param $dungeon
     * @param $room
     * @param $log
     * @return mixed
     */
    private function determinePositionForNextRoom($dungeon, $room, &$log) {
        $algorithm = Algorithm::get($this->roomPlacementAlgorithm);
        $coordinate = $algorithm::determinePositionForNextRoom($dungeon, $room, $log);
        return $coordinate;
    }

    /**
     * Configure
     * @param Room $room
     * @param Coordinate $coordinate
     * @param $log
     * @return Room
     */
    private function configureRoom(Room $room, Coordinate $coordinate, &$log) {
        $algorithm = Algorithm::get($this->roomPlacementAlgorithm);
        $room = $algorithm::configureRoomForPlacement($room, $coordinate, $log);
        return $room;
    }

    private function recordBuildStep($dungeon) {
        if ($this->recordBuildSteps) {
            $buildStep = new DungeonBuildStep();
            $buildStep->setDungeon($dungeon);
            $buildStep->setLog(self::dumpLog());
            $this->buildSteps->push($buildStep);
        }
    }

    public static function log($message) {
        array_push(self::$log, $message);
    }

    public static function dumpLog() {
        $log = self::$log;
        self::$log = [];
        return $log;
    }
}

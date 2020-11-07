<?php

namespace App\Dungeon;


use App\Dungeon\Math\Coordinate;

class Dungeon
{
    protected $buildLog = [];

    /**
     * @var DungeonMap|null
     */
    protected $map = null;

    public function __construct()
    {
        $map = new DungeonMap();
        $this->map = &$map;
    }
    /**
     * @return DungeonMap|null
     */
    public function getMap() {
        return $this->map;
    }

    public function getBuildLog() {
        return $this->buildLog;
    }

    public function clearBuildLog() {
        $this->buildLog = [];
    }

    public function log($log) {
        if (is_string($log)) {
            $this->buildLog[] = $log;
        } elseif (is_array($log)) {
            $this->buildLog = array_merge($this->buildLog, $log);
        }
    }

    public function getRoom($x, $y) {
        return $this->getMap()->getRoom(new Coordinate($x, $y));
    }
}

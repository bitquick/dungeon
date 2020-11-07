<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/6/2020
 * Time: 10:13 PM
 */

namespace App\Dungeon\Generator;


use App\Dungeon\Dungeon;

class DungeonBuildStep
{
    protected $log = [];

    protected $dungeon;

    public function setLog(array $log) {
        $this->log = $log;
    }

    public function getLog() {
        return $this->log;
    }

    public function setDungeon(Dungeon $dungeon) {
        $this->dungeon = $dungeon;
    }

    public function getDungeon() {
        return $this->dungeon;
    }
}

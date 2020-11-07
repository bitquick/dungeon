<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/4/2020
 * Time: 5:50 PM
 */

namespace App\Dungeon\Components;


use App\Dungeon\DungeonMap;

trait DungeonMapReferenceTrait
{
    /** A reference to a dungeon map.
     * @var DungeonMap
     */
    protected $map = null;

    /**
     * Set the dungeon map reference.
     * @param DungeonMap|null $map
     */
    public function setMap(?DungeonMap &$map) {
        $this->map = $map;
    }

    /**
     * Get the dungeon map reference.
     * @return DungeonMap
     */
    public function getMap() {
        return $this->map;
    }

}

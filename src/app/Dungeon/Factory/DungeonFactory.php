<?php

namespace App\Dungeon\Factory;

/**
 * Class DungeonFactory
 *
 * Generate one or more dungeons number of dungeons
 *
 * @package App\Dungeon
 */
class DungeonFactory
{
    private const UP_OR_DOWN = [-1, 1];
    public const BASE_ROOMS = 12;
    public const ROOM_LEVEL_INCREASE = 5;
    public const ROOM_DEVIATION_PERCENT = 10;

    /**
     * @param int $level
     * @return float|int
     */
    private function calculateNumberOfRooms(int $level = 1) {
        $rooms = self::BASE_ROOMS;
        for ($i=0; $i < $level; $i++) {
            $rooms += self::ROOM_LEVEL_INCREASE + $this->deviationOfRooms();
        }
        return ceil($rooms);
    }

    private function deviationOfRooms() {
        $roomDeviation = self::ROOM_LEVEL_INCREASE * (self::ROOM_DEVIATION_PERCENT / 100);
        $roomDeviation = $roomDeviation * array_rand(self::UP_OR_DOWN);
        return $roomDeviation;
    }
}

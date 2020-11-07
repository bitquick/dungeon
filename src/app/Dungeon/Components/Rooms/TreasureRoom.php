<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/1/2020
 * Time: 4:38 PM
 */

namespace App\Dungeon\Components\Rooms;


class TreasureRoom extends TerminalRoom
{

    public function canConnectTo(Room $room)
    {
        return $room->getRoomType() === BossRoom::class
            && parent::canConnectTo($room);
    }
}

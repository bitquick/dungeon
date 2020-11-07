<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 11/1/2020
 * Time: 4:35 PM
 */

namespace App\Dungeon\Components\Rooms;


class BossRoom extends BridgeRoom
{
    public function canConnectTo(Room $room)
    {
        if (count($this->doors) === 1) {
            if ($room->getRoomType() === TreasureRoom::class) {
                return parent::canConnectTo($room);
            }
            return false;
        }
        return parent::canConnectTo($room);
    }
}

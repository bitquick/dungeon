<div class="tile {{$class}}">
    @if(empty($room))
        <div class='void'></div>
    @else
        <div class="
            room
            {{ $room->getPrettyRoomName() }}
            {{ $room->hasTopDoor() ? 'topDoor' : '' }}
            {{ $room->hasRightDoor() ? 'rightDoor' : '' }}
            {{ $room->hasBottomDoor() ? 'bottomDoor' : '' }}
            {{ $room->hasLeftDoor() ? 'leftDoor' : '' }}
        ">
            <span class='coordinate'>{{ $room->getCoordinate() }}</span>
            @if($room->hasTopDoor())
                <span class='door topDoor'></span>
            @endif
            @if($room->hasRightDoor())
                <span class='door rightDoor'></span>
            @endif
            @if($room->hasBottomDoor())
                <span class='door bottomDoor'></span>
            @endif
            @if($room->hasLeftDoor())
                <span class='door leftDoor'></span>
            @endif
            @if($room->startingPoint())
                <span class='door bottomDoor entranceDoor'></span>
            @endif
        </div>
    @endif
</div>

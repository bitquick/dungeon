@extends('main')

@section('content')
    <h1>Generate Dungeon</h1>
    <h3>{{ $roomCount }} Rooms</h3>

    <div>
        <div class="map">
            @for ($i = 0; $i <= $yRange+2; $i++)
                <div class="row">
                    @for ($k = 0; $k <= $xRange+2; $k++)
                        @include('room', [
                            'room' => $dungeon->getRoom($xMin-1+$k, $yMax+1-$i),
                            'class' => $dungeon->getRoom($xMin-1+$k, $yMax+1-$i) ? 'filled' : 'void'
                        ])
                    @endfor
                </div>
            @endfor
        </div>
    </div>

    <div>
        <span>Generated in {{ $duration }} ms</span>
    </div>

    <div>
        <h3>Legend</h3>
        <table>
            <tr>
                <td>Red Square</td>
                <td>Boss Room</td>
            </tr>
            <tr>
                <td>Yellow Square</td>
                <td>Treasure Room</td>
            </tr>
            <tr>
                <td>Yellow Dot</td>
                <td>Starting Point</td>
            </tr>

        </table>
    </div>

    <h3>Dungeon Room Coordinates</h3>
    @php(dump($coordinates))

    <h3>Dungeon Room List</h3>
    @php(dump($roomList))

    <h3>Dungeon</h3>
    @php(dump($dungeon))
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Dungeon</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            .void {
                height: 100%;
                width: 100%;
                background-color: #000;
                color: #AAA;
                border: 2px solid black;
                box-sizing: border-box;
            }

            .room {
                height: 100%;
                width: 100%;
                background-color: blue;
                display: inline-block;
                position: relative;
                box-sizing: border-box;
                border: 4px solid black;

            }

            .map {
                overflow:hidden;
                white-space: nowrap;
                background-color: black;
                display: inline-block;
            }

            .map .row {

            }

            .map .tile {
                display: inline-block;
                height: 100px;
                width: 130px;
                text-align: center;
                padding:0;
                margin: 0;
            }

            span.door {
                display: block;
                width: 10px;
                height: 10px;
                position: absolute;
                background-color: blue;
            }
            span.topDoor {
                top:-8px;
                right: 60px;
            }

            span.bottomDoor {
                bottom: -8px;
                right: 60px;
            }

            span.leftDoor {
                top: 45px;
                left: -8px;
            }

            span.rightDoor {
                top: 45px;
                right:-8px;
            }

            span.entranceDoor {
                background-color: yellow;
            }

            .coordinate {
                position: absolute; top: 43px; right: 50px;
            }

            .BossRoom {
                background-color: red;
                color: white;
            }

            .TreasureRoom {
                background-color: yellow;
                color: black;
            }

        </style>
    </head>
    <body class="antialiased">
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>

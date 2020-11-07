<?php

namespace App\Http\Controllers;

use App\Dungeon\Dungeon;
use App\Dungeon\Generator\DungeonGenerator;

class DungeonController extends Controller
{
    /**
     * Endpoint for the dungeon generator
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function generate(\Illuminate\Http\Request $request) {
        // The number of rooms you would like the dungeon to have
        $rooms = $request->get('rooms') ?? 1;
        // Display the dungeon after each build step
        $showSteps = $request->get('showSteps') == 1 ? true : false;
        // Display all logs
        $showLogs = $request->get('showLogs') == 1 ? true : false;
        // Placement Algorithm
        $algorithm = $request->get('algorithm') ?? 1;

        return $this->generateDungeon($rooms, $showSteps, $showLogs, $algorithm);
    }

    private function generateDungeon($rooms, $showSteps, $showLogs, $algorithm) {
        $generator = new DungeonGenerator();
        $generator->setRoomPlacementAlgorithm($algorithm);
        if ($showSteps) {
            $generator->recordBuildSteps();
            //$this->setDungeonStepCallback($generator, $showLogs);
        }

        // Generate the dungeon
        $dungeon = $generator->generateDungeon($rooms);
        $buildSteps = $generator->getBuildSteps();
        $duration = $generator->getDuration()->asMilliseconds();

        [$xMin, $xMax, $yMin, $yMax] = $dungeon->getMap()->getMapRange();
        $xRange = $xMax - $xMin;
        $yRange = $yMax - $yMin;

        //$this->displayDungeon($dungeon, $rooms);
        //$this->displayStats($dungeon, $generator);
        return view('dungeon', [
            'dungeon' => $dungeon,
            'roomCount' => $rooms,
            'buildSteps' => $buildSteps,
            'duration' => $duration,
            'xMin' => $xMin,
            'xMax' => $xMax,
            'yMin' => $yMin,
            'yMax' => $yMax,
            'xRange' => $xRange,
            'yRange' => $yRange,
            'coordinates' => $dungeon->getMap()->getAllRoomCoordinates(),
            'roomList' => $dungeon->getMap()->getRoomList(),
        ]);
    }

    private function setDungeonStepCallback(DungeonGenerator &$generator, bool $showLogs) {
        $generator->setStepCallback(function(Dungeon $dungeon) use ($showLogs) {
            static $step = 1;
            echo "<h3>Step {$step}</h3>";
            // Show logs if set
            if ($showLogs) {
                foreach($dungeon->getBuildLog() as $log) {
                    echo $log . "<br />\n";
                }
                $dungeon->clearBuildLog();
            }
            echo $dungeon->getMap()->render();
            $step++;
        });
    }
}

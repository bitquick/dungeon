<?php

use App\Dungeon\Generator\Algorithms\DungeonGenerator;
use App\Http\Controllers\DungeonController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\Timer\Timer;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dungeon', [DungeonController::class, 'generate']);

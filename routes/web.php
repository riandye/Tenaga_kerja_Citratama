<?php

use App\Http\Controllers\chatbotController;
use App\Http\Controllers\JadwalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/botman', [chatbotController::class, 'handle']);

Route::get('/sse', function () {
    return response()->stream(function () {
        while (true) {
            echo "data: " . json_encode(['time' => now()->toDateTimeString()]) . "\n\n";
            ob_flush();
            flush();
            sleep(1);
        }
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
    ]);
});
<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PelamarKerjaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\notifikasiController;
use App\Http\Controllers\PenerimaJadwal;
use App\Http\Controllers\PenerimaJadwalController;
use App\Http\Controllers\PerusahaanMitraController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\chatbotController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserProfileController;
use App\Models\PerusahaanMitra;
use Laravel\Sanctum\Sanctum;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);

    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/datadiri', [PelamarKerjaController::class, 'datadiri']);
    Route::get('/show-profile', [PelamarKerjaController::class, 'showprofile']);
    Route::post('/update-profile/{id}', [PelamarKerjaController::class, 'update']);
    Route::get('/jadwalUser', [JadwalController::class, 'jadwaluser']);
    Route::get('/notifUser', [PelamarKerjaController::class, 'showNotifUser']);
});

    Route::post('/tambahadmin', [AdminController::class, 'tambahadmin']);

// Route::middleware('auth:sanctum')->group(function(){
    Route::post('/admin/tambah-pelamar', [AdminController::class, 'tambahUser'])->middleware('api');
    Route::post('/admin/update-pelamar/{id}', [AdminController::class, 'updateUser']);
    Route::get('/admin/pelamar', [AdminController::class, 'showUser']);
    Route::delete('/admin/pelamar/{id}', [AdminController::class, 'deleteUser']);

    Route::post('/admin/tambah-mitra', [AdminController::class, 'tambahMitra']);
    Route::post('/admin/update-mitra/{id}', [AdminController::class, 'updateMitra']);
    Route::get('/admin/mitra', [AdminController::class, 'showMitra']);
    Route::delete('/admin/mitra/{id}', [AdminController::class, 'deleteMitra']);

    Route::post('/admin/tambah-berita', [AdminController::class, 'tambahBerita']);
    Route::post('/admin/update-berita/{id}', [AdminController::class, 'updateBerita']);
    Route::get('/admin/berita', [AdminController::class, 'showBerita']);
    Route::delete('/admin/berita/{id}', [AdminController::class, 'deleteBerita']);

    Route::get('/admin/recruitment', [AdminController::class, 'showrecruitment']);
    Route::post('/admin/approve/{recruitment}', [AdminController::class, 'approveRecruitment']);
    Route::post('/admin/approve/penerimaan/{recruitment}', [AdminController::class, 'approvePenerimaan']);
    Route::post('/admin/sendWA/{jadwal}', [AdminController::class, 'sendWhatsapp']);
    Route::get('/notifAdmin', [AdminController::class, 'showNotifAdmin']);
// });

// Route::middleware('auth:api')->group(function () {
    Route::get('/userTersedia', [PerusahaanMitraController::class, 'getUsersByStatus']);
    Route::get('/notifMitra', [PerusahaanMitraController::class, 'showNotifMitra']);
    
// });

Route::get('/notif', [RecruitmentController::class, 'showNotif']);
Route::get('/userStatus', [RecruitmentController::class, 'index']);
Route::middleware('auth:sanctum')->group(function(){
    
    Route::get('/recruitments', [RecruitmentController::class, 'showRecruitment']);
    Route::post('/recruit', [RecruitmentController::class, 'recruit']);
    Route::post('/recruit/confirm/{id}', [RecruitmentController::class, 'confirmRecruitment']);
});

Route::middleware('auth:sanctum')->post('/jadwal/add', [JadwalController::class, 'store']);
Route::get('/jadwal',[JadwalController::class, 'index']);
Route::get('/jadwal/{id}',[JadwalController::class, 'show']);
Route::post('/jadwal/update/{id}',[JadwalController::class, 'update']);
Route::delete('/jadwal/delete/{id}',[JadwalController::class, 'destroy']);
Route::get('admin/jadwal', [JadwalController::class, 'showJadwal']);

Route::get('/faq',[FAQController::class, 'index']);
Route::post('/faq/add',[FAQController::class, 'store']);
Route::post('/faq/update/{id}',[FAQController::class, 'update']);
Route::delete('/faq/delete/{id}',[FAQController::class, 'destroy']);


Route::post('/notification/read/{id}', [NotificationController::class, 'markAsRead']);

Route::match(['get', 'post'], '/botman', [chatbotController::class, 'handle']);
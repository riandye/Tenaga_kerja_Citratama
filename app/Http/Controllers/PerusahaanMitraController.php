<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanMitra;
use App\Models\recruitment;
use Illuminate\Http\Request;
use App\Models\User;

class PerusahaanMitraController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/userTersedia",
 *     tags={"PerusahaanMitra"},
 *     summary="Get users available for recruitment",
 *     description="Mengambil daftar pengguna yang tersedia untuk direkrut oleh perusahaan mitra.",
 *     operationId="getUsersByStatus",
 *     @OA\Response(
 *         response="default",
 *         description="return array model user"
 *     )
 *   )
 * )
 */
    public function getUsersByStatus()
    {
        $availableUsers = User::whereDoesntHave('recruitments', function ($query) {
            $query->whereIn('status', ['menunggu', 'tidak tersedia']);
        })->get();
        
        return response()->json($availableUsers, 200);
    }

    public function showNotifMitra()
    {
        $Mitra = Auth::user();
        $notifications = notifications::where('notifiable_type', 'App\Models\PerusahaanMitra')
                                      ->where('notifiable_id', $Mitra->ID_mitra)
                                      ->get();
        
    
        return response()->json($notifications);
    }
}

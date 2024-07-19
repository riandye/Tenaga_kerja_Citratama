<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\notifications;
use App\Models\PerusahaanMitra;
use App\Models\recruitment;
use App\Models\User;
use App\Notifications\penerimaanNotification;
use App\Notifications\RecruitmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class RecruitmentController extends Controller
{
/**
 * @OA\Post(
 *     path="/api/recruit",
 *     tags={"Recruitment"},
 *     summary="Recruit a user",
 *     description="Perusahaan mitra melakukan recruitment terhadap user.",
 *     operationId="recruit",
 *     security={{"bearer": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          description="ID user yang direcruit",
 *          @OA\JsonContent(
 *              required={"ID_user"},
 *              @OA\Property(property="ID_user", type="integer", example=1),
 *          ),
 *      ),
 *      @OA\Response(
 *         response="default",
 *         description="return array model user"
 *     )
 *   )
 * )
 */
    public function recruit(Request $request)
    {
        $ID_user = $request->input('ID_user');
        $PerusahaanMitra = Auth::user();

        if (!$PerusahaanMitra) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::find($ID_user);
    
        if ($user) {
            $recruitment = new Recruitment([
                'ID_user' => $ID_user,
                'ID_mitra' => $PerusahaanMitra->ID_mitra,
                'tgl_recruitment' => now(),
                'status' => 'menunggu',
                'info' => 'menunggu'
            ]);

            $recruitment->save();
            $admins = Admin::all();
            Notification::send($admins, new RecruitmentNotification($recruitment));
           
            return response()->json(['message' => 'User successfully recruited and notification sent to admin.'], 200);
        }
    }

    /**
 * @OA\Post(
 *     path="/api/recruit/confirm/{id}",
 *     tags={"Recruitment"},
 *     summary="Confirm recruitment",
 *     description="Perusahaan mitra mengonfirmasi status recruitment user.",
 *     operationId="confirmRecruitment",
 *     security={{"bearer": {}}},
 *     @OA\Parameter(
 *         name="ID_recruitment",
 *         in="path",
 *         required=true,
 *         description="ID recruitment yang akan dikonfirmasi",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *          required=true,
 *          description="Status recruitment baru",
 *          @OA\JsonContent(
 *              required={"status"},
 *              @OA\Property(property="status", type="string", enum={"tersedia", "menunggu", "tidak tersedia"}),
 *          ),
 *      ),
 *     @OA\Response(
 *         response="default",
 *         description="return array model recruitment"
 *     )
 *   )
 * )
 */
    public function confirmRecruitment(Request $request, $ID_recruitment)
    {
        $recruitment = Recruitment::find($ID_recruitment);
        $perusahaanMitra = Auth::user();

        if (!$perusahaanMitra) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!$recruitment) {
            return response()->json(['message' => 'Recruitment not found'], 404);
        }

        if ($recruitment->ID_mitra != $perusahaanMitra->ID_mitra) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        $status = $request->input('status');

        if ($status === 'centang') {
            $recruitment->status = 'menunggu';
            $recruitment->info = 'diterima';
            $recruitment->info_penerimaan = 'menunggu';
        } elseif ($status === 'silang') {
            $recruitment->status = 'tersedia';
            $recruitment->info = 'diterima';
            $recruitment->info_penerimaan = 'ditolak';
        } else {
            return response()->json(['message' => 'Invalid status input'], 400);
        }

        $recruitment->save();
        $admins = Admin::all();
        Notification::send($admins, new penerimaanNotification($recruitment));

        return response()->json(['message' => 'Recruitment status updated'], 200);
    }


/**
 * @OA\Get(
 *     path="/api/recruitments",
 *     tags={"Recruitment"},
 *     summary="Show recruited users by logged in company partner",
 *     description="Perusahaan mitra melihat daftar user yang direkrut olehnya.",
 *     operationId="showRecruitment",
 *     security={{"bearer": {}}},
 *     @OA\Response(
 *         response="default",
 *         description="return array model recruitment"
 *     )
 *   )
 * )
 */
    public function showRecruitment()
    {
        $perusahaanMitra = Auth::user();
        if (!$perusahaanMitra) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        // $recruitments = recruitment::with('perusahaanMitra', 'user')
        // ->orderBy('created_at', 'desc')
        // ->get();

        $recruitments = recruitment::with('perusahaanmitra','user')
            ->orderBy('created_at', 'desc')
            ->where('ID_mitra', $perusahaanMitra->ID_mitra)
            ->get();

        $recruitedUsers = $recruitments->map(function ($recruitment) {
            return [
                'recruitment' => $recruitment,
                'user'=> $recruitment->user,
            ];
        });

        return response()->json($recruitedUsers, 200);
    }

/**
 * @OA\Get(
 *     path="/api/userStatus",
 *     tags={"Recruitment"},
 *     summary="Get all users with latest recruitment status",
 *     description="Mengambil semua pengguna beserta status rekrutmen terbaru mereka.",
 *     operationId="userStatus",
 *    @OA\Response(
 *         response="default",
 *         description="return array model recruitment"
 *     )
 *   )
 * )
 */
    public function index()
    {
        $users = User::with(['recruitments' => function ($query) {
            $query->orderBy('created_at', 'desc')->with('perusahaanmitra', 'user');
        }])->get();
    
        return response()->json(['users' => $users]);
    }

    public function showNotif()
    {
        $notifications = notifications::all();

        return response()->json($notifications);
    }
}
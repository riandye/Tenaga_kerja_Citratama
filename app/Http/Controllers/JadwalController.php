<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\jadwal;
use App\Models\PenerimaJadwal;
use App\Models\PerusahaanMitra;
use App\Notifications\JadwalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use function React\Promise\all;

class JadwalController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/jadwal",
     *     tags={"Jadwal"},
     *     summary="Get List jadwal",
     *     description="data jadwal",
     *     operationId="indexJadwal",
     *     @OA\Response(
     *         response="default",
     *         description="return array model jadwal"
     *     )
     * )
     */
    public function index() //perusahaan mitra
    {
        $jadwals = jadwal::with('penerimaJadwal.user', 'mitra')->get();

        return response()->json($jadwals);
    }

    public function showJadwal() //admin
    {
        $jadwal = jadwal::with('penerimaJadwal.user')->get();
        return response()->json($jadwal);
    }

    public function jadwaluser() //user
    {
        $user = Auth::user();
        $jadwal = jadwal::with('penerimaJadwal.user')
                    ->whereHas('penerimaJadwal.user', function ($query) use ($user) {
                        $query->where('ID_user', $user->ID_user);
                    })
                    ->get();

        return response()->json($jadwal);
    }
    /**
 * @OA\Post(
 *     path="/api/jadwal/add",
 *     tags={"Jadwal"},
 *     summary="Tambah data jadwal",
 *     description="Endpoint ini digunakan untuk menambah data jadwal.",
 *     operationId="tambahJadwal;",
 *     security={{"bearer": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          description="Form tambah jadwal",
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  required={"tanggal", "tempat", "user_ids"},
 *                  @OA\Property(property="tanggal", type="string"),
 *                  @OA\Property(property="tempat", type="string"),
 *                  @OA\Property(property="user_ids", type="array", @OA\Items(type="integer"))
 *              )
 *          )
 *      ),
 *     @OA\Response(
 *         response=200,
 *         description="Jadwal berhasil ditambahkan",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Jadwal berhasil ditambahkan"),
 *             @OA\Property(
 *                 property="jadwal",
 *                 type="object",
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid input data")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     )
 * )
 */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date', 
            'tempat' => 'required|string|max:255',
            'jam' => 'required|string|regex:/^\d{2}:\d{2} - \d{2}:\d{2}$/',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,ID_user'
        ]);

        $perusahaanMitra = Auth::user();

        if (!$perusahaanMitra) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $jadwal = Jadwal::create([
            'ID_mitra' => $perusahaanMitra->ID_mitra,
            'tanggal' => $validatedData['tanggal'],
            'tempat' => $validatedData['tempat'],
            'jam' => $validatedData['jam']
        ]);

        foreach ($validatedData['user_ids'] as $ID_user) {
            PenerimaJadwal::create([
                'ID_jadwal' => $jadwal->ID_jadwal,
                'ID_user' => $ID_user
            ]);
            $admin = Admin::all();
            Notification::send($admin, new JadwalNotification($jadwal));
        }
        return response()->json(['message' => 'Jadwal sudah berhasil dibuat dan sudah memberikan notifikasi ke admin', 'jadwal' => $jadwal], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/jadwal/{id}",
     *     tags={"Jadwal"},
     *     summary="Get List jadwal",
     *     description="data jadwal",
     *     operationId="showBerita",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID jadwal yang ingin diperbarui",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="return array model jadwal"
     *     )
     * )
     */
    public function show($id)
    {
        $jadwal = jadwal::find($id);
        return response()->json($jadwal);
    }

    /**
 * @OA\Post(
 *     path="/api/jadwal/update/{id}",
 *     tags={"Jadwal"},
 *     summary="Update data jadwal",
 *     description="Update data jadwal berdasarkan ID",
 *     operationId="updateBerita",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID jadwal yang ingin diperbarui",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Data yang akan diperbarui",
 *         @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  @OA\Property(property="tanggal", type="string"),
 *                  @OA\Property(property="tempat", type="string"),
 *                  @OA\Property(property="user_ids", type="string", format="array")
 *         )
 *      )
 *     ),
 *       @OA\Response(
 *         response="default",
 *         description="return array model jadwal"
 *     )
 *   )
 * )
 */
    public function update(Request $request, $id)
    {
        $request->validate([
            'ID_mitra' => 'required|exists:perusahaan_mitra,ID_mitra',
            'tanggal' => 'required|date',
            'tempat' => 'required|string|max:255',
        ]);

        $jadwal = jadwal::find($id);
        $jadwal->tanggal = $request->input('tanggal');
        $jadwal->tempat = $request->input('tempat');
        $jadwal->save();

        return response()->json('Jadwal berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jadwal = jadwal::find($id);
        $jadwal->delete();

        return response()->json('Jadwal berhasil dihapus');
    }
}

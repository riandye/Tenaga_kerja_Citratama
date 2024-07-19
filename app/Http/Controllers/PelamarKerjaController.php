<?php

namespace App\Http\Controllers;

use App\Models\berita;
use App\Models\notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;



class PelamarKerjaController extends Controller
{
/**
 * @OA\Post(
 *     path="/api/datadiri",
 *     tags={"Pelamar kerja"},
 *     summary="Tambah data pelamar kerja",
 *     description="Endpoint ini digunakan untuk menambah data pelamar kerja.",
 *     operationId="datadiri",
 *     security={{"bearer": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          description="Form tambah pelamar kerja",
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  required={"name", "alamat", "tempat_tanggal_lahir", "no_hp", "klasifikasi_pilihan", "sub_klasifikasi_pilihan"},
 *                  @OA\Property(property="name", type="string", description="Nama pelamar kerja"),
 *                  @OA\Property(property="alamat", type="string", description="Alamat pelamar kerja"),
 *                  @OA\Property(property="tempat_lahir", type="string", description="Tempat  lahir pelamar kerja"),
 *                  @OA\Property(property="tanggal_lahir", type="string", description="tanggal  lahir pelamar kerja"),
 *                  @OA\Property(property="lokasi", type="string", description="lokasi"),
 *                  @OA\Property(property="no_hp", type="string", description="Nomor HP pelamar kerja"),
 *                  @OA\Property(property="klasifikasi_pilihan", type="string", description="Klasifikasi pilihan pelamar kerja"),
 *                  @OA\Property(property="sub_klasifikasi_pilihan", type="string", description="Sub-klasifikasi pilihan pelamar kerja"),
 *                  @OA\Property(property="photo", type="string", format="binary", description="URL foto pelamar kerja"),
 *              )
 *          )
 *      ),
 *     @OA\Response(
 *         response="default",
 *         description="return array model user"
 *     )
 *   )
 * )
 */
    public function datadiri(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string|max:255',
            'klasifikasi_pilihan' => 'required|string',
            'sub_klasifikasi_pilihan' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $user = Auth::user();
        
        // $tempatTanggalLahir = explode(',', $request->input('tempat_tanggal_lahir'));
        // if (count($tempatTanggalLahir) != 2) {
        //     return response()->json(['error' => 'Format tempat dan tanggal lahir tidak valid.'], 400);
        // }

        // $tempatLahir = trim($tempatTanggalLahir[0]);
        // $tanggalLahir = trim($tempatTanggalLahir[1]);


        // if (!preg_match('/\d{4}-\d{2}-\d{2}/', $tanggalLahir)) {
        //     return response()->json(['error' => 'Format tanggal lahir harus YYYY-MM-DD.'], 400);
        // }

        $photoPath = $user->info['photo'] ?? null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

            $info = [
                'name' => $request->name,
                'alamat' => $request->alamat,
                'lokasi' => $request->lokasi,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_HP' => $request->no_hp,
                'status' => 'pelamar_kerja',
                'photo' => $photoPath,
                'klasifikasi_pilihan' => $request->klasifikasi_pilihan,
                'sub_klasifikasi_pilihan' => $request->sub_klasifikasi_pilihan,   
            ];
        /** @var \App\Models\User $user */
        $user->info = $info;
        $user->save(); 
        
        return response()->json(['message' => 'Data berhasil ditambahkan', 'user' => $user], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/show-profile",
     *     tags={"Pelamar kerja"},
     *     summary="Get List pelamar kerja",
     *     description="data pelamar kerja",
     *     operationId="showprofile",
     *     security={{"bearer": {}}},
     *     @OA\Response(
     *         response="default",
     *         description="return array model user"
     *     )
     * )
     */
    public function showprofile()
    {
        $user = Auth::user();
        return response()->json([
            'user' => $user
        ]);
    }

    /**
 * @OA\Post(
 *     path="/api/update-profile/{id}",
 *     tags={"Pelamar kerja"},
 *     summary="Update data user",
 *     description="Update data user berdasarkan ID",
 *     operationId="updateProfile",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID user yang ingin diperbarui",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Data yang akan diperbarui",
 *         @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  @OA\Property(property="email", type="string", format="email"),
 *                  @OA\Property(property="nama", type="string"),
 *                  @OA\Property(property="alamat", type="string"),
 *                  @OA\Property(property="tempat_lahir", type="string"),
 *                  @OA\Property(property="tanggal_lahir", type="string", format="date"),
 *                  @OA\Property(property="no_hp", type="string"),
 *                  @OA\Property(property="klasifikasi_pilihan", type="string"),
 *                  @OA\Property(property="sub_klasifikasi_pilihan", type="string"),
 *                  @OA\Property(property="photo", type="string", format="binary", description="URL foto pelamar kerja"),
 *                  @OA\Property(property="ringkasan_pribadi", type="string"),
 *                  @OA\Property(property="pendidikan", type="string"),
 *                  @OA\Property(property="riwayat_karir", type="string"),
 *                  @OA\Property(property="sertifikat", type="string"),
 *                  @OA\Property(property="keahlian", type="string"),
 *                  @OA\Property(property="bahasa", type="string"),
 *         )
 *      )
 *     ),
 *       @OA\Response(
 *         response="default",
 *         description="return array model user"
 *     )
 *   )
 * )
 */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'user tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|string|email|max:255|unique:users,' . $user->id,
            'nama' => 'sometimes|string|max:255',
            'alamat' => 'sometimes|string',
            'tempat_lahir' => 'sometimes|string',
            'tanggal_lahir' => 'sometimes|date',
            'no_hp' => 'sometimes|string|max:15',
            'klasifikasi_pilihan' => 'sometimes|string',
            'sub_klasifikasi_pilihan' => 'sometimes|string',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ringkasan_pribadi' => 'sometimes|string',
            'pendidikan' => 'sometimes|string',
            'riwayat_karir' => 'sometimes|string',
            'sertifikat' => 'sometimes|string',
            'keahlian' => 'sometimes|string',
            'bahasa' => 'sometimes|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        if ($request->has('email')) {
            $user->email = $request->email;
        }
    
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

       $info = $user->info;
       $info = array_merge($info, $request->except(['email', 'password']));
       $user->info = $info;

       $user->save();

        return response()->json(['message' => 'Data user berhasil diperbarui', 'user' => $user]);
    }

    public function showBerita()
    {
        $berita = berita::all();
        return response()->json($berita);
    }

    public function showNotifUser()
    {
        $user = Auth::user();
        $notifications = notifications::where('notifiable_type', 'App\Models\User')
                                      ->where('notifiable_id', $user->ID_user)
                                      ->get();
        
    
        return response()->json($notifications);
    }
    
}


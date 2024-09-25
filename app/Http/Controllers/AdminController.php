<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\berita;
use App\Models\jadwal;
use App\Models\notifications;
use App\Models\PenerimaJadwal;
use App\Models\PerusahaanMitra;
use App\Models\recruitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Notifications\approveAdmin;
use App\Notifications\JadwalNotification;
use App\Notifications\penerimaanAdmin;
use App\Notifications\userJadwalNotification;
use App\Notifications\userNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Model;


class AdminController extends Controller
{
    public function tambahadmin(Request $request )
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|max:100',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $admin = Admin::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        $admin->save(); 
        
        return response()->json(['message' => 'Data berhasil ditambahkan', 'admin' => $admin], 201);
    }
    
    /**
     * @OA\Get(
     *     path="/api/admin/pelamar",
     *     tags={"Admin-pelamar"},
     *     summary="Get List pelamar kerja",
     *     description="data pelamar kerja",
     *     operationId="admin/showUser",
     *     @OA\Response(
     *         response="default",
     *         description="return array model user"
     *     )
     * )
     */
    public function showUser()
    {
        $user = user::get()->all();
        return response()->json($user);
    }

/**
 * @OA\Post(
 *     path="/api/admin/tambah-pelamar",
 *     tags={"Admin-pelamar"},
 *     summary="Tambah data pelamar kerja",
 *     description="Endpoint ini digunakan untuk menambah data pelamar kerja.",
 *     operationId="admin/tambahUser",
 *     @OA\RequestBody(
 *          required=true,
 *          description="Form tambah pelamar kerja",
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  required={"email", "password", "role", "name"},
 *                  @OA\Property(property="email", type="string", description="Email dari pelamar kerja"),
 *                  @OA\Property(property="password", type="string", description="Password untuk akun pelamar"),
 *                  @OA\Property(property="role", type="string", description="Role dari pengguna"),
 *                  @OA\Property(property="name", type="string", description="Nama pelamar kerja"),
 *                  @OA\Property(property="alamat", type="string", nullable=true),
 *                  @OA\Property(property="tempat_tanggal_lahir", type="string", description="Tempat dan tanggal lahir pelamar kerja, format: Tempat, YYYY-MM-DD", nullable=true),
 *                  @OA\Property(property="no_hp", type="string", description="Nomor HP pelamar kerja", nullable=true),
 *                  @OA\Property(property="klasifikasi_pilihan", type="string", description="Klasifikasi pilihan pelamar kerja", nullable=true),
 *                  @OA\Property(property="sub_klasifikasi_pilihan", type="string", description="Sub-klasifikasi pilihan pelamar kerja", nullable=true),
 *                  @OA\Property(property="photo", type="string", format="binary", description="URL foto pelamar kerja", nullable=true),
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
    public function tambahUser(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'date',
            'no_hp' => 'nullable|string|max:255',
            'klasifikasi_pilihan' => 'nullable|string',
            'sub_klasifikasi_pilihan' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        /** @var \App\Models\User $user */
        $photoPath = $user->info['photo'] ?? null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'info' => [
                'name' => $request->name,
                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_HP' => $request->no_hp,
                'photo' => $photoPath,
                'klasifikasi_pilihan' => $request->klasifikasi_pilihan,
                'sub_klasifikasi_pilihan' => $request->sub_klasifikasi_pilihan,   
            ]
        ]);
        $user->save(); 
        
        return response()->json(['message' => 'Data berhasil ditambahkan', 'user' => $user], 201);
    }

/**
 * @OA\Post(
 *     path="/api/admin/update-pelamar/{id}",
 *     tags={"Admin-pelamar"},
 *     summary="Update data user",
 *     description="Update data user berdasarkan ID",
 *     operationId="admin/updateUser",
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
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'user tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|string|email|max:255' . $user->ID_user,
            'name' => 'sometimes|string|max:255',
            'alamat' => 'sometimes|string',
            'tempat_lahir' => 'sometimes|string',
            'tanggal_lahir' => 'sometimes|date',
            'no_hp' => 'sometimes|string|max:15',
            'klasifikasi_pilihan' => 'sometimes|string',
            'sub_klasifikasi_pilihan' => 'sometimes|string',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

/**
 * @OA\Delete(
 *     path="/api/admin/pelamar/{id}",
 *     tags={"Admin-pelamar"},
 *     summary="Hapus data user",
 *     description="Hapus data user berdasarkan ID",
 *     operationId="admin/deleteUser",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID user yang ingin dihapus",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *  @OA\Response(
 *         response="default",
 *         description="return array model user"
 *     )
 *   )
 * )
 */
    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'user tidak ditemukan'], 404);
        }
        $user->delete();

        return response()->json(['message' => 'Data user berhasil dihapus']);
    }

    /**
 * @OA\Post(
 *     path="/api/admin/tambah-mitra",
 *     tags={"Admin-mitra"},
 *     summary="Tambah data perusahaan mitra",
 *     description="Endpoint ini digunakan untuk menambah data perusahaan mitra.",
 *     operationId="admin/tambahMitra",
 *     @OA\RequestBody(
 *          required=true,
 *          description="Form tambah perusahaan mitra",
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  required={"email", "password", "role", "name"},
 *                  @OA\Property(property="email", type="string"),
 *                  @OA\Property(property="password", type="string"),
 *                  @OA\Property(property="role", type="string"),
 *                  @OA\Property(property="name", type="string"),
 *                  @OA\Property(property="alamat", type="string"),
 *                  @OA\Property(property="no_hp", type="string", description="Nomor HP pelamar kerja"),
 *                  @OA\Property(property="bidang_usaha", type="string", description="Bidang usaha perusahaan mitra"),
 *              )
 *          )
 *      ),
 *     @OA\Response(
 *         response="default",
 *         description="return array model PerusahaanMitra"
 *     )
 *   )
 * )
 */
    public function tambahMitra(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:255',
            'bidang_usaha' => 'required|string|max:255'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $mitra = PerusahaanMitra::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'info' => [
                'name' => $request->name,
                'alamat' => $request->alamat,
                'no_HP' => $request->no_hp,
                'bidang_usaha' => $request->bidang_usaha
            ]
        ]);
        $mitra->save(); 
        
        return response()->json(['message' => 'Data berhasil ditambahkan', 'mitra' => $mitra], 201);
    }

    /**
 * @OA\Post(
 *     path="/api/admin/update-mitra/{id}",
 *     tags={"Admin-mitra"},
 *     summary="Update data perusahaan mitra",
 *     description="Update data berdasarkan ID",
 *     operationId="admin/updateMitra",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID perusahaan mitra yang ingin diperbarui",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Data yang akan diperbarui",
 *         @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  @OA\Property(property="email", type="string"),
 *                  @OA\Property(property="password", type="string"),
 *                  @OA\Property(property="role", type="string"),
 *                  @OA\Property(property="name", type="string"),
 *                  @OA\Property(property="alamat", type="string"),
 *                  @OA\Property(property="no_hp", type="string", description="Nomor HP pelamar kerja"),
 *                  @OA\Property(property="bidang_usaha", type="string", description="Bidang usaha perusahaan mitra"),
 *         )
 *      )
 *     ),
 *       @OA\Response(
 *         response="default",
 *         description="return array model PerusahaanMitra"
 *     )
 *   )
 * )
 */
    public function updateMitra(Request $request, $id)
    {
        $mitra = PerusahaanMitra::find($id);
        if (!$mitra) {
            return response()->json(['message' => 'mitra tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|string|email|max:255|unique:users' . $mitra->id,
            'name' => 'sometimes|string|max:255',
            'alamat' => 'sometimes|string|max:255',
            'no_hp' => 'sometimes|string|max:15',
            'bidang_usaha' => 'sometimes|string|max:255'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        if ($request->has('email')) {
            $mitra->email = $request->email;
        }
    
        if ($request->has('password')) {
            $mitra->password = Hash::make($request->password);
        }

       $info = $mitra->info;
       $info = array_merge($info, $request->except(['email', 'password']));
       $mitra->info = $info;
       $mitra->save();

        return response()->json(['message' => 'Data mitra berhasil diperbarui', 'mitra' => $mitra]);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/mitra",
     *     tags={"Admin-mitra"},
     *     summary="Get List perusahaan mitra",
     *     description="data perusahaan mitra",
     *     operationId="admin/showMitra",
     *     @OA\Response(
     *         response="default",
     *         description="return array model PerusahaanMitra"
     *     )
     * )
     */
    public function showMitra()
    {
        $mitra = PerusahaanMitra::get()->all();
        return response()->json($mitra);
    }

/**
 * @OA\Delete(
 *     path="/api/admin/mitra/{id}",
 *     tags={"Admin-mitra"},
 *     summary="Hapus data perusahaan mitra",
 *     description="Hapus data berdasarkan ID",
 *     operationId="admin/deleteMitra",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID yang ingin dihapus",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *  @OA\Response(
 *         response="default",
 *         description="return array model perusahaan mitra"
 *     )
 *   )
 * )
 */
    public function deleteMitra($id)
    {
        $mitra = PerusahaanMitra::find($id);
        if (!$mitra) {
            return response()->json(['message' => 'mitra tidak ditemukan'], 404);
        }
        $mitra->delete();

        return response()->json(['message' => 'Data mitra berhasil dihapus']);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/berita",
     *     tags={"Admin-berita"},
     *     summary="Get List berita",
     *     description="data berita",
     *     operationId="admin/showBerita",
     *     @OA\Response(
     *         response="default",
     *         description="return array model user"
     *     )
     * )
     */
    public function showBerita()
    {
        $berita = berita::all();
        return response()->json($berita);
    }

    /**
 * @OA\Post(
 *     path="/api/admin/tambah-berita",
 *     tags={"Admin-berita"},
 *     summary="Tambah data berita",
 *     description="Endpoint ini digunakan untuk menambah data berita.",
 *     operationId="admin/tambahBerita",
 *     @OA\RequestBody(
 *          required=true,
 *          description="Form tambah berita",
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  required={"judul", "konten"},
 *                  @OA\Property(property="judul", type="string"),
 *                  @OA\Property(property="konten", type="string"),
 *                  @OA\Property(property="tanggal", type="string", format="date"),
 *                  @OA\Property(property="gambar", type="string", format="binary")
 *              )
 *          )
 *      ),
 *     @OA\Response(
 *         response="default",
 *         description="return array model berita"
 *     )
 *   )
 * )
 */
    public function tambahBerita(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'judul' => 'required|string|unique:berita,judul|max:255',
            'konten' => 'required|string',
            'tanggal' => 'nullable|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $photoPath = $berita['gambar'] ?? null;
        if ($request->hasFile('gambar')) {
            $photoPath = $request->file('gambar')->store('photos', 'public');
        }

        $berita = berita::create([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'tanggal' => now(),
            'gambar' => $photoPath,
        ]);
        
        return response()->json($berita,201);
    }

/**
 * @OA\Post(
 *     path="/api/admin/update-berita/{id}",
 *     tags={"Admin-berita"},
 *     summary="Update data berita",
 *     description="Update data berita berdasarkan ID",
 *     operationId="admin/updateBerita",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID berita yang ingin diperbarui",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Data yang akan diperbarui",
 *         @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  @OA\Property(property="judul", type="string"),
 *                  @OA\Property(property="konten", type="string"),
 *                  @OA\Property(property="tanggal", type="string", format="date"),
 *                  @OA\Property(property="gambar", type="string", format="binary")
 *         )
 *      )
 *     ),
 *       @OA\Response(
 *         response="default",
 *         description="return array model berita"
 *     )
 *   )
 * )
 */
    public function updateBerita(Request $request, $id)
    {
        $berita = berita::find($id);

        if (!$berita) {
            return response()->json(['message' => 'berita not found'], 404);
        }
        $validator = Validator::make($request->all(),[
            'judul' => 'nullable|string|unique:berita,judul|max:255',
            'konten' => 'nullable|string',
            'tanggal' => 'date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $photoPath = $berita['gambar'] ?? null;
        if ($request->hasFile('gambar')) {
            $photoPath = $request->file('gambar')->store('photos', 'public');
        }

        $berita->update([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'tanggal' => now(),
            'gambar' => $photoPath,
        ]);
        
        $beritas = berita::find($id);
        return response()->json(['message' => 'berita berhasil diupdated', 'berita' => $beritas],201);
    }

/**
 * @OA\Delete(
 *     path="/api/admin/berita/{id}",
 *     tags={"Admin-berita"},
 *     summary="Hapus data berita",
 *     description="Hapus data berita berdasarkan ID",
 *     operationId="admin/deleteBerita",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID berita yang ingin dihapus",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *  @OA\Response(
 *         response="default",
 *         description="return array model berita"
 *     )
 *   )
 * )
 */
    public function deleteBerita($id)
    {
        $berita = berita::find($id);

        if (!$berita) {
            return response()->json(['message' => 'berita not found'], 404);
        }

        $berita->delete();

        return response()->json(['message' => 'berita deleted successfully']);
    }


    public function showrecruitment()
    {
        $recruitments = recruitment::with('perusahaanMitra', 'user')
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json($recruitments);
    }

    public function approveRecruitment(Request $request, $ID_recruitment)
    {
        $recruitment = recruitment::find($ID_recruitment);

        if (!$recruitment) {
            return response()->json(['message' => 'Recruitment not found'], 404);
        }

        $status = $request->input('status');
        if ($status === 'centang') {
            $recruitment->status = 'menunggu';
            $recruitment->info = 'diterima';
        } elseif ($status === 'silang') {
            $recruitment->status = 'tersedia';
            $recruitment->info = 'ditolak';
        } else {
            return response()->json(['message' => 'Invalid status input'], 400);
        }
        $recruitment->save();
        $mitra = PerusahaanMitra::all();
        Notification::send($mitra, new approveAdmin($recruitment));

        return response()->json(['message' => 'Recruitment approved', 'status' => $status], 200);
    }

    public function approvePenerimaan(Request $request, $ID_recruitment)
    {
        $recruitment = recruitment::find($ID_recruitment);

        if (!$recruitment) {
            return response()->json(['message' => 'Recruitment not found'], 404);
        }

        $status = $request->input('status');
        if ($status === 'centang') {
            $recruitment->status = 'tidak tersedia';
            $recruitment->info = 'diterima';
            $recruitment->info_penerimaan = 'diterima';
        } elseif ($status === 'silang') {
            $recruitment->status = 'tersedia';
            $recruitment->info = 'diterima';
            $recruitment->info_penerimaan = 'ditolak';
        } else {
            return response()->json(['message' => 'Invalid status input'], 400);
        }
        $recruitment->save();
        $mitra = PerusahaanMitra::all();
        $user = User::find($recruitment->ID_user);
        if (!$mitra || !$user) {
            return response()->json(['message' => 'PerusahaanMitra or User not found'], 404);
        }
        
        Notification::send($mitra, new penerimaanAdmin($recruitment));
        Notification::send($user, new userNotification($recruitment));

        return response()->json(['message' => 'Recruitment approved', 'status' => $status], 200);
    }

/**
 * @OA\Post(
 *     path="/api/admin/sendWA/{jadwal}",
 *     tags={"WhatsApp"},
 *     summary="Send WhatsApp message to recipients based on schedule",
 *     description="Sends a WhatsApp message to recipients based on the specified schedule ID.",
 *     operationId="sendWhatsapp",
 *     @OA\Parameter(
 *         name="ID_jadwal",
 *         in="path",
 *         required=true,
 *         description="ID of the schedule to send WhatsApp message for",
 *         @OA\Schema(type="integer")
 *     ),
 * @OA\Response(
 *         response="default",
 *         description="return array model user"
 *     )
 *   )
 * )
 */
    public function sendWhatsapp($ID_jadwal)
    {
        $jadwal = jadwal::find($ID_jadwal);

    if (!$jadwal) {
        return response()->json(['message' => 'Jadwal not found'], 404);
    }

    $penerimas = PenerimaJadwal::where('ID_jadwal', $jadwal->ID_jadwal)->get();
    $targets = [];

    foreach ($penerimas as $penerima) {
        $user = User::find($penerima->ID_user);

        if ($user) {
            $info = $user->info;
            $nohp = $info['no_hp'] ?? null;

            if ($nohp) {
                $targets[] = $nohp . '|' . $user->name . '|' . $user->role;
            } else {
                \Log::warning("No phone number found for user ID: " . $user->id);
            }
        } else {
            \Log::warning("User not found for recipient ID: " . $penerima->ID_user);
        }
    }

    if (!empty($targets)) {
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.fonnte.com/send',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => array(
                            'target' => implode(',', $targets),
                            'message' => 'Halo kami dari Pt. Citratama Indonesia ingin memberikan informasi terkain dengan jadwal recruitment yang anda ikutin. '.
                                        'Jadwal Anda: ' . $jadwal->tanggal . ' di ' . $jadwal->tempat . ' jam ' . $jadwal->jam,
                            'countryCode' => '62',
                        ),
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: +wJLH9f!c8!cJPd8THa6'
                        ),
                    ));

                    $response = curl_exec($curl);
                    
                    if (curl_errno($curl)) {
                        $error_msg = curl_error($curl);
                        \Log::error('Error sending message: ' . $error_msg);
                        return response()->json(['message' => 'Error sending message: ' . $error_msg], 500);
                    }
                    
                    foreach ($penerimas as $penerima) {
                        $user = User::find($penerima->ID_user);
                        Notification::send($user, new userJadwalNotification($jadwal));
                    }

                    
                    curl_close($curl);

                    return response()->json(['message' => 'Messages sent successfully'], 200);
                } else {
                    return response()->json(['message' => 'No valid targets found'], 404);
                }
            }
    public function showNotifAdmin()
    {
        $admin = Admin::all();
        $notifications = notifications::where('notifiable_type', 'App\Models\Admin')
        ->where('notifiable_id', $admin->ID_admin)
        ->get();


        return response()->json($notifications);
    }
}
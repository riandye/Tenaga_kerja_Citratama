<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Authentication"},
 *     summary="Login user or perusahaan mitra",
 *     description="Endpoint untuk melakukan login bagi pengguna dan perusahaan mitra.",
 *     operationId="login",
 *     @OA\RequestBody(
 *          required=true,
 *          description="Email dan password untuk login",
 *          @OA\JsonContent(
 *              required={"email", "password"},
 *              @OA\Property(property="email", type="string", example="user@example.com"),
 *              @OA\Property(property="password", type="string", example="password123"),
 *          ),
 *      ),
 *      @OA\Response(
 *         response="default",
 *         description="return array model user"
 *     )
 *   )
 * )
 */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        // Coba untuk mengotentikasi pengguna dari tabel 'users'
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
        
            $token = $user->createToken('authToken')->plainTextToken;
        
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        }
        
        // Jika tidak berhasil, coba untuk mengotentikasi dari tabel 'perusahaan_mitra'
        if (Auth::guard('perusahaan_mitra')->attempt($request->only('email', 'password'))) {
            $perusahaanMitra = Auth::guard('perusahaan_mitra')->user();
        
            $token = $perusahaanMitra->createToken('authToken')->plainTextToken;
        
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'perusahaan_mitra' => $perusahaanMitra,
            ]);
        }
        
        // Jika kedua jenis autentikasi gagal, kembalikan respons 401 Unauthorized
        return response()->json([
            'message' => 'Invalid login details'
        ], 401);
    }

    /**
 * @OA\Post(
 *     path="/api/logout",
 *     tags={"Authentication"},
 *     summary="Logout user or perusahaan mitra",
 *     description="Endpoint untuk melakukan logout bagi pengguna dan perusahaan mitra.",
 *     operationId="logout",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successfully logged out",
 *     )
 * )
 */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}

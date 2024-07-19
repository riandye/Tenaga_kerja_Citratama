<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class RegisterController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/register",
 *     tags={"Authentication"},
 *     summary="Register a new user",
 *     description="Endpoint untuk melakukan pendaftaran pengguna baru.",
 *     operationId="register",
 *     @OA\RequestBody(
 *          required=true,
 *          description="Form pendaftaran",
 *          @OA\JsonContent(
 *              required={"name", "email", "password", "password_confirmation"},
 *              @OA\Property(property="name", type="string", example="John Doe"),
 *              @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *              @OA\Property(property="password", type="string", format="password", example="password123"),
 *              @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
 *          ),
 *      ),
 *     @OA\Response(
 *         response="default",
 *         description="return array model user"
 *     )
 *   )
 * )
 */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'info' => [
                'name' => $data['name']
            ],
        ]);
        
        return response()->json([
            'message' => ucfirst($user->role) . ' registration successful',
            'user' => $user,
        ]);
    }
}

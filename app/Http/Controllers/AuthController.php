<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="News Aggregator API",
 *      description="API documentation for News Aggregator",
 *      @OA\Contact(
 *          email="your@email.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url="http://localhost:8080/api",
 *      description="Localhost API Server"
 * )
 *
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/register",
     *      summary="Register a new user",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password"},
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="password", type="string", format="password")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User registered successfully"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      )
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['token' => $user->createToken('auth_token')->plainTextToken], 201);
    }

    /**
     * @OA\Post(
     *      path="/login",
     *      summary="Login user",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="password", type="string", format="password")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login successful"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid credentials"
     *      )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json(['token' => $user->createToken('auth_token')->plainTextToken]);
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      summary="Logout user",
     *      tags={"Authentication"},
     *      security={{ "bearerAuth":{} }},
     *      @OA\Response(
     *          response=200,
     *          description="Logged out successfully"
     *      )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="User Preferences",
 *     description="Endpoints for managing user preferences"
 * )
 */
class PreferenceController extends Controller
{
    /**
     * Enforce authentication on all methods.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    // }

    /**
     * @OA\Get(
     *      path="/preferences",
     *      summary="Get user preferences",
     *      tags={"User Preferences"},
     *      security={{ "bearerAuth":{} }},
     *      @OA\Response(
     *          response=200,
     *          description="User preferences retrieved successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="category", type="string"),
     *              @OA\Property(property="source", type="string"),
     *              @OA\Property(property="author", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     */
    public function index()
    {
        $preferences = Auth::user()->preferences;

        return response()->json([
            'message' => 'User preferences retrieved successfully',
            'data' => $preferences
        ], 200);
    }

    /**
     * @OA\Post(
     *      path="/preferences",
     *      summary="Set user preferences",
     *      tags={"User Preferences"},
     *      security={{ "bearerAuth":{} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="category", type="string"),
     *              @OA\Property(property="source", type="string"),
     *              @OA\Property(property="author", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Preferences saved successfully"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid request data"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
        ]);

        $preference = Preference::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        return response()->json([
            'message' => 'Preferences saved successfully',
            'data' => $preference
        ], 201);
    }

    /**
     * @OA\Put(
     *      path="/preferences",
     *      summary="Update user preferences",
     *      tags={"User Preferences"},
     *      security={{ "bearerAuth":{} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="category", type="string"),
     *              @OA\Property(property="source", type="string"),
     *              @OA\Property(property="author", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Preferences updated successfully"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Preferences not found"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
        ]);

        $preference = Preference::where('user_id', Auth::id())->first();

        if (!$preference) {
            return response()->json([
                'message' => 'Preferences not found'
            ], 404);
        }

        $preference->update($validated);

        return response()->json([
            'message' => 'Preferences updated successfully',
            'data' => $preference
        ], 200);
    }
}

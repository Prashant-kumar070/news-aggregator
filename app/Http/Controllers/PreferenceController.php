<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    // Get user preferences
    public function index()
    {
        $preferences = Auth::user()->preferences;
        return response()->json($preferences);
    }

    // Store user preferences
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
        ]);

        $preference = Preference::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only('category', 'source', 'author')
        );

        return response()->json(['message' => 'Preferences saved', 'data' => $preference]);
    }

    // Update user preferences
    public function update(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
        ]);

        $preference = Auth::user()->preferences;
        if (!$preference) {
            return response()->json(['message' => 'Preferences not found'], 404);
        }

        $preference->update($request->only('category', 'source', 'author'));
        return response()->json(['message' => 'Preferences updated', 'data' => $preference]);
    }
}

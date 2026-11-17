<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Handle user registration via API
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|in:user,validator',
            'phone' => 'nullable|string|max:15',
            'birth_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload if provided
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->input('role', 'user'), // Role selected by user (donatur/validator)
            'coins' => 0, // Default coins for new users
            'photo' => $photoPath,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
        ]);

        // Notifikasi ke Admin: Pengguna Baru Terdaftar dari Mobile
        try {
            AdminNotification::create([
                'title'           => '👤 Pengguna Baru Terdaftar',
                'message'         => 'Pengguna baru bernama "' . $user->name . '" (' . $user->role . ') telah mendaftar melalui aplikasi mobile.',
                'category'        => 'sistem',
                'priority'        => 'low',
                'icon'            => '👤',
                'type'            => 'user_registered',
                'action_label'    => 'Lihat Profil',
                'action_url'      => route('admin.profiles.show', $user->id),
                'related_user_id' => $user->id,
                'metadata'        => [
                    'name'   => $user->name,
                    'email'  => $user->email,
                    'role'   => $user->role,
                    'source' => 'mobile',
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create admin notification for new user registration: ' . $e->getMessage());
        }


        // Create token for the new user
        $token = $user->createToken('auth-token')->plainTextToken;

        $responseData = [
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'coins' => $user->coins,
                'photo' => $user->photo,
                'phone' => $user->phone,
                'birth_date' => $user->birth_date ? $user->birth_date->format('Y-m-d') : null,
            ],
            'token' => $token,
            'token_type' => 'Bearer'
        ];

        $response = response()->json($responseData, 201);


        return $response;
    }

    /**
     * Handle user login via API
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $response = response()->json([
                'message' => 'Invalid credentials',
                'errors' => [
                    'email' => ['Email atau password salah. Silakan coba lagi.']
                ]
            ], 422);


            return $response;
        }

        // Create new token
        $token = $user->createToken('auth-token')->plainTextToken;

        $responseData = [
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'coins' => $user->coins,
                'photo' => $user->photo,
                'phone' => $user->phone,
                'birth_date' => $user->birth_date ? $user->birth_date->format('Y-m-d') : null,
            ],
            'token' => $token,
            'token_type' => 'Bearer'
        ];

        $response = response()->json($responseData);


        return $response;
    }

    /**
     * Handle user logout via API
     */
    public function logout(Request $request): JsonResponse
    {
        $currentToken = $request->user()->currentAccessToken();
        if ($currentToken instanceof \Laravel\Sanctum\PersonalAccessToken) {
            $currentToken->delete();
        }

        // Bearer-token API requests do not necessarily have a web session.
        if ($request->hasSession()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $response = response()->json([
            'message' => 'Logout successful'
        ]);


        return $response;
    }

    /**
     * Get authenticated user info
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'error' => 'User not authenticated',
                'message' => 'No authenticated user found'
            ], 401);
        }

        $responseData = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'coins' => $user->coins,
                'photo' => $user->photo,
                'phone' => $user->phone,
                'birth_date' => $user->birth_date ? $user->birth_date->format('Y-m-d') : null,
            ]
        ];

        $response = response()->json($responseData);


        return $response;
    }

    /**
     * Refresh the authentication token
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        $currentToken = $user->currentAccessToken();
        if (! $currentToken instanceof \Laravel\Sanctum\PersonalAccessToken) {
            return response()->json(['message' => 'Bearer token required.'], 401);
        }
        $token = \Illuminate\Support\Facades\DB::transaction(function () use ($user, $currentToken) {
            $token = $user->createToken('auth-token')->plainTextToken;
            $currentToken->delete();
            return $token;
        });

        $responseData = [
            'message' => 'Token refreshed successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'coins' => $user->coins,
                'photo' => $user->photo,
                'phone' => $user->phone,
                'birth_date' => $user->birth_date ? $user->birth_date->format('Y-m-d') : null,
            ],
            'token' => $token,
            'token_type' => 'Bearer'
        ];

        $response = response()->json($responseData);


        return $response;
    }
}

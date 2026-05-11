<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ValidatorController extends Controller
{
    /**
     * Mengambil detail kampanye berdasarkan token (untuk Flutter)
     */
    public function showCampaign($token): JsonResponse
    {
        $campaign = Campaign::where('verification_token', $token)
            ->whereIn('status', ['Pending', 'pending'])
            ->first();

        if (!$campaign) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kampanye tidak ditemukan, token tidak valid, atau sudah diverifikasi sebelumnya.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data kampanye ditemukan',
            'data' => $campaign
        ]);
    }

    /**
     * Menyetujui/Verifikasi Kampanye (untuk Flutter)
     */
    public function verifyCampaign(Request $request, $token): JsonResponse
    {
        $user = auth()->user();

        // Pastikan role-nya adalah validator
        if (strtolower($user->role) !== 'validator') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak: Hanya akun dengan Role Validator yang dapat melakukan verifikasi.'
            ], 403);
        }

        $campaign = Campaign::where('verification_token', $token)
            ->whereIn('status', ['Pending', 'pending'])
            ->first();

        if (!$campaign) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kampanye tidak ditemukan atau sudah diverifikasi.'
            ], 404);
        }

        $campaign->update([

            'status' => 'active',
            'status' => 'verified',

            'validator_name' => $user->name,
            'validator_user_id' => $user->id,
            'verified_at' => Carbon::now(),
            'verification_token' => null, // Token hangus setelah digunakan
        ]);

        return response()->json([
            'status' => 'success',

            'message' => 'Verifikasi Berhasil! Kampanye kini berstatus Active.',

            'message' => 'Verifikasi Berhasil! Kampanye kini berstatus verified.',

            'data' => $campaign
        ]);
    }

    /**
     * Mengambil riwayat verifikasi milik validator ini (untuk Flutter)
     */
    public function getHistory(): JsonResponse
    {
        $user = auth()->user();

        $history = Campaign::where('validator_user_id', $user->id)

            ->whereIn('status', ['Verified', 'active'])

            ->whereIn('status', ['Verified', 'verified'])

            ->latest('verified_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat verifikasi ditemukan',
            'total' => $history->count(),
            'data' => $history
        ]);
    }
}

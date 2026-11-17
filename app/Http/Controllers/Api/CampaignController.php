<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns with pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        try {
            \Log::info('API V1 Campaign Index called', ['params' => $request->all()]);

            $query = Campaign::with('user');
            $viewer = auth('sanctum')->user();
            $isMine = $request->boolean('my');
            if ($isMine && !$viewer) abort(401, 'Silakan login untuk melihat pengajuan Anda.');
            if (!$isMine && strtolower((string) $viewer?->role) !== 'admin') {
                $query->where('status', 'verified');
            }

            // Add filters if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            if ($isMine) {
                $user = auth('sanctum')->user();
                if ($user) {
                    $query->where('user_id', $user->id);
                } else {
                    $query->where('user_id', 0);
                }
                $query->orderBy('created_at', 'desc');
            } else {
                // Sort by end_date ascending (nearest first) by default
                $query->orderBy('end_date', 'asc');
            }

            $campaigns = $query->paginate(10);

            \Log::info('Campaigns count: ' . $campaigns->count());

            return CampaignResource::collection($campaigns);
        } catch (\Exception $e) {
            \Log::error('Error in API V1 Campaign Index: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());

            throw $e;
        }
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(Request $request): JsonResponse
    {
        abort_unless(in_array(strtolower((string) $request->user()->role), ['user', 'donatur', 'admin'], true), 403, 'Hanya akun donatur yang dapat mengajukan kampanye.');
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:10000|max:9999999999999',
            'end_date' => 'required|date|after:today',
            'kategori' => 'required|string|in:Lingkungan,Kesehatan,Pendidikan,Sosial Kemanusiaan,Bencana Alam',
            'yayasan' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'sometimes|string|in:pending,verified,rejected',
            'lokasi' => 'required|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'alamat_lengkap' => 'nullable|string',
            'kode_pos' => 'nullable|string|max:10',
            'jenis_penerima' => 'required|in:Individu,Yayasan,Komunitas',
            'recipient_description' => 'required|string|max:5000',
            'whatsapp' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9][0-9\s-]{7,19}$/'],
            'penyaluran' => 'required|in:Langsung,Yayasan,Platform',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Handle image upload if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('campaigns', 'public');
        }

        $validatedData['status'] = 'pending'; // Approval remains an admin decision.
        $validatedData['current_amount'] = 0;
        $validatedData['image'] = $imagePath;
        $validatedData['user_id'] = $request->user()->id; // Assuming user is authenticated
        $validatedData['slug'] = Str::slug($request->title);

        // Check if slug already exists and make unique
        $originalSlug = $validatedData['slug'];
        $counter = 1;
        while (Campaign::where('slug', $validatedData['slug'])->exists()) {
            $validatedData['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        try {
            $campaign = Campaign::create($validatedData);
        } catch (\Throwable $e) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            throw $e;
        }

        // Notifikasi ke admin: kampanye baru dibuat dari mobile
        try {
            $user = $request->user();
            AdminNotification::create([
                'title'               => '📢 Kampanye Baru Menunggu Verifikasi',
                'message'             => 'Kampanye "' . $campaign->title . '" dibuat oleh ' . $user->name .
                    ' dari aplikasi mobile dan menunggu persetujuan admin.',
                'category'            => 'kampanye',
                'priority'            => 'high',
                'icon'                => '📢',
                'type'                => 'campaign_pending_approval',
                'action_label'        => 'Lihat Detail',
                'action_url'          => route('admin.campaigns.show', $campaign->id),
                'related_campaign_id' => $campaign->id,
                'related_user_id'     => $user->id,
                'metadata'            => [
                    'campaign_title'  => $campaign->title,
                    'creator_name'    => $user->name,
                    'target_amount'   => $campaign->target_amount,
                    'kategori'        => $campaign->kategori,
                    'source'          => 'mobile',
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create admin notification for campaign: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Campaign created successfully',
            'data' => new CampaignResource($campaign->load('user'))
        ], 201);
    }

    /**
     * Display the specified campaign by slug or ID.
     */
    public function show(string $id): CampaignResource|JsonResponse
    {
        $campaign = Campaign::with('user')->where('slug', $id)->orWhere('id', $id)->first();

        if (!$campaign) {
            return response()->json([
                'message' => 'Campaign not found'
            ], 404);
        }

        $viewer = auth('sanctum')->user();
        if ($campaign->status !== 'verified' &&
            (!$viewer || (strtolower((string) $viewer->role) !== 'admin' && (int) $viewer->id !== (int) $campaign->user_id))) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }
        return new CampaignResource($campaign);
    }

    /**
     * Update the specified campaign in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $campaign = Campaign::where('slug', $id)->orWhere('id', $id)->first();

        if (!$campaign) {
            return response()->json([
                'message' => 'Campaign not found'
            ], 404);
        }

        $isAdmin = strtolower((string) $request->user()->role) === 'admin';
        abort_unless($isAdmin || (int) $campaign->user_id === (int) $request->user()->id, 403);
        if (!$isAdmin && $request->has('status')) {
            abort(403, 'Status verifikasi hanya dapat diubah oleh admin.');
        }
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'target_amount' => 'sometimes|required|numeric|min:0',
            'end_date' => 'sometimes|required|date|after_or_equal:today',
            'kategori' => 'sometimes|required|string|in:Lingkungan,Kesehatan,Pendidikan,Sosial Kemanusiaan,Bencana Alam',
            'yayasan' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|string|in:pending,verified,rejected',
            'lokasi' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'alamat_lengkap' => 'nullable|string',
            'kode_pos' => 'nullable|string|max:10',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }

            $imagePath = $request->file('image')->store('campaigns', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Update slug if title changed
        if (isset($validatedData['title']) && $validatedData['title'] !== $campaign->title) {
            $slug = Str::slug($validatedData['title']);
            $originalSlug = $slug;
            $counter = 1;
            
            // Check if slug already exists (excluding current campaign)
            while (Campaign::where('slug', $slug)->where('id', '!=', $campaign->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $validatedData['slug'] = $slug;
        }

        $campaign->update($validatedData);

        return response()->json([
            'message' => 'Campaign updated successfully',
            'data' => new CampaignResource($campaign->load('user'))
        ]);
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $campaign = Campaign::where('slug', $id)->orWhere('id', $id)->first();

        if (!$campaign) {
            return response()->json([
                'message' => 'Campaign not found'
            ], 404);
        }

        abort_unless(strtolower((string) $request->user()->role) === 'admin' || (int) $campaign->user_id === (int) $request->user()->id, 403);

        // Delete associated image if exists
        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }

        $campaign->delete();

        return response()->json([
            'message' => 'Campaign deleted successfully'
        ], 200);
    }

    /**
     * Get urgent campaigns (campaigns with less than 3 days remaining and at least 50% funded)
     */
    public function urgent(Request $request): AnonymousResourceCollection
    {
        // Ambil semua kampanye yang sudah diverifikasi dan belum berakhir
        $query = Campaign::with('user')
            ->where('status', 'verified')
            ->where('end_date', '>=', now())
            ->orderBy('end_date', 'asc'); // Urutkan dari yang paling dekat deadline-nya

        $urgentCampaigns = $query->paginate(10);

        return CampaignResource::collection($urgentCampaigns);
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    /**
     * Get all notifications for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $unreadOnly = $request->boolean('unread_only', false);
        $filter = $request->get('filter', 'all'); // all, donasi, kampanye, relawan, sistem

        $query = $user->notifications();
        
        if ($unreadOnly) {
            $query = $user->unreadNotifications();
        }

        $allNotifications = $query->latest()->get();

        // Map and classify categories
        $notifications = $allNotifications->map(function ($notif) {
            $data = is_array($notif->data) ? $notif->data : json_decode($notif->data, true);
            
            return [
                'id' => $notif->id,
                'data' => $data,
                'type' => $notif->type,
                'title' => $data['title'] ?? 'Notifikasi Baru',
                'message' => $data['message'] ?? '',
                'icon' => $data['icon'] ?? 'fas fa-bell',
                'color' => $data['color'] ?? 'text-blue-500',
                'category' => $this->getCategory($notif),
                'url' => $data['url'] ?? null,
                'is_high' => $this->isHighPriority($notif),
                'read_at' => $notif->read_at,
                'is_read' => !is_null($notif->read_at),
                'created_at' => $notif->created_at,
                'time_ago' => $notif->created_at ? $notif->created_at->diffForHumans() : ''
            ];
        });

        // Filter by category if specified
        if ($filter !== 'all') {
            $notifications = $notifications->filter(function ($notif) use ($filter) {
                return $notif['category'] === $filter;
            })->values();
        }

        return response()->json([
            'message' => 'Notifications retrieved successfully',
            'data' => $notifications->forPage(
                max(1, (int) $request->input('page', 1)),
                min(100, max(1, (int) $request->input('per_page', 15)))
            )->values()
        ]);
    }

    /**
     * Get unread count
     */
    public function unreadCount(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        
        return response()->json([
            'message' => 'Unread notifications count retrieved successfully',
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }

    /**
     * Mark a specific notification as read
     */
    public function markRead($id): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $notification = $user->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read successfully',
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read successfully',
            'unread_count' => 0
        ]);
    }

    /**
     * Delete a specific notification
     */
    public function destroy($id): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $notification = $user->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully',
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }

    /**
     * Seed beautiful demo notifications for premium display and validation
     */
    public function seedDemoNotifications(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // Delete existing notifications to avoid duplicates and ensure a clean review
        $user->notifications()->delete();
        \App\Models\RecommendationNotification::where('user_id', $user->id)->delete();

        // Ensure dummy campaigns exist for recommendation seeding
        if (\App\Models\VolunteerCampaign::count() === 0) {
            \App\Models\VolunteerCampaign::create([
                'judul' => 'Aksi Bersih Pantai Pangandaran 🌊',
                'slug' => 'aksi-bersih-pantai-pantai-' . Str::random(4),
                'kategori' => 'Lingkungan',
                'lokasi' => 'Pangandaran, Jawa Barat',
                'deskripsi' => 'Membantu membersihkan area pantai Pangandaran demi menjaga ekosistem laut.',
                'tanggal_mulai' => now()->addDays(7),
                'tanggal_selesai' => now()->addDays(8),
                'kuota_total' => 50,
                'kuota_terisi' => 12,
                'status' => 'Aktif',
            ]);
            \App\Models\VolunteerCampaign::create([
                'judul' => 'Mengajar Bimbingan Belajar Gratis 📚',
                'slug' => 'mengajar-bimbingan-belajar-' . Str::random(4),
                'kategori' => 'Pendidikan',
                'lokasi' => 'Bandung, Jawa Barat',
                'deskripsi' => 'Bimbingan belajar gratis untuk anak-anak kurang mampu di desa Sukajadi.',
                'tanggal_mulai' => now()->addDays(3),
                'tanggal_selesai' => now()->addDays(10),
                'kuota_total' => 10,
                'kuota_terisi' => 3,
                'status' => 'Aktif',
            ]);
        }

        // Seed 2 recommendation notifications
        $campaigns = \App\Models\VolunteerCampaign::limit(2)->get();
        foreach ($campaigns as $index => $campaign) {
            \App\Models\RecommendationNotification::create([
                'user_id' => $user->id,
                'campaign_id' => $campaign->id,
                'matching_category' => $campaign->kategori,
                'reason' => $index === 0 
                    ? "Berdasarkan ketertarikan Anda pada kategori {$campaign->kategori}."
                    : "Rekomendasi terbaik untuk kategori {$campaign->kategori} sesuai minat Anda.",
                'is_read' => false,
                'is_dismissed' => false,
            ]);
        }

        $demoNotifications = [
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\CustomNotification',
                'notifiable_type' => get_class($user),
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Donasi Berhasil 💰',
                    'message' => 'Terima kasih banyak! Donasi Anda sebesar Rp100.000 untuk kampanye "Bantu Pendidikan Anak Desa" telah berhasil diterima dan diproses.',
                    'icon' => 'fas fa-coins',
                    'color' => 'text-blue-500',
                    'category' => 'donasi',
                    'url' => route('profiles.index'),
                ]),
                'read_at' => null,
                'created_at' => now()->subMinutes(2),
                'updated_at' => now()->subMinutes(2),
            ],
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\CustomNotification',
                'notifiable_type' => get_class($user),
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Kampanye Disetujui 📢',
                    'message' => 'Kampanye galang dana "Pembangunan Sekolah Darurat" yang Anda ajukan telah disetujui oleh admin dan kini dipublikasikan.',
                    'icon' => 'fas fa-bullhorn',
                    'color' => 'text-indigo-500',
                    'category' => 'kampanye',
                    'url' => '#',
                ]),
                'read_at' => null,
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ],
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\CustomNotification',
                'notifiable_type' => get_class($user),
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Relawan Diterima 🤝',
                    'message' => 'Selamat! Pendaftaran Anda sebagai relawan untuk program kemanusiaan "Aksi Bersih Pantai" telah disetujui. Silakan cek detail jadwal kegiatan.',
                    'icon' => 'fas fa-handshake',
                    'color' => 'text-green-500',
                    'category' => 'relawan',
                    'url' => '#',
                ]),
                'read_at' => null,
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ],
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\CustomNotification',
                'notifiable_type' => get_class($user),
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Dana Telah Disalurkan 💰',
                    'message' => 'Kabar gembira! Penyaluran dana untuk kampanye "Bantu Pendidikan Anak Desa" sebesar Rp5.000.000 telah selesai disalurkan.',
                    'icon' => 'fas fa-gift',
                    'color' => 'text-emerald-500',
                    'category' => 'donasi',
                    'url' => '#',
                ]),
                'read_at' => now()->subDays(1),
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\CustomNotification',
                'notifiable_type' => get_class($user),
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Informasi Keamanan Akun ⚙️',
                    'message' => 'Kami mendeteksi aktivitas login baru pada akun Anda dari peramban Chrome Windows pukul 02:43 AM. Jika bukan Anda, amankan akun Anda.',
                    'icon' => 'fas fa-shield-alt',
                    'color' => 'text-amber-500',
                    'category' => 'sistem',
                    'url' => '#',
                ]),
                'read_at' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\CustomNotification',
                'notifiable_type' => get_class($user),
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Laporan Penyaluran Tersedia 📊',
                    'message' => 'Laporan pertanggungjawaban penyaluran dana donasi "Pembangunan Sekolah Darurat" kini telah diunggah oleh pihak penanggung jawab.',
                    'icon' => 'fas fa-file-invoice',
                    'color' => 'text-sky-500',
                    'category' => 'donasi',
                    'url' => '#',
                ]),
                'read_at' => now()->subDays(3),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ]
        ];

        DB::table('notifications')->insert($demoNotifications);

        return response()->json([
            'message' => 'Demo notifications seeded successfully',
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }

    /**
     * Helper to classify category based on notification data.
     */
    private function getCategory($notification)
    {
        $data = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);
        
        if (isset($data['category'])) {
            return $data['category'];
        }

        $title = strtolower($data['title'] ?? '');
        $message = strtolower($data['message'] ?? '');
        $icon = strtolower($data['icon'] ?? '');

        if (str_contains($title, 'donasi') || str_contains($message, 'donasi') || str_contains($icon, 'coin') || str_contains($icon, 'dollar') || str_contains($icon, 'money') || str_contains($icon, 'gift') || str_contains($title, 'dana')) {
            return 'donasi';
        }

        if (str_contains($title, 'kampanye') || str_contains($message, 'kampanye') || str_contains($icon, 'bullhorn') || str_contains($icon, 'megaphone') || str_contains($title, 'campaign')) {
            return 'kampanye';
        }

        if (str_contains($title, 'relawan') || str_contains($message, 'relawan') || str_contains($icon, 'handshake') || str_contains($icon, 'user') || str_contains($title, 'lamaran')) {
            return 'relawan';
        }

        return 'sistem';
    }

    /**
     * Helper to check if a notification has high priority.
     */
    private function isHighPriority($notification)
    {
        $data = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);
        $title = strtolower($data['title'] ?? '');
        
        // High priority check
        if (str_contains($title, 'berhasil') || str_contains($title, 'disetujui') || str_contains($title, 'diterima') || str_contains($title, 'selesai') || str_contains($title, 'mencapai target')) {
            return true;
        }

        return false;
    }
}

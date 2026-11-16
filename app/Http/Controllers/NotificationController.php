<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    /**
     * Display all notifications with filters.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Get all database notifications
        $allNotifications = $user->notifications()->latest()->get();

        // Map category and priority to each notification dynamically
        $allNotifications = $allNotifications->map(function ($notif) {
            $data = $notif->data;
            
            // Set dynamic category
            $notif->category = $this->getCategory($notif);
            
            // Set priority status
            $notif->is_high = $this->isHighPriority($notif);

            return $notif;
        });

        // Get filter counts
        $counts = [
            'all' => $allNotifications->count(),
            'donasi' => 0,
            'kampanye' => 0,
            'relawan' => 0,
            'sistem' => 0,
        ];

        foreach ($allNotifications as $notif) {
            $cat = $notif->category;
            if (isset($counts[$cat])) {
                $counts[$cat]++;
            } else {
                $counts['sistem']++;
            }
        }

        $rekomendasiCount = \App\Models\RecommendationNotification::where('user_id', $user->id)
            ->where('is_dismissed', false)
            ->count();
        $counts['rekomendasi'] = $rekomendasiCount;

        // Apply filtering
        $filter = $request->get('filter', 'all');
        $notifications = $allNotifications;
        $recommendations = collect();

        if ($filter === 'rekomendasi') {
            $recommendations = \App\Models\RecommendationNotification::where('user_id', $user->id)
                ->where('is_dismissed', false)
                ->with('campaign')
                ->latest()
                ->get();
            $notifications = collect();
        } elseif ($filter !== 'all') {
            $notifications = $allNotifications->filter(function ($notif) use ($filter) {
                return $notif->category === $filter;
            });
        }

        return view('notifications.index', compact('notifications', 'recommendations', 'counts', 'filter'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Delete a specific notification.
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Helper to classify category based on notification data.
     */
    private function getCategory($notification)
    {
        $data = $notification->data;
        
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
        $title = strtolower($notification->data['title'] ?? '');
        
        // High priority: donasi berhasil, kampanye disetujui, kampanye selesai, lamaran diterima/disetujui
        if (str_contains($title, 'berhasil') || str_contains($title, 'disetujui') || str_contains($title, 'diterima') || str_contains($title, 'selesai') || str_contains($title, 'mencapai target')) {
            return true;
        }

        return false;
    }

    /**
     * Seed beautiful demo notifications for premium display and validation.
     */
    public function seedDemoNotifications()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Delete existing database notifications to avoid duplicates and ensure a clean review
        $user->notifications()->delete();

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

        return redirect()->route('notifications.index')->with('success', 'Berhasil menghasilkan notifikasi demo premium! Anda dapat mengujinya sekarang.');
    }

    /**
     * Mark a specific recommendation notification as read and redirect.
     */
    public function markRecommendationRead($id)
    {
        $recommendation = \App\Models\RecommendationNotification::where('user_id', auth()->id())->findOrFail($id);
        $recommendation->update(['is_read' => true]);

        if ($recommendation->campaign) {
            return redirect()->route('volunteer.campaigns.show', $recommendation->campaign->slug);
        }

        return back()->with('success', 'Rekomendasi kampanye ditandai sebagai dibaca.');
    }

    /**
     * Dismiss a specific recommendation notification.
     */
    public function markRecommendationDismiss($id)
    {
        $recommendation = \App\Models\RecommendationNotification::where('user_id', auth()->id())->findOrFail($id);
        $recommendation->update(['is_dismissed' => true]);

        return back()->with('success', 'Rekomendasi kampanye berhasil dihapus dari daftar.');
    }
}

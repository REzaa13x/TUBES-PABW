<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Carbon\Carbon;

class AdminNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                abort(403, 'Akses ditolak. Anda bukan admin.');
            }
            return $next($request);
        });
    }

    /**
     * Halaman utama notifikasi admin
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'semua');
        $priority = $request->query('priority', '');
        $search = $request->query('search', '');
        $dateFrom = $request->query('date_from', '');
        $dateTo = $request->query('date_to', '');

        $query = AdminNotification::latest();

        // Filter kategori / status
        if ($category === 'unread') {
            $query->unread();
        } else {
            $query->byCategory($category);
        }

        // Filter prioritas
        if ($priority) {
            $query->byPriority($priority);
        }

        // Filter tanggal
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Pencarian
        $query->search($search);

        $notifications = $query->paginate(15);

        // Hitung statistik
        $stats = [
            'total_unread' => AdminNotification::unread()->count(),
            'today' => AdminNotification::whereDate('created_at', today())->count(),
            'high_priority' => AdminNotification::where('priority', 'high')->unread()->count(),
            'all' => AdminNotification::count(),
            'kampanye' => AdminNotification::where('category', 'kampanye')->count(),
            'donasi' => AdminNotification::where('category', 'donasi')->count(),
            'relawan' => AdminNotification::where('category', 'relawan')->count(),
            'laporan' => AdminNotification::where('category', 'laporan')->count(),
            'smart_matching' => AdminNotification::where('category', 'smart_matching')->count(),
            'sistem' => AdminNotification::where('category', 'sistem')->count(),
        ];

        // Activity feed terbaru (untuk dashboard widget)
        $activityFeed = AdminNotification::latest()->limit(10)->get();

        return view('admin.notifications.index', compact(
            'notifications',
            'category',
            'priority',
            'search',
            'dateFrom',
            'dateTo',
            'stats',
            'activityFeed'
        ));
    }

    /**
     * Form tambah notifikasi baru
     */
    public function create()
    {
        return view('admin.notifications.create');
    }

    /**
     * Buat notifikasi baru secara manual oleh Admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'category' => 'nullable|string',
            'priority' => 'nullable|string',
            'send_to_users' => 'nullable',
        ]);

        $category = $request->input('category', 'sistem');
        $priority = $request->input('priority', 'medium');

        $icon = match($category) {
            'donasi' => '💰',
            'kampanye' => '📢',
            'relawan' => '🤝',
            'laporan' => '🚩',
            default => '🔔',
        };

        AdminNotification::create([
            'title' => $request->title,
            'message' => $request->message,
            'category' => $category,
            'priority' => $priority,
            'icon' => $icon,
            'type' => 'manual',
            'is_read' => false,
        ]);

        if ($request->boolean('send_to_users') || $request->input('send_to_users') == '1') {
            $userIcon = match($category) {
                'donasi' => 'fas fa-hand-holding-heart',
                'kampanye' => 'fas fa-bullhorn',
                'relawan' => 'fas fa-hands-helping',
                default => 'fas fa-bell',
            };
            $users = \App\Models\User::all();
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\CustomNotification(
                    $request->title,
                    $request->message,
                    $userIcon,
                    'text-blue-500'
                ));
            }
        }

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil ditambahkan!');
    }

    /**
     * Form edit notifikasi
     */
    public function edit($id)
    {
        $notification = AdminNotification::findOrFail($id);
        return view('admin.notifications.edit', compact('notification'));
    }

    /**
     * Update notifikasi
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'category' => 'nullable|string',
            'priority' => 'nullable|string',
        ]);

        $notification = AdminNotification::findOrFail($id);
        $notification->update([
            'title' => $request->title,
            'message' => $request->message,
            'category' => $request->input('category', $notification->category),
            'priority' => $request->input('priority', $notification->priority),
        ]);

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil diperbarui!');
    }

    /**
     * Dropdown notifikasi (untuk navbar)
     */
    public function dropdown()
    {
        $notifications = AdminNotification::unread()
            ->latest()
            ->limit(5)
            ->get();

        $unreadCount = AdminNotification::unread()->count();

        return response()->json([
            'unreadCount' => $unreadCount,
            'notifications' => $notifications->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'icon' => $notif->icon,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'category' => $notif->category,
                    'priority' => $notif->priority,
                    'time_ago' => $notif->time_ago,
                    'action_url' => $notif->resolved_action_url,
                    'is_read' => $notif->is_read,
                ];
            }),
        ]);
    }

    /**
     * Tandai notifikasi sebagai dibaca
     */
    public function markAsRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi ditandai sebagai dibaca',
                'unread_count' => AdminNotification::unread()->count(),
            ]);
        }

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca');
    }

    /**
     * Tandai semua notifikasi sebagai dibaca
     */
    public function markAllAsRead()
    {
        AdminNotification::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi ditandai sebagai dibaca',
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca');
    }

    /**
     * Tandai notifikasi sebagai belum dibaca
     */
    public function markAsUnread($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->markAsUnread();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi ditandai sebagai belum dibaca',
                'unread_count' => AdminNotification::unread()->count(),
            ]);
        }

        return back()->with('success', 'Notifikasi ditandai sebagai belum dibaca');
    }

    /**
     * Hapus notifikasi
     */
    public function destroy($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi dihapus',
                'unread_count' => AdminNotification::unread()->count(),
            ]);
        }

        return back()->with('success', 'Notifikasi dihapus');
    }

    /**
     * Hapus semua notifikasi
     */
    public function destroyAll()
    {
        AdminNotification::truncate();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi dihapus',
            ]);
        }

        return back()->with('success', 'Semua notifikasi dihapus');
    }

    /**
     * Export log aktivitas (CSV)
     */
    public function export(Request $request)
    {
        $category = $request->query('category', 'semua');
        $query = AdminNotification::latest();

        if ($category && $category !== 'semua') {
            $query->where('category', $category);
        }

        $notifications = $query->get();

        $filename = 'activity-log-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($notifications) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for UTF-8

            // Header
            fputcsv($file, [
                'Waktu',
                'Judul',
                'Pesan',
                'Kategori',
                'Prioritas',
                'Status Dibaca',
            ]);

            // Data
            foreach ($notifications as $notif) {
                fputcsv($file, [
                    $notif->created_at->format('Y-m-d H:i:s'),
                    $notif->title,
                    $notif->message,
                    $notif->category,
                    $notif->priority,
                    $notif->is_read ? 'Dibaca' : 'Belum Dibaca',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get statistik untuk dashboard
     */
    public function stats()
    {
        $stats = [
            'total_unread' => AdminNotification::unread()->count(),
            'high_priority_unread' => AdminNotification::where('priority', 'high')->unread()->count(),
            'today_count' => AdminNotification::whereDate('created_at', today())->count(),
            'by_category' => [
                'kampanye' => AdminNotification::where('category', 'kampanye')->count(),
                'donasi' => AdminNotification::where('category', 'donasi')->count(),
                'relawan' => AdminNotification::where('category', 'relawan')->count(),
                'laporan' => AdminNotification::where('category', 'laporan')->count(),
                'smart_matching' => AdminNotification::where('category', 'smart_matching')->count(),
                'sistem' => AdminNotification::where('category', 'sistem')->count(),
            ],
            'by_priority' => [
                'high' => AdminNotification::where('priority', 'high')->count(),
                'medium' => AdminNotification::where('priority', 'medium')->count(),
                'low' => AdminNotification::where('priority', 'low')->count(),
            ],
        ];

        return response()->json($stats);
    }

    /**
     * Activity feed untuk dashboard widget
     */
    public function activityFeed()
    {
        $feed = AdminNotification::latest()
            ->limit(10)
            ->get()
            ->map(function ($notif) {
                return [
                    'time' => $notif->created_at->format('H:i'),
                    'title' => $notif->title,
                    'icon' => $notif->icon,
                    'category' => $notif->category,
                ];
            });

        return response()->json($feed);
    }
}

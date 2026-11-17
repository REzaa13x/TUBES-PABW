<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\VolunteerCampaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    public function message(Request $request): JsonResponse
    {
        if (is_string($request->input('message'))) {
            $request->merge(['message' => trim($request->input('message'))]);
        }
        $input = $request->validate(['message' => 'required|string|max:1000']);
        $text = mb_strtolower($input['message']);
        $data = ['donations' => [], 'volunteers' => []];
        $reply = null;
        if (preg_match('/\b(halo|hai|hello|selamat pagi|selamat siang|selamat malam)\b/u', $text)
            && !preg_match('/donasi|relawan|kampanye/u', $text)) {
            $reply = 'Halo! Saya bisa membantu mencari kampanye donasi, kegiatan relawan, serta menjelaskan pendaftaran, pembayaran, dan sertifikat. Apa yang ingin Anda cari?';
        } elseif (str_contains($text, 'sertifikat')) {
            $reply = 'Buka Profil > Sertifikat Saya untuk melihat sertifikat kegiatan Anda. Sertifikat diterbitkan untuk pendaftaran relawan yang disetujui. Unduhan tersedia jika status sertifikat aktif; status pending masih menunggu aktivasi. Jika kegiatan belum muncul, hubungi admin DonGiv.';
        } elseif (preg_match('/\b(cara|bagaimana|panduan|gimana)\b/u', $text) || str_contains($text, 'daftar')) {
            if (str_contains($text, 'relawan')) {
                $reply = 'Untuk mendaftar relawan: login, buka kegiatan relawan, pilih Daftar, isi posisi, alamat, dan motivasi, lalu unggah CV PDF maksimal 2 MB. Tekan Kirim Pendaftaran. Persetujuan dapat dilihat di Riwayat Relawan.';
            } elseif (preg_match('/kampanye|galang/u', $text)) {
                $reply = 'Untuk membuat kampanye: buka Mulai Kampanye, lengkapi data penerima dan kampanye, unggah foto, pilih lokasi di peta, lalu kirim. Pengajuan akan menunggu verifikasi admin dan dapat dipantau melalui riwayat kampanye.';
            } elseif (preg_match('/donasi|bayar|pembayaran/u', $text)) {
                $reply = 'Buka kampanye donasi, tekan Donasi, isi nominal dan data yang diminta, lalu ikuti instruksi pembayaran. Periksa Riwayat Donasi untuk melihat status transaksi. Saya tidak dapat mengonfirmasi pembayaran atau mengubah status transaksi.';
            } else {
                $reply = 'Silakan jelaskan kebutuhan Anda, misalnya cara donasi, cara daftar relawan, membuat kampanye, atau melihat sertifikat.';
            }
        } elseif (preg_match('/\b(bayar|pembayaran|transaksi|refund|status donasi)\b/u', $text)) {
            $reply = 'Status pembayaran pribadi tersedia di Profil > Riwayat Donasi. Buka transaksi terkait untuk melihat detailnya. Untuk kendala pembayaran atau pengembalian dana, hubungi admin dengan nomor transaksi. Jangan kirim password atau kode OTP di chat.';
        }

        if ($reply === null) {
            $volunteer = preg_match('/relawan|volunteer/u', $text) === 1;
            $donation = preg_match('/donasi|donation/u', $text) === 1;
            $categories = [
                'Pendidikan' => '/pendidikan|sekolah|belajar|pengajar|edukasi/u',
                'Bencana' => '/bencana|banjir|gempa|longsor/u',
                'Kesehatan' => '/kesehatan|medis|sakit|obat/u',
                'Lingkungan' => '/lingkungan|sampah|pohon|pantai/u',
                'Sosial' => '/sosial|pangan|makanan|yatim/u',
            ];
            $category = null;
            foreach ($categories as $name => $pattern) {
                if (preg_match($pattern, $text)) { $category = $name; break; }
            }
            $stop = ['saya','aku','ingin','mau','tolong','carikan','cari','mencari','rekomendasi','kegiatan','aksi','relawan','volunteer','donasi','donation','kampanye','program','bantuan','bantu','di','untuk','yang','dan','atau','ada','apa','saja','dong','mendesak','terbaru'];
            $tokens = array_values(array_filter(preg_split('/[^\pL\pN]+/u', $text),
                fn($word) => mb_strlen($word) >= 3 && !in_array($word, $stop, true)));
            $tokens = array_slice(array_unique($tokens), 0, 8);
            $location = null;
            if (preg_match('/\bdi\s+([\pL]+(?:\s+[\pL]+)?)/u', $text, $matches)) {
                $location = trim($matches[1]);
            }
            $applyFilters = function ($query, array $columns) use ($category, $tokens, $location) {
                if ($category) $query->where('kategori', $category);
                if ($location) $query->where('lokasi', 'like', '%'.$location.'%');
                if (!$category && $tokens) {
                    $query->where(function ($nested) use ($tokens, $columns) {
                        foreach ($tokens as $token) {
                            foreach ($columns as $column) $nested->orWhere($column, 'like', '%'.$token.'%');
                        }
                    });
                }
            };
            if (!$volunteer || $donation) {
                $query = Campaign::where('status', 'verified')
                    ->whereDate('end_date', '>=', today())
                    ->whereColumn('current_amount', '<', 'target_amount');
                $applyFilters($query, ['title', 'description', 'kategori', 'lokasi']);
                $data['donations'] = $query->orderBy('end_date')->limit(3)->get()->map(fn($c) => [
                    'id' => $c->id, 'type' => 'donation', 'title' => $c->title,
                    'category' => $c->kategori, 'image_url' => $c->image, 'location' => $c->lokasi,
                    'target_amount' => (float) $c->target_amount, 'current_amount' => (float) $c->current_amount,
                    'yayasan' => $c->yayasan,
                    'progress' => $c->target_amount > 0 ? min(100, 100 * $c->current_amount / $c->target_amount) : 0,
                ])->all();
            }
            if ($volunteer || !$donation) {
                $query = VolunteerCampaign::where('status', 'Aktif')
                    ->whereDate('tanggal_selesai', '>=', today())->whereColumn('kuota_terisi', '<', 'kuota_total');
                $applyFilters($query, ['judul', 'deskripsi', 'kategori', 'lokasi']);
                $data['volunteers'] = $query->orderBy('tanggal_mulai')->limit(3)->get()->map(fn($c) => [
                    'id' => $c->id, 'type' => 'volunteer', 'title' => $c->judul,
                    'category' => $c->kategori, 'image_url' => $c->image, 'location' => $c->lokasi,
                    'max_volunteers' => (int) $c->kuota_total, 'registered_volunteers' => (int) $c->kuota_terisi,
                    'start_date' => $c->tanggal_mulai, 'end_date' => $c->tanggal_selesai,
                    'progress' => $c->kuota_total > 0 ? min(100, 100 * $c->kuota_terisi / $c->kuota_total) : 0,
                ])->all();
            }
            $count = count($data['donations']) + count($data['volunteers']);
            $reply = $count > 0
                ? "Saya menemukan {$count} rekomendasi yang masih tersedia dari database DonGiv. Pilih Buka Detail untuk melihat informasi lengkap dan melanjutkan."
                : 'Belum ada kampanye atau kegiatan aktif yang cocok dengan permintaan Anda. Coba kategori Pendidikan, Bencana, Kesehatan, Lingkungan, Sosial, atau lokasi lain. Saya juga bisa menjelaskan cara donasi dan pendaftaran relawan.';
        }
        return response()->json(['reply' => $reply, 'used_ai' => false, 'data' => $data]);
    }
}

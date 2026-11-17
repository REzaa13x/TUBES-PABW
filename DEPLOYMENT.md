# Deploy Laravel

Upload kode terbaru, termasuk seluruh folder `database/migrations`.
Pastikan konfigurasi database hosting mengarah ke database aplikasi yang benar.

Jika `SESSION_DRIVER=database` atau `CACHE_STORE=database`, aktifkan
**Run Database Migrations / Jalankan Migrasi Basis Data** di langkah deploy.
Jalankan perintah berikut di terminal hosting dari direktori aplikasi apabila
hosting tidak menyediakan langkah migrasi otomatis:

```sh
php artisan config:clear
php artisan migrate --force
php artisan migrate:status
php artisan config:cache
```

Semua migrasi harus berstatus `Ran`. Jika migrasi gagal, perbaiki error pertama
di output sebelum melanjutkan deploy. Jangan gunakan `migrate:fresh` pada
database hosting yang berisi data karena perintah itu menghapus seluruh tabel.

Migrasi `2026_11_17_000003_ensure_sessions_table_exists` memulihkan tabel
`sessions` yang hilang tanpa menghapus session yang sudah ada. Jika error
`sessions doesn't exist` masih muncul setelah migrasi berhasil, periksa
`DB_DATABASE`, `SESSION_CONNECTION`, dan `SESSION_TABLE` di environment hosting.
Konfigurasi khusus session harus mengarah ke koneksi dan tabel yang tersedia.

Pastikan `storage` dan `bootstrap/cache` dapat ditulis oleh proses PHP.
Periksa `storage/logs/laravel.log` untuk error berikutnya jika situs masih 500.

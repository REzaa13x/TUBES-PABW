<section class="my-5 rounded-lg border border-slate-200 p-4">
    <h2 class="font-bold mb-3">Penerima dan Lokasi Bantuan</h2>
    <p><strong>Penerima:</strong> {{ $campaign->yayasan ?? '-' }} ({{ $campaign->jenis_penerima ?? '-' }})</p>
    @if($campaign->recipient_description)
        <p class="mt-2 whitespace-pre-line">{{ $campaign->recipient_description }}</p>
    @endif
    <p class="mt-2"><strong>Alamat:</strong> {{ $campaign->alamat_lengkap ?: $campaign->lokasi }}</p>
    <p>{{ $campaign->kecamatan }} {{ $campaign->kabupaten }} {{ $campaign->provinsi }} {{ $campaign->kode_pos }}</p>
    <p><strong>WhatsApp:</strong> {{ $campaign->whatsapp ?? '-' }}</p>
    <p><strong>Penyaluran:</strong> {{ $campaign->penyaluran ?? '-' }}</p>
    @if($campaign->latitude !== null && $campaign->longitude !== null)
        <p class="mt-2"><strong>Koordinat:</strong> {{ $campaign->latitude }}, {{ $campaign->longitude }}</p>
        <a class="text-blue-600 underline" target="_blank" rel="noopener noreferrer"
           href="https://www.openstreetmap.org/?{{ http_build_query(['mlat' => $campaign->latitude, 'mlon' => $campaign->longitude]) }}#map=17/{{ $campaign->latitude }}/{{ $campaign->longitude }}">Lihat titik bantuan di peta</a>
    @endif
</section>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kampanye - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin_notifications.css') }}">
</head>
<body>
    @extends('admin.layouts.master')

    @section('content')
    <div class="admin-campaign-detail container mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-4">{{ $campaign->title }}</h1>
        <p class="mb-2"><strong>Deskripsi:</strong> {{ $campaign->description }}</p>
        @include('admin.campaigns.submission-details')
        <p class="mb-2"><strong>Kategori:</strong> {{ $campaign->kategori }}</p>
        <p class="mb-2"><strong>Status:</strong> {{ ucfirst($campaign->status) }}</p>
        <p class="mb-2"><strong>Target Dana:</strong> Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
        <p class="mb-2"><strong> Dana Terkumpul:</strong> Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
        <p class="mb-2"><strong>End Date:</strong> {{ $campaign->end_date->format('d M Y') }}</p>
        <p class="mb-2"><strong>Pembuat:</strong> {{ $campaign->user->name ?? 'Admin' }}</p>
        @if($campaign->image)
            <div class="mt-4">
                <img src="{{ $campaign->image }}" alt="Gambar Kampanye" class="max-w-full h-auto rounded">
            </div>
        @endif
        <div class="mt-6">
            <a href="{{ route('admin.campaigns.index') }}" class="btn-primary">← Kembali ke Daftar</a>
            <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="btn-primary ml-4">Edit Kampanye</a>
        </div>
    </div>
    @endsection
</body>
</html>

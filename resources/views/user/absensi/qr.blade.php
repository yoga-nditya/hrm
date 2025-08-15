@extends('layouts.app')
@section('title', 'QR Presensi Magang')

@section('content')
<div class="container py-5 text-center">
    <h3 class="mb-3">QR Code Presensi Magang</h3>

    @if(!$hasMasukToday)
        <p class="mb-3">Silakan scan QR <strong>Masuk</strong> saat datang.</p>
        <div class="d-flex justify-content-center">
            <div class="card shadow-sm" style="max-width: 320px;">
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="badge badge-success mb-2" style="font-size: 0.9rem;">MASUK</div>
                    {!! QrCode::size(240)->generate($qrMasuk) !!}
                </div>
            </div>
        </div>
        <small class="text-muted d-block mt-3">Nama: {{ $application->user->name }} • Posisi: {{ $application->lowongan->posisi ?? '-' }}</small>
        <small class="text-muted">UUID: {{ $uuid }}</small>

        <div class="mt-4">
            <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">Refresh</a>
        </div>
    @else
        <p class="mb-3">Silakan scan QR <strong>Keluar</strong> saat pulang.</p>
        <div class="d-flex justify-content-center">
            <div class="card shadow-sm" style="max-width: 320px;">
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="badge badge-dark mb-2" style="font-size: 0.9rem;">KELUAR</div>
                    {!! QrCode::size(240)->generate($qrKeluar) !!}
                </div>
            </div>
        </div>
        <small class="text-muted d-block mt-3">Nama: {{ $application->user->name }} • Posisi: {{ $application->lowongan->posisi ?? '-' }}</small>
        <small class="text-muted">UUID: {{ $uuid }}</small>

        <div class="mt-4">
            <a href="{{ route('user.absensi.index') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Riwayat</a>
        </div>
    @endif
</div>
@endsection

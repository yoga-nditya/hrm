{{-- resources/views/user/absensi/qr.blade.php --}}
@extends('layouts.app')
@section('title', 'QR Absensi Magang')

@section('content')
<div class="container py-5 text-center">
    <h3 class="mb-3">QR Code Absensi Magang</h3>
    <p>Perlihatkan QR ini ke admin untuk di-scan setiap hari.</p>
    <div class="my-4 d-flex flex-column align-items-center">
        {{-- Pastikan Anda sudah install "simplesoftwareio/simple-qrcode" --}}
        {!! QrCode::size(250)->generate($qrData) !!}
        <div class="mt-3">
            <strong>{{ $application->user->name }}</strong> <br>
            <span>{{ $application->lowongan->posisi ?? '-' }}</span> <br>
            <small class="text-muted">UUID: {{ $qrData }}</small>
        </div>
    </div>

    <a href="{{ route('user.absensi.index') }}" class="btn btn-outline-secondary">Kembali ke Riwayat</a>
</div>
@endsection

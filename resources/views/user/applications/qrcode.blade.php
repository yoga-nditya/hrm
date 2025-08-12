@extends('layouts.app')

@section('title', 'QR Code Lolos Tes')

@section('content')
<div class="container py-5 text-center">
    <h3 class="mb-3 text-success">Selamat, Anda Lolos!</h3>
    <p class="mb-2">Tunjukkan QR Code ini saat tes/wawancara:</p>
    <div class="my-4 d-flex flex-column align-items-center">
        {!! QrCode::size(250)->generate($qrData) !!}
        <div class="mt-3">
            <strong>{{ $application->user->name }}</strong> <br>
            <span>{{ $application->lowongan->posisi ?? '-' }}</span>
        </div>
    </div>
    <p class="text-muted">Scan QR code di bawah ini untuk membuka menu "Lamaran Saya" pada aplikasi HRM ini.</p>
    <a href="{{ route('user.applications') }}" class="btn btn-outline-primary mt-3">Menu Lamaran Saya</a>
</div>
@endsection

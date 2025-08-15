@extends('layouts.app')
@section('title', 'Presensi Magang')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Presensi Magang</h3>

    @if(!$application)
        <div class="alert alert-warning">Anda belum diterima magang atau belum ada lamaran diterima.</div>
    @else
        <div class="mb-4">
            <a href="{{ route('user.absensi.qr') }}" class="btn btn-outline-success">
                <i class="fas fa-qrcode"></i> Tampilkan QR Presensi
            </a>
        </div>

        <h5>Riwayat Absensi</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead><tr>
                    <th style="white-space:nowrap;">Tanggal</th>
                    <th style="white-space:nowrap;">Jam Masuk</th>
                    <th style="white-space:nowrap;">Jam Keluar</th>
                </tr></thead>
                <tbody>
                    @forelse($rekap as $r)
                        <tr>
                            <td>{{ $r['tanggal'] }}</td>
                            <td>{{ $r['jam_masuk'] ?? '-' }}</td>
                            <td>{{ $r['jam_keluar'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">Belum ada absensi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

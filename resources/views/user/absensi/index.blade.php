@extends('layouts.app')
@section('title', 'Absensi Magang')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Absensi Magang</h3>

    @if(!$application)
        <div class="alert alert-warning">Anda belum diterima magang atau belum ada lamaran diterima.</div>
    @else
        <div class="mb-4">
            <a href="{{ route('user.absensi.qr') }}" class="btn btn-outline-success">
                <i class="fas fa-qrcode"></i> Tampilkan QR Absensi
            </a>
        </div>
        <h5>Riwayat Absensi</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead><tr>
                    <th>Tanggal</th><th>Waktu</th><th>Keterangan</th>
                </tr></thead>
                <tbody>
                    @forelse($absensi as $a)
                        <tr>
                            <td>{{ $a->tanggal }}</td>
                            <td>{{ $a->waktu }}</td>
                            <td>{{ $a->keterangan }}</td>
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

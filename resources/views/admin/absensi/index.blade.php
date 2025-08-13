{{-- resources/views/admin/absensi/index.blade.php --}}
@extends('admin.master')
@section('title', 'Presensi Magang')
@section('page-title', 'Rekap Presensi Magang')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <form method="GET" class="form-inline">
            <input type="text" name="q" class="form-control" placeholder="Cari nama peserta" value="{{ $keyword }}">
            <button class="btn btn-primary ml-2">Cari</button>
        </form>
        <a href="{{ route('admin.absensi.scan') }}" class="btn btn-success">Buka Scanner</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Posisi</th>
                    <th>Jam</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensi as $a)
                <tr>
                    <td>{{ $a->tanggal }}</td>
                    <td>{{ $a->application->user->name ?? '-' }}</td>
                    <td>{{ $a->application->lowongan->posisi ?? '-' }}</td>
                    <td>{{ $a->waktu }}</td>
                    <td>{{ $a->keterangan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $absensi->links() }}
        </div>
    </div>
</div>
@endsection

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
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groups as $g)
                    @php
                        $app = $apps[$g->application_uuid] ?? null;
                        $nama = $app->user->name ?? '-';
                        $pos  = $app->lowongan->posisi ?? '-';
                    @endphp
                    <tr>
                        <td>{{ $g->tanggal }}</td>
                        <td>{{ $nama }}</td>
                        <td>{{ $pos }}</td>
                        <td>{{ $g->jam_masuk ?? '-' }}</td>
                        <td>{{ $g->jam_keluar ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $groups->appends(['q' => $keyword])->links() }}
        </div>
    </div>
</div>
@endsection

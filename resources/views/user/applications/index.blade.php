@extends('layouts.app')

@section('title', 'Lamaran Saya')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold mb-0">Lamaran Saya</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($applications->isEmpty())
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-file-alt fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">Belum ada lamaran</h4>
                        <p class="text-muted">Anda belum mengajukan lamaran pekerjaan</p>
                        <a href="{{ route('lowongan.index') }}" class="btn btn-danger mt-3">
                            <i class="fas fa-briefcase me-2"></i>Cari Lowongan
                        </a>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Posisi</th>
                                        <th>Perusahaan</th>
                                        <th>Tanggal Lamar</th>
                                        <th>Status</th>
                                        {{-- <th>Aksi</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                        <tr>
                                            <td>
                                                <strong>{{ $application->lowongan->posisi ?? 'N/A' }}</strong>
                                            </td>
                                            <td>Lotte Mart Indonesia</td>
                                            <td>{{ $application->created_at->format('d M Y') }}</td>
                                            <td>
                                                @if($application->status == 'approved')
                                                    <span class="badge bg-success">Diterima</span>
                                                @elseif($application->status == 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Dalam Proses</span>
                                                @endif
                                            </td>
                                            {{-- <td>
                                                <a href="{{ route('lowongan.show', $application->lowongan->uuid ?? '#') }}"
                                                   class="btn btn-sm btn-outline-primary mb-1">
                                                    <i class="fas fa-eye me-1"></i>Detail
                                                </a>
                                                @if($application->status == 'approved')
                                                <a href="{{ route('user.application.qrcode', $application->uuid) }}"
                                                   class="btn btn-sm btn-success mb-1" target="_blank">
                                                    <i class="fas fa-qrcode me-1"></i>QR Code
                                                </a>
                                                @endif
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $applications->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

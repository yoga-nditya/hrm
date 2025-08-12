@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <!-- Profile Completion Alert -->
    @if(!$isProfileComplete)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Profil Belum Lengkap!</strong><br>
                    Silakan lengkapi profil Anda untuk meningkatkan peluang diterima dalam lamaran kerja.
                </div>
                <a href="{{ route('profile.show') }}" class="btn btn-warning btn-sm ms-auto me-2">
                    <i class="fas fa-user-edit me-1"></i>Lengkapi Profil
                </a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Welcome Section -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="card-title mb-2">
                                <i class="fas fa-user-circle text-primary me-2"></i>
                                Selamat datang, {{ auth()->user()->name }}!
                            </h2>
                            <p class="card-text text-muted">
                                Status terakhir Anda:
                                @if($myApplications->count() > 0)
                                    @if($myApplications->first()->status == 'approved')
                                        <span class="text-success">Lamaran diterima untuk posisi {{ $myApplications->first()->lowongan->posisi ?? '' }}</span>
                                    @elseif($myApplications->first()->status == 'rejected')
                                        <span class="text-danger">Lamaran ditolak untuk posisi {{ $myApplications->first()->lowongan->posisi ?? '' }}</span>
                                    @else
                                        <span class="text-warning">Menunggu konfirmasi untuk posisi {{ $myApplications->first()->lowongan->posisi ?? '' }}</span>
                                    @endif
                                @else
                                    <span>Anda belum memiliki lamaran aktif</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex gap-2 justify-content-md-end">
                                <a href="{{ route('lowongan.index') }}" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Cari Lowongan
                                </a>
                                <a href="{{ route('user.applications') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-1"></i>Riwayat Lamaran
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="col-12 mb-4">
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ $applicationStats['total'] }}</h6>
                                    <p class="text-muted mb-0 small">Total Lamaran</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ $applicationStats['pending'] }}</h6>
                                    <p class="text-muted mb-0 small">Menunggu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ $applicationStats['approved'] }}</h6>
                                    <p class="text-muted mb-0 small">Diterima</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ $applicationStats['rejected'] }}</h6>
                                    <p class="text-muted mb-0 small">Ditolak</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Recent Applications -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt text-primary me-2"></i>
                        Lamaran Terbaru
                    </h5>
                    <a href="{{ route('user.applications') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @forelse($myApplications->take(5) as $application)
                        <div class="p-3 border-bottom hover-bg">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <div class="application-status-badge me-3">
                                            @if($application->status == 'pending')
                                                <span class="badge bg-warning rounded-pill">Menunggu</span>
                                            @elseif($application->status == 'approved')
                                                <span class="badge bg-success rounded-pill">Diterima</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill">Ditolak</span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $application->lowongan->posisi ?? 'Posisi tidak tersedia' }}</h6>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-building me-1"></i>
                                                {{ $application->lowongan->department ?? 'Department tidak tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-end">
                                            <p class="small text-muted mb-1">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $application->created_at->format('d M Y') }}
                                            </p>
                                            <p class="small text-muted mb-0">
                                                {{ $application->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted">Belum ada lamaran</h5>
                            <p class="text-muted">Anda belum mengajukan lamaran untuk lowongan apapun.</p>
                            <a href="{{ route('lowongan.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Cari Lowongan
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Job Recommendations -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-star text-warning me-2"></i>
                        Rekomendasi Lowongan Untuk Anda
                    </h5>
                    <a href="{{ route('lowongan.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($lowongan as $job)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 job-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-2">{{ $job->posisi }}</h6>
                                            @if($myApplications->contains('posisi', $job->posisi))
                                                <span class="badge bg-info">Sudah Dilamar</span>
                                            @endif
                                        </div>
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-building me-1"></i>{{ $job->department }}
                                        </p>
                                        <div class="mb-3">
                                            <span class="badge bg-light text-dark border">{{ $job->jenis_pekerjaan }}</span>
                                            @if($job->salary_min && $job->salary_max)
                                                <span class="badge bg-light text-dark border">
                                                    IDR {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="small text-muted mb-0">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $job->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <div>
                                                <a href="{{ route('lowongan.show', $job->uuid) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i>Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-4">
                                    <i class="fas fa-briefcase text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted">Tidak ada lowongan tersedia</h5>
                                    <p class="text-muted">Silakan cek kembali nanti untuk lowongan terbaru.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Profile Completion -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-user-check text-primary me-2"></i>
                        Status Profil
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="progress-circle me-3" style="--completion-angle: {{ $isProfileComplete ? 360 : ($profileCompletionPercentage * 3.6) }}deg;">
<                           <div class="progress-text">{{ $profileCompletionPercentage }}%</div>
                        </div>
                        <div>
                            <h6 class="mb-1">Kelengkapan Profil</h6>
                            <p class="text-muted small mb-0">
                                {{ $isProfileComplete ? 'Profil Anda sudah lengkap' : 'Lengkapi profil untuk meningkatkan peluang' }}
                            </p>
                        </div>
                    </div>
                    @if(!$isProfileComplete)
                        <div class="alert alert-warning small">
                            <strong>Perhatian:</strong> Profil Anda belum lengkap. Silakan lengkapi:
                            <ul class="mb-0 mt-2">
                                @if(empty(auth()->user()->phone))
                                    <li>Nomor Telepon</li>
                                @endif
                                @if(empty(auth()->user()->address))
                                    <li>Alamat</li>
                                @endif
                                @if(empty(auth()->user()->date_of_birth))
                                    <li>Tanggal Lahir</li>
                                @endif
                                @if(empty(auth()->user()->gender))
                                    <li>Jenis Kelamin</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                    <a href="{{ route('profile.show') }}" class="btn btn-primary w-100">
                        <i class="fas fa-user-edit me-1"></i>{{ $isProfileComplete ? 'Lihat Profil' : 'Lengkapi Profil' }}
                    </a>
                </div>
            </div>

            <!-- Application Tips -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        Tips Melamar
                    </h6>
                </div>
                <div class="card-body">
                    <div class="tips-list">
                        <div class="tip-item">
                            <div class="tip-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="tip-content">
                                <h6>Lengkapi Profil</h6>
                                <p class="small text-muted mb-0">Perusahaan lebih memilih pelamar dengan profil lengkap.</p>
                            </div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="tip-content">
                                <h6>Siapkan Dokumen</h6>
                                <p class="small text-muted mb-0">Siapkan CV dan portofolio sebelum melamar.</p>
                            </div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="tip-content">
                                <h6>Baca Deskripsi</h6>
                                <p class="small text-muted mb-0">Pastikan Anda memenuhi kualifikasi sebelum melamar.</p>
                            </div>
                        </div>
                        <div class="tip-item mb-0">
                            <div class="tip-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="tip-content">
                                <h6>Pantau Status</h6>
                                <p class="small text-muted mb-0">Periksa secara berkala status lamaran Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt text-danger me-2"></i>
                        Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('lowongan.index') }}" class="btn btn-outline-primary text-start">
                            <i class="fas fa-search me-2"></i>Cari Lowongan
                        </a>
                        <a href="{{ route('user.applications') }}" class="btn btn-outline-secondary text-start">
                            <i class="fas fa-list me-2"></i>Riwayat Lamaran
                        </a>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-success text-start">
                            <i class="fas fa-user-edit me-2"></i>Edit Profil
                        </a>
                        @if($myApplications->where('status', 'pending')->count() > 0)
                            <a href="{{ route('user.applications') }}?status=pending" class="btn btn-outline-warning text-start">
                                <i class="fas fa-clock me-2"></i>
                                {{ $myApplications->where('status', 'pending')->count() }} Lamaran Menunggu
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-bg:hover {
        background-color: #f8f9fa;
    }
    .progress-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: conic-gradient(var(--primary-color) var(--completion-angle), #eee var(--completion-angle));
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .progress-circle::before {
        content: '';
        position: absolute;
        width: 50px;
        height: 50px;
        background: white;
        border-radius: 50%;
    }
    .progress-text {
        position: relative;
        font-weight: bold;
        font-size: 0.9rem;
    }
    .job-card {
        transition: all 0.3s ease;
        border-radius: 8px;
    }
    .job-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .tips-list .tip-item {
        display: flex;
        margin-bottom: 1rem;
    }
    .tips-list .tip-item:last-child {
        margin-bottom: 0;
    }
    .tip-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 1rem;
    }
    .tip-content h6 {
        font-size: 0.9rem;
        margin-bottom: 0.2rem;
    }
    .application-status-badge .badge {
        font-size: 0.7rem;
        padding: 0.35rem 0.6rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-refresh dashboard data every 30 seconds
    setInterval(() => {
        fetch('/dashboard-stats')
            .then(response => response.json())
            .then(data => {
                // Update statistics if needed
                console.log('Dashboard stats updated:', data);
            })
            .catch(error => console.error('Error updating dashboard:', error));
    }, 30000);

    // Highlight new applications since last visit
    document.addEventListener('DOMContentLoaded', function() {
        const lastVisit = localStorage.getItem('lastDashboardVisit');
        const now = new Date().toISOString();

        if (lastVisit) {
            document.querySelectorAll('.application-item').forEach(item => {
                const appDate = item.getAttribute('data-created-at');
                if (appDate > lastVisit) {
                    item.classList.add('new-application');
                }
            });
        }

        localStorage.setItem('lastDashboardVisit', now);
    });
</script>
@endpush

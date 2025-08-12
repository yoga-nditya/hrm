@extends('layouts.app')

@section('title', 'Browse Job Opportunities')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="mb-5">
        <h2 class="fw-bold">
            <i class="fas fa-briefcase me-2"></i>Job Opportunities
        </h2>
        <p class="text-muted"><i class="fas fa-search me-1"></i>Find your perfect job match</p>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('lowongan.index') }}">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search by position, company..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                        <select class="form-select" name="jenis_pekerjaan">
                            <option value="">All Job Types</option>
                            <option value="Full-time" {{ request('jenis_pekerjaan') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="Part-time" {{ request('jenis_pekerjaan') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="Contract" {{ request('jenis_pekerjaan') == 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Internship" {{ request('jenis_pekerjaan') == 'Internship' ? 'selected' : '' }}>Internship</option>
                            <option value="Remote" {{ request('jenis_pekerjaan') == 'Remote' ? 'selected' : '' }}>Remote</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <select class="form-select" name="lokasi">
                            <option value="">All Locations</option>
                            <option value="Jakarta" {{ request('lokasi') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                            <option value="Bandung" {{ request('lokasi') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                            <option value="Surabaya" {{ request('lokasi') == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                            <option value="Yogyakarta" {{ request('lokasi') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                            <option value="Bali" {{ request('lokasi') == 'Bali' ? 'selected' : '' }}>Bali</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1 d-grid">
                    <button class="btn btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Job Listings -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        {{ $lowongan->total() }} Jobs Available
                    </h5>
                    <div>
                        <small class="text-muted me-2">Sorted by: Newest</small>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($lowongan as $job)
                    <div class="job-item border-bottom pb-3 mb-3">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="mb-1">
                                    <a href="{{ route('lowongan.show', $job->uuid) }}" class="text-decoration-none">
                                        {{ $job->posisi }}
                                    </a>
                                </h5>
                                <p class="mb-1 text-muted">
                                    <span class="badge bg-success">
                                    <i class="fas fa-building me-1"></i>{{ $job->department }}
                                    </span>
                                </p>
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="badge bg-primary">
                                        <i class="fas fa-briefcase me-1"></i>{{ $job->jenis_pekerjaan }}
                                    </span>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-user-tie me-1"></i>{{ $job->role_pekerjaan }}
                                    </span>
                                    <span class="badge bg-success">
                                        <i class="fas fa-user-tie me-1"></i>{{ $job->experience_level }}
                                    </span>
                                    @if($job->lokasi)
                                    @endif
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>Posted: {{ $job->created_at->diffForHumans() }}
                                </small>
                            </div>
<div class="col-md-4 text-md-end">
    @if($job->salary_min && $job->salary_max)
    <div class="mb-2">
        <h6 class="text-success fw-bold">
            <i class="fas fa-money-bill-wave me-1"></i>
            Rp{{ number_format($job->salary_min, 0, ',', '.') }} - Rp{{ number_format($job->salary_max, 0, ',', '.') }}
        </h6>
    </div>
    @endif
    <a href="{{ route('lowongan.show', $job->uuid) }}" class="btn btn-sm btn-outline-primary me-1">
        <i class="fas fa-eye me-1"></i>View
    </a>
    @if(auth()->check())
        @if(in_array($job->posisi, $appliedJobs))
            <button class="btn btn-sm btn-success" disabled>
                <i class="fas fa-check me-1"></i>Applied
            </button>
        @else
            <form method="POST" action="{{ route('lowongan.apply', $job->uuid) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-paper-plane me-1"></i>Apply
                </button>
            </form>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-sign-in-alt me-1"></i>Login to Apply
        </a>
    @endif
</div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No job listings found</h5>
                        <p class="text-muted">Try adjusting your search filters</p>
                        <a href="{{ route('lowongan.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-sync me-1"></i>Reset Filters
                        </a>
                    </div>
                    @endforelse

                    <!-- Pagination -->
                    @if($lowongan->hasPages())
                    <div class="mt-4">
                        {{ $lowongan->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

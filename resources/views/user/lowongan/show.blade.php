@extends('layouts.app')

@section('title', $lowongan->posisi . ' at ' . ($lowongan->perusahaan ?? 'Company'))

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Main Job Content -->
        <div class="col-lg-8">
            <!-- Job Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="fw-bold mb-1">{{ $lowongan->posisi }}</h2>
                            <h4 class="text-muted mb-3">
                                <i class="fas fa-building me-2"></i>{{ $lowongan->department}}
                            </h4>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary">
                                    <i class="fas fa-briefcase me-1"></i>{{ $lowongan->jenis_pekerjaan }}
                                </span>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-user-tie me-1"></i>{{ $lowongan->role_pekerjaan }}
                                </span>
                                @if($lowongan->lokasi)
                                <span class="badge bg-info text-dark">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $lowongan->lokasi }}
                                </span>
                                @endif
                            </div>

                            @if($lowongan->salary_min && $lowongan->salary_max)
                            <div class="mb-3">
                                <h5 class="text-success fw-bold">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    Rp{{ number_format($lowongan->salary_min, 0, ',', '.') }} - Rp{{ number_format($lowongan->salary_max, 0, ',', '.') }}
                                </h5>
                            </div>
                            @endif
                        </div>

                        <div>
                            <small class="text-muted d-block">
                                <i class="fas fa-clock me-1"></i>Posted: {{ $lowongan->created_at->diffForHumans() }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-sync me-1"></i>Updated: {{ $lowongan->updated_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>

                    <!-- Apply Button -->
                    <div class="mt-4">
                        @auth
    @php
        $hasApplied = auth()->user()->applications()
            ->where('posisi', $lowongan->posisi)
            ->exists();
    @endphp
    @if($hasApplied)
    <button class="btn btn-success" disabled>
        <i class="fas fa-check-circle me-1"></i>Already Applied
    </button>
    @auth
    <li class="nav-item">
        <a class="nav-link" href="{{ route('user.applications') }}">
            <i class="fas fa-file-alt me-1"></i>My Applications
        </a>
    </li>
@endauth
@else
    <form method="POST" action="{{ route('lowongan.apply', $lowongan->uuid) }}">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane me-1"></i>Apply Now
        </button>
    </form>
@endif
@else
    <a href="{{ route('login') }}" class="btn btn-primary">
        <i class="fas fa-sign-in-alt me-1"></i>Login to Apply
    </a>
@endauth

                    </div>
                </div>
            </div>

            <!-- Job Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Job Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar-alt me-2"></i>Employment Type:</strong>
                                {{ $lowongan->jenis_pekerjaan }}
                            </p>
                            <p class="mb-2">
                                <strong><i class="fas fa-user-tie me-2"></i>Role:</strong>
                                {{ $lowongan->role_pekerjaan }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            @if($lowongan->lokasi)
                            <p class="mb-2">
                                <strong><i class="fas fa-map-marker-alt me-2"></i>Location:</strong>
                                {{ $lowongan->lokasi }}
                            </p>
                            @endif
                            @if($lowongan->experience_level)
                            <p class="mb-2">
                                <strong><i class="fas fa-chart-line me-2"></i>Experience Level:</strong>
                                {{ $lowongan->experience_level }}
                            </p>
                            @endif
                        </div>
                    </div>

                    <h5 class="mb-3"><i class="fas fa-align-left me-2"></i>Job Description</h5>
                    <div class="job-description">
                        {!! nl2br(e($lowongan->deskripsi)) !!}
                    </div>

                    @if($lowongan->requirements)
                    <h5 class="mt-4 mb-3"><i class="fas fa-list-check me-2"></i>Requirements</h5>
                    <div class="job-requirements">
                        {!! nl2br(e($lowongan->requirements)) !!}
                    </div>
                    @endif
                </div>
            </div>

            <!-- How to Apply -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>How to Apply</h5>
                </div>
                <div class="card-body">
                    @auth
                        @if(!$hasApplied)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Click the "Apply Now" button above to submit your application.
                        </div>
                        @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>You've already applied for this position. We'll review your application soon.
                        </div>
                        @endif
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>You need to <a href="{{ route('login') }}" class="alert-link">login</a> to apply for this position.
                    </div>
                    @endauth
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

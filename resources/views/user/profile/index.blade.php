@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>My Profile
                        </h4>
                        <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit Profile
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Left Column - Photo & Contact -->
                        <div class="col-md-4 border-end">
                            <div class="text-center mb-4">
                                @if(isset($userDetail) && $userDetail && $userDetail->foto)
                                    <img src="{{ asset('storage/'.$userDetail->foto) }}"
                                         class="img-thumbnail rounded-circle mb-3"
                                         style="width: 200px; height: 200px; object-fit: cover;"
                                         alt="Profile Photo">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light rounded-circle mx-auto mb-3"
                                         style="width: 200px; height: 200px;">
                                        <i class="fas fa-user fa-4x text-muted"></i>
                                    </div>
                                @endif
                                <h4 class="mb-1">{{ $user->name ?? 'N/A' }}</h4>
                                <p class="text-muted mb-0">{{ $user->email ?? 'N/A' }}</p>
                            </div>

                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h5 class="card-title text-muted mb-3">
                                        <i class="fas fa-id-card me-1"></i> Contact Information
                                    </h5>
                                    <ul class="list-unstyled">
                                        @if(isset($userDetail) && $userDetail && $userDetail->no_telpon)
                                            <li class="mb-2">
                                                <i class="fas fa-phone me-2 text-primary"></i>
                                                {{ $userDetail->no_telpon }}
                                            </li>
                                        @endif
                                        @if(isset($userDetail) && $userDetail && $userDetail->alamat)
                                            <li class="mb-2">
                                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                {{ $userDetail->alamat }}
                                            </li>
                                            @if($userDetail->kota || $userDetail->provinsi || $userDetail->kode_pos)
                                                <li class="mb-2 ps-4">
                                                    {{ $userDetail->kota ?? '' }}@if($userDetail->kota && ($userDetail->provinsi || $userDetail->kode_pos)), @endif
                                                    {{ $userDetail->provinsi ?? '' }}@if($userDetail->provinsi && $userDetail->kode_pos) @endif
                                                    {{ $userDetail->kode_pos ?? '' }}
                                                </li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            @if(isset($userDetail) && $userDetail && $userDetail->CV)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title text-muted mb-3">
                                            <i class="fas fa-file-alt me-1"></i> Curriculum Vitae
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-pdf me-2 text-danger fa-lg"></i>
                                            <a href="{{ asset('storage/'.$userDetail->CV) }}"
                                               target="_blank" class="text-decoration-none">
                                                Download CV
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right Column - Profile Details -->
                        <div class="col-md-8">
                            <!-- Work Experience -->
                            <div class="mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-briefcase me-1"></i> Work Experience
                                </h5>
                                @if(isset($pengalaman) && $pengalaman && $pengalaman->pengalaman_kerja)
                                    <div class="ps-3">
                                        {!! nl2br(e($pengalaman->pengalaman_kerja)) !!}
                                    </div>
                                @else
                                    <div class="alert alert-warning py-2">
                                        <i class="fas fa-exclamation-circle me-2"></i>No work experience added yet
                                    </div>
                                @endif
                            </div>

                            <!-- Education -->
                            <div class="mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-graduation-cap me-1"></i> Education
                                </h5>
                                @if(isset($pengalaman) && $pengalaman && $pengalaman->pendidikan)
                                    <div class="ps-3">
                                        {!! nl2br(e($pengalaman->pendidikan)) !!}
                                    </div>
                                @else
                                    <div class="alert alert-warning py-2">
                                        <i class="fas fa-exclamation-circle me-2"></i>No education information added yet
                                    </div>
                                @endif
                            </div>

                            <!-- Organization Experience -->
                            <div class="mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-users me-1"></i> Organization Experience
                                </h5>
                                @if(isset($pengalaman) && $pengalaman && $pengalaman->pengalaman_organisasi)
                                    <div class="ps-3">
                                        {!! nl2br(e($pengalaman->pengalaman_organisasi)) !!}
                                    </div>
                                @else
                                    <div class="alert alert-warning py-2">
                                        <i class="fas fa-exclamation-circle me-2"></i>No organization experience added yet
                                    </div>
                                @endif
                            </div>

                            <!-- Certifications & Training -->
                            <div class="mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-certificate me-1"></i> Certifications & Training
                                </h5>
                                @if(isset($pengalaman) && $pengalaman && $pengalaman->pengalaman_sertifikasi_pelatihan)
                                    <div class="ps-3">
                                        {!! nl2br(e($pengalaman->pengalaman_sertifikasi_pelatihan)) !!}
                                    </div>
                                @else
                                    <div class="alert alert-warning py-2">
                                        <i class="fas fa-exclamation-circle me-2"></i>No certifications or training added yet
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

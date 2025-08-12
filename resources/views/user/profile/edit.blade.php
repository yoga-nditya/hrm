@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- Main Content - Centered -->
        <div class="col-lg-10 col-xl-8">
            <div class="p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-user-edit me-2 text-primary"></i>Edit Profile
                    </h4>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Terjadi kesalahan!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- Personal Information -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-gradient-primary text-white">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-user me-2"></i>Informasi Pribadi
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $user->name) }}"
                                               required
                                               placeholder="Masukkan nama lengkap">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="no_telpon" class="form-label fw-semibold">No. Telepon</label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('no_telpon') is-invalid @enderror"
                                               id="no_telpon"
                                               name="no_telpon"
                                               value="{{ old('no_telpon', $userDetail->no_telpon ?? '') }}"
                                               placeholder="Contoh: 08123456789">
                                        @error('no_telpon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label fw-semibold">Alamat Lengkap</label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                                  id="alamat"
                                                  name="alamat"
                                                  rows="3"
                                                  placeholder="Masukkan alamat lengkap">{{ old('alamat', $userDetail->alamat ?? '') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="kota" class="form-label fw-semibold">Kota</label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('kota') is-invalid @enderror"
                                               id="kota"
                                               name="kota"
                                               value="{{ old('kota', $userDetail->kota ?? '') }}"
                                               placeholder="Contoh: Jakarta">
                                        @error('kota')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="provinsi" class="form-label fw-semibold">Provinsi</label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('provinsi') is-invalid @enderror"
                                               id="provinsi"
                                               name="provinsi"
                                               value="{{ old('provinsi', $userDetail->provinsi ?? '') }}"
                                               placeholder="Contoh: DKI Jakarta">
                                        @error('provinsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="kode_pos" class="form-label fw-semibold">Kode Pos</label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('kode_pos') is-invalid @enderror"
                                               id="kode_pos"
                                               name="kode_pos"
                                               value="{{ old('kode_pos', $userDetail->kode_pos ?? '') }}"
                                               placeholder="Contoh: 12345">
                                        @error('kode_pos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Files Upload -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-gradient-success text-white">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-upload me-2"></i>Upload File
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="foto" class="form-label fw-semibold">Foto Profil</label>
                                        <input type="file"
                                               class="form-control form-control-lg @error('foto') is-invalid @enderror"
                                               id="foto"
                                               name="foto"
                                               accept="image/jpeg,image/png,image/jpg,image/gif">
                                        @error('foto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Format: JPG, PNG, GIF. Max: 2MB
                                        </div>
                                        @if($userDetail && $userDetail->foto)
                                            <div class="mt-2 p-2 bg-light rounded">
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    File saat ini: {{ basename($userDetail->foto) }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="CV" class="form-label fw-semibold">CV (Curriculum Vitae)</label>
                                        <input type="file"
                                               class="form-control form-control-lg @error('CV') is-invalid @enderror"
                                               id="CV"
                                               name="CV"
                                               accept=".pdf,.doc,.docx">
                                        @error('CV')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Format: PDF, DOC, DOCX. Max: 5MB
                                        </div>
                                        @if($userDetail && $userDetail->CV)
                                            <div class="mt-2 p-2 bg-light rounded">
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    File saat ini: {{ basename($userDetail->CV) }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Experience Information -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-gradient-warning text-white">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-briefcase me-2"></i>Pengalaman & Pendidikan
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pendidikan" class="form-label fw-semibold">Pendidikan Terakhir</label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('pendidikan') is-invalid @enderror"
                                               id="pendidikan"
                                               name="pendidikan"
                                               value="{{ old('pendidikan', $pengalaman->pendidikan ?? '') }}"
                                               placeholder="Contoh: S1 Teknik Informatika - Universitas Indonesia">
                                        @error('pendidikan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pengalaman_kerja" class="form-label fw-semibold">Pengalaman Kerja</label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('pengalaman_kerja') is-invalid @enderror"
                                               id="pengalaman_kerja"
                                               name="pengalaman_kerja"
                                               value="{{ old('pengalaman_kerja', $pengalaman->pengalaman_kerja ?? '') }}"
                                               placeholder="Contoh: Software Developer - PT XYZ (2020-2023)">
                                        @error('pengalaman_kerja')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pengalaman_organisasi" class="form-label fw-semibold">Pengalaman Organisasi</label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('pengalaman_organisasi') is-invalid @enderror"
                                               id="pengalaman_organisasi"
                                               name="pengalaman_organisasi"
                                               value="{{ old('pengalaman_organisasi', $pengalaman->pengalaman_organisasi ?? '') }}"
                                               placeholder="Contoh: Ketua Himpunan Mahasiswa Teknik Informatika">
                                        @error('pengalaman_organisasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pengalaman_sertifikasi_pelatihan" class="form-label fw-semibold">Sertifikasi & Pelatihan</label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('pengalaman_sertifikasi_pelatihan') is-invalid @enderror"
                                               id="pengalaman_sertifikasi_pelatihan"
                                               name="pengalaman_sertifikasi_pelatihan"
                                               value="{{ old('pengalaman_sertifikasi_pelatihan', $pengalaman->pengalaman_sertifikasi_pelatihan ?? '') }}"
                                               placeholder="Contoh: Certified Java Developer (2021)">
                                        @error('pengalaman_sertifikasi_pelatihan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center mb-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5 py-3 shadow" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom gradient backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%) !important;
}

/* Enhanced form styling */
.form-control-lg {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control-lg:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    transform: translateY(-1px);
}

.card {
    border-radius: 15px !important;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.card-header {
    border: none !important;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
}

.form-label {
    color: #495057;
    margin-bottom: 8px;
}

.text-muted {
    font-size: 0.875rem;
}

/* Better spacing */
.card-body {
    background: #fafafa;
}

.mb-3 {
    margin-bottom: 1.5rem !important;
}

/* Loading state for submit button */
.btn-loading {
    pointer-events: none;
    opacity: 0.65;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission dengan loading state
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function() {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        submitBtn.classList.add('btn-loading');
    });

    // Preview foto sebelum upload
    const fotoInput = document.getElementById('foto');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Bisa ditambahkan preview image di sini
                    console.log('Foto selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection

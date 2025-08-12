@extends('admin.master')

@section('title', 'Create New Job - HRM System')
@section('page-title', 'Create New Job')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.lowongan.index') }}">Job Management</a></li>
    <li class="breadcrumb-item active">Create New Job</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Job Information</h3>
            </div>

            <form action="{{ route('admin.lowongan.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        {{-- Posisi --}}
                        <div class="col-md-6 mb-3">
                            <label for="posisi">Position <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('posisi') is-invalid @enderror" 
                                   id="posisi" name="posisi" value="{{ old('posisi') }}" required>
                            @error('posisi')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Jenis Pekerjaan --}}
                        <div class="col-md-6 mb-3">
                            <label for="jenis_pekerjaan">Job Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('jenis_pekerjaan') is-invalid @enderror" 
                                    name="jenis_pekerjaan" required>
                                <option value="">-- Select Job Type --</option>
                                @foreach(['Full Time', 'Part Time', 'Contract', 'Freelance', 'Internship'] as $type)
                                    <option value="{{ $type }}" {{ old('jenis_pekerjaan') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('jenis_pekerjaan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Role Pekerjaan --}}
                        <div class="col-md-6 mb-3">
                            <label for="role_pekerjaan">Job Role <span class="text-danger">*</span></label>
                            <select class="form-control @error('role_pekerjaan') is-invalid @enderror" name="role_pekerjaan" required>
                                <option value="">-- Select Role --</option>
                                @foreach([
                                    'Software Developer', 'Frontend Developer', 'Backend Developer', 'Full Stack Developer',
                                    'Mobile Developer', 'UI/UX Designer', 'Data Analyst', 'Project Manager',
                                    'Quality Assurance', 'DevOps Engineer', 'System Administrator', 'HR Specialist',
                                    'Marketing Specialist', 'Sales Representative', 'Customer Support', 'Other'
                                ] as $role)
                                    <option value="{{ $role }}" {{ old('role_pekerjaan') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                            @error('role_pekerjaan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Department --}}
                        <div class="col-md-6 mb-3">
                            <label for="department">Department</label>
                            <select class="form-control" name="department">
                                <option value="">-- Select Department --</option>
                                @foreach(['IT', 'HR', 'Marketing', 'Sales', 'Finance', 'Operations'] as $dept)
                                    <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Experience Level --}}
                        <div class="col-md-6 mb-3">
                            <label for="experience_level">Experience Level</label>
                            <select class="form-control" name="experience_level">
                                <option value="">-- Select Level --</option>
                                @foreach(['Entry Level', 'Mid Level', 'Senior Level', 'Lead/Manager'] as $level)
                                    <option value="{{ $level }}" {{ old('experience_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Salary --}}
                        <div class="col-md-3 mb-3">
                            <label for="salary_min">Minimum Salary (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="salary_min" class="form-control" value="{{ old('salary_min') }}" min="0">
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="salary_max">Maximum Salary (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="salary_max" class="form-control" value="{{ old('salary_max') }}" min="0">
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="col-md-12 mb-3">
                            <label for="deskripsi">Job Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Include job responsibilities, required skills, qualifications, and more.
                            </small>
                        </div>

                        {{-- Status Aktif --}}
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">Active Job Posting</label>
                            </div>
                            <small class="form-text text-muted">Uncheck if this is a draft.</small>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Create Job</button>
                    <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#deskripsi').on('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        $('input[name="salary_min"], input[name="salary_max"]').on('input', function () {
            var min = parseInt($('input[name="salary_min"]').val()) || 0;
            var max = parseInt($('input[name="salary_max"]').val()) || 0;

            if (min > 0 && max > 0 && min >= max) {
                $(this).addClass('is-invalid');
                if (!$(this).siblings('.invalid-feedback').length) {
                    $(this).after('<div class="invalid-feedback">Maximum salary must be greater than minimum salary.</div>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            }
        });
    });
</script>
@endpush

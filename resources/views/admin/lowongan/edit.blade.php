@extends('admin.master')

@section('title', 'Edit Job - HRM System')
@section('page-title', 'Edit Job')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.lowongan.index') }}">Job Management</a></li>
    <li class="breadcrumb-item active">Edit Job</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Job Position</h3>
                </div>
                <form action="{{ route('admin.lowongan.update', $lowongan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Position Field -->
                        <div class="form-group">
                            <label for="posisi">Position <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('posisi') is-invalid @enderror" 
                                   id="posisi" 
                                   name="posisi" 
                                   value="{{ old('posisi', $lowongan->posisi) }}" 
                                   placeholder="Enter job position"
                                   required>
                            @error('posisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Job Type Field -->
                        <div class="form-group">
                            <label for="jenis_pekerjaan">Job Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('jenis_pekerjaan') is-invalid @enderror" 
                                    id="jenis_pekerjaan" 
                                    name="jenis_pekerjaan" 
                                    required>
                                <option value="">Select Job Type</option>
                                <option value="Full Time" {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan) == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                <option value="Part Time" {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan) == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                <option value="Contract" {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan) == 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Freelance" {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan) == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                <option value="Internship" {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan) == 'Internship' ? 'selected' : '' }}>Internship</option>
                            </select>
                            @error('jenis_pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role Field -->
                        <div class="form-group">
                            <label for="role_pekerjaan">Job Role <span class="text-danger">*</span></label>
                            <select class="form-control @error('role_pekerjaan') is-invalid @enderror" 
                                    id="role_pekerjaan" 
                                    name="role_pekerjaan" 
                                    required>
                                <option value="">Select Job Role</option>
                                <option value="Manager" {{ old('role_pekerjaan', $lowongan->role_pekerjaan) == 'Manager' ? 'selected' : '' }}>Manager</option>
                                <option value="Supervisor" {{ old('role_pekerjaan', $lowongan->role_pekerjaan) == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                                <option value="Senior" {{ old('role_pekerjaan', $lowongan->role_pekerjaan) == 'Senior' ? 'selected' : '' }}>Senior</option>
                                <option value="Junior" {{ old('role_pekerjaan', $lowongan->role_pekerjaan) == 'Junior' ? 'selected' : '' }}>Junior</option>
                                <option value="Staff" {{ old('role_pekerjaan', $lowongan->role_pekerjaan) == 'Staff' ? 'selected' : '' }}>Staff</option>
                                <option value="Intern" {{ old('role_pekerjaan', $lowongan->role_pekerjaan) == 'Intern' ? 'selected' : '' }}>Intern</option>
                            </select>
                            @error('role_pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="form-group">
                            <label for="deskripsi">Job Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" 
                                      name="deskripsi" 
                                      rows="6" 
                                      placeholder="Enter detailed job description, requirements, and responsibilities..."
                                      required>{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Job
                                </button>
                                <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Fields marked with <span class="text-danger">*</span> are required
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $('input[required], select[required], textarea[required]').each(function() {
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
    
    // Remove validation class on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Auto-resize textarea
    $('#deskripsi').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>
@endpush
@extends('admin.master')

@section('title', 'Job Management - HRM System')
@section('page-title', 'Job Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Job Management</li>
@endsection

@section('content')
    <!-- Job Statistics Cards -->
    <div class="row mb-3">
        <!-- (Tetap sama seperti sebelumnya) -->
    </div>

    <!-- Job Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex">
                    <h3 class="card-title my-auto">All Job Positions</h3>
                    <a href="{{ route('admin.lowongan.create') }}" class="btn btn-primary btn-sm ml-auto">
                        <i class="fas fa-plus"></i> Create New Job
                    </a>
                </div>
                <div class="card-body table-responsive">
                    <table id="jobTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Position</th>
                            <th>Job Type</th>
                            <th>Role</th>
                            <th>Experience</th>
                            <th>Department</th>
                            <th>Salary</th>
                            <th>Description</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lowongan as $job)
                        <tr>
                            <td><strong>{{ $job->posisi }}</strong></td>
                            <td><span class="badge badge-info">{{ $job->jenis_pekerjaan }}</span></td>
                            <td>{{ $job->role_pekerjaan }}</td>
                            <td>{{ $job->experience_level ?? '-' }}</td>
                            <td>{{ $job->department ?? '-' }}</td>
                            <td>
                                @if($job->salary_min && $job->salary_max)
                                    Rp{{ number_format($job->salary_min) }} - Rp{{ number_format($job->salary_max) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $job->deskripsi }}">
                                    {{ Str::limit($job->deskripsi, 100) }}
                                </div>
                            </td>
                            <td>{{ $job->created_at->format('M d, Y') }}</td>
                            <td>
    <div class="btn-group" role="group">
        <a href="{{ route('admin.lowongan.edit', $job->uuid) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-danger btn-sm" onclick="deleteJob('{{ $job->uuid }}')">
            <i class="fas fa-trash"></i>
        </button>
        <form id="delete-form-{{ $job->uuid }}" action="{{ route('admin.lowongan.destroy', $job->uuid) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#jobTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#jobTable_wrapper .col-md-6:eq(0)');
});

function deleteJob(jobUuid) {
    if (confirm('Are you sure you want to delete this job?')) {
        document.getElementById('delete-form-' + jobUuid).submit();
    }
}
</script>
@endpush

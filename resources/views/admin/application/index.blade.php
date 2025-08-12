@extends('admin.master')

@section('title', 'Application Management - HRM System')
@section('page-title', 'Application Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Applications</li>
@endsection

@section('content')
    <!-- Filter Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $applications->where('status', 'pending')->count() }}</h3>
                    <p>Pending Applications</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $applications->where('status', 'approved')->count() }}</h3>
                    <p>Approved Applications</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $applications->where('status', 'rejected')->count() }}</h3>
                    <p>Rejected Applications</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $applications->count() }}</h3>
                    <p>Total Applications</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Job Applications</h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-filter"></i> Filter by Status
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="?status=all">All Applications</a>
                                <a class="dropdown-item" href="?status=pending">Pending</a>
                                <a class="dropdown-item" href="?status=approved">Approved</a>
                                <a class="dropdown-item" href="?status=rejected">Rejected</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="applicationsTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Position</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($applications as $application)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $application->user->userDetail && $application->user->userDetail->foto ? 
                                               asset('storage/' . $application->user->userDetail->foto) : 
                                               'https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg' }}" 
                                         alt="User Avatar" class="img-circle mr-2" style="width: 40px; height: 40px;">
                                    <div>
                                        <strong>{{ $application->user->name ?? 'Unknown User' }}</strong>
                                        @if($application->user->userDetail)
                                            <br><small class="text-muted">{{ $application->user->userDetail->kota ?? 'Location not specified' }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $application->posisi }}</strong>
                            </td>
                            <td>{{ $application->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $application->status == 'pending' ? 'warning' : 
                                                           ($application->status == 'approved' ? 'success' : 'danger') }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td>
                                @if($application->user->userDetail && $application->user->userDetail->no_telpon)
                                    <small>
                                        <i class="fas fa-phone"></i> {{ $application->user->userDetail->no_telpon }}<br>
                                        <i class="fas fa-envelope"></i> {{ $application->user->email }}
                                    </small>
                                @else
                                    <small><i class="fas fa-envelope"></i> {{ $application->user->email }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- Fixed Eye Button - Direct link to show page -->
                                    <a href="{{ route('admin.application.show', $application->uuid) }}" class="btn btn-info btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Status Update Forms -->
    @foreach($applications as $application)
        <form id="status-form-{{ $application->id }}" action="{{ route('admin.application.status', $application->uuid) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" id="status-input-{{ $application->id }}">
        </form>
    @endforeach
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#applicationsTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "order": [[ 2, "desc" ]], // Sort by date descending
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#applicationsTable_wrapper .col-md-6:eq(0)');
});

function updateStatus(applicationId, status) {
    let message = '';
    if (status === 'approved') {
        message = 'Are you sure you want to approve this application?';
    } else if (status === 'rejected') {
        message = 'Are you sure you want to reject this application?';
    } else {
        message = 'Are you sure you want to reset this application to pending?';
    }

    if (confirm(message)) {
        document.getElementById('status-input-' + applicationId).value = status;
        document.getElementById('status-form-' + applicationId).submit();
    }
}

// Auto-refresh pending applications count every 30 seconds
setInterval(function() {
    // You can implement AJAX to refresh the counts
}, 30000);
</script>
@endpush
@extends('admin.master')

@section('title', 'Application Details - HRM System')
@section('page-title', 'Application Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.application.index') }}">Applications</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Application Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Personal Information</h4>
                            <div class="d-flex align-items-center mb-4">
                                <img src="{{ $application->user->userDetail && $application->user->userDetail->foto ?
                                           asset('storage/' . $application->user->userDetail->foto) :
                                           'https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg' }}"
                                     alt="User Avatar" class="img-circle mr-3" style="width: 100px; height: 100px;">
                                <div>
                                    <h3>{{ $application->user->name }}</h3>
                                    <p class="text-muted">{{ $application->lowongan->posisi ?? $application->posisi }}</p>
                                    <span class="badge badge-{{ $application->status == 'pending' ? 'warning' :
                                                          ($application->status == 'approved' ? 'success' : 'danger') }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $application->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $application->user->userDetail->no_telpon ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>
                                            {{ $application->user->userDetail->alamat ?? '' }}<br>
                                            {{ $application->user->userDetail->kota ?? '' }},
                                            {{ $application->user->userDetail->provinsi ?? '' }}<br>
                                            {{ $application->user->userDetail->kode_pos ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>CV</th>
                                        <td>
                                            @if($application->user->userDetail && $application->user->userDetail->CV)
                                                <a href="{{ asset('storage/' . $application->user->userDetail->CV) }}"
                                                   target="_blank" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-file-pdf"></i> View CV
                                                </a>
                                            @else
                                                No CV uploaded
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4>Education</h4>
                            @if($application->user->userDetail && $application->user->userDetail->pendidikan)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Degree</th>
                                            <td>{{ $application->user->userDetail->pendidikan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Institution</th>
                                            <td>{{ $application->user->userDetail->institusi ?? 'Not specified' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            @else
                                <p>No education information provided.</p>
                            @endif

                            {{-- Tambah QR Code di sini jika approved --}}
                            @if($application->status == 'approved')
                            <div class="text-center my-5" id="qrcode">
                                <h5 class="mb-3">QR Code Peserta Lolos Tes</h5>
                                {!! QrCode::size(220)->generate(json_encode([
                                    'nama' => $application->user->name ?? '-',
                                    'posisi' => $application->lowongan->posisi ?? $application->posisi ?? '-',
                                    'uuid' => $application->uuid,
                                    'keterangan' => 'Lolos - Silakan scan saat tes/wawancara'
                                ])) !!}
                                <div class="mt-2">
                                    <small class="text-muted">Scan QR ini saat peserta hadir tes/wawancara.</small>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group">
                        @if($application->status == 'pending')
                            <form action="{{ route('admin.application.status', $application) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-success mr-2">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.application.status', $application) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.application.status', $application) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-undo"></i> Reset to Pending
                                </button>
                            </form>
                        @endif
                    </div>
                    <a href="{{ route('admin.application.index') }}" class="btn btn-default float-right">Back to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection

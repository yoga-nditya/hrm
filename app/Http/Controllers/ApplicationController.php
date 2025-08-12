<?php

namespace App\Http\Controllers;

use App\Models\StatusLamaran;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ApplicationController extends Controller
{
    // For users to view their applications
    public function userIndex()
    {
        $applications = StatusLamaran::where('users_id', auth()->user()->uuid)
            ->with('lowongan') // assuming you have this relationship
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.applications.index', compact('applications'));
    }

    // For admin to view all applications
    public function adminIndex(Request $request)
    {
        $status = $request->query('status', 'all');

        $applications = StatusLamaran::with(['user.userDetail', 'lowongan'])
            ->when($status !== 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.application.index', compact('applications', 'status'));
    }

    public function updateStatus(Request $request, $uuid)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $application = StatusLamaran::where('uuid', $uuid)->firstOrFail();
        $application->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Application status updated!');
    }

    public function show($uuid)
    {
        $application = StatusLamaran::with(['user.userDetail.pengalaman', 'lowongan'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('admin.application.show', compact('application'));
    }

    public function showQrCode($uuid)
    {
        $application = StatusLamaran::with(['user', 'lowongan'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        if ($application->status != 'approved') {
            abort(403, 'QR Code hanya untuk pelamar yang lolos.');
        }

        // QR berisi URL ke halaman "Lamaran Saya"
        $qrData = route('user.applications');

        return view('user.applications.qrcode', compact('application', 'qrData'));
    }
}

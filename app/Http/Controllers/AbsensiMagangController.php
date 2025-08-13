<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusLamaran;
use App\Models\AbsensiMagang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiMagangController extends Controller
{
    // === USER ===

    // Halaman absensi sendiri (riwayat)
    public function userIndex()
    {
        $application = StatusLamaran::where('users_id', Auth::user()->uuid)
            ->where('status', 'approved')
            ->with('lowongan')
            ->first();

        $absensi = [];
        if ($application) {
            $absensi = AbsensiMagang::where('application_uuid', $application->uuid)
                ->orderByDesc('tanggal')
                ->get();
        }

        return view('user.absensi.index', compact('application', 'absensi'));
    }

    // Tampilkan QR yang cukup berisi UUID aplikasi
    public function userQr()
    {
        $application = StatusLamaran::where('users_id', Auth::user()->uuid)
            ->where('status', 'approved')
            ->with('user.userDetail', 'lowongan')
            ->firstOrFail();

        $qrData = $application->uuid;

        return view('user.absensi.qr', compact('application', 'qrData'));
    }

    // Form absen mandiri via QR/link manual
    public function formAbsen($uuid)
    {
        $application = StatusLamaran::with('user.userDetail', 'lowongan')
            ->where('uuid', $uuid)
            ->where('users_id', Auth::user()->uuid)
            ->where('status', 'approved')
            ->firstOrFail();

        $today = Carbon::today('Asia/Jakarta')->toDateString();
        $already = AbsensiMagang::where('application_uuid', $uuid)->where('tanggal', $today)->first();

        return view('user.absensi.form', compact('application', 'already'));
    }

    // Submit absen mandiri
    public function submitAbsen(Request $request, $uuid)
    {
        $application = StatusLamaran::where('uuid', $uuid)
            ->where('users_id', Auth::user()->uuid)
            ->where('status', 'approved')
            ->firstOrFail();

        $today = Carbon::today('Asia/Jakarta')->toDateString();
        $already = AbsensiMagang::where('application_uuid', $uuid)->where('tanggal', $today)->first();
        if ($already) {
            return back()->with('error', 'Anda sudah absen hari ini!');
        }

        AbsensiMagang::create([
            'application_uuid' => $uuid,
            'tanggal' => $today,
            'waktu' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
            'keterangan' => 'Hadir'
        ]);

        return back()->with('success', 'Absen berhasil!');
    }

    // === ADMIN ===

    public function adminIndex(Request $request)
    {
        $keyword = $request->query('q');
        $absensi = AbsensiMagang::with('application.user', 'application.lowongan')
            ->when($keyword, function($q) use ($keyword) {
                $q->whereHas('application.user', function($qq) use ($keyword) {
                    $qq->where('name', 'like', "%$keyword%");
                });
            })
            ->orderByDesc('tanggal')
            ->paginate(20);

        return view('admin.absensi.index', compact('absensi', 'keyword'));
    }

    public function adminScanPage()
    {
        return view('admin.absensi.scan');
    }

    public function adminScanStore(Request $request)
    {
        $request->validate([
            'payload' => 'required|string'
        ]);

        $payload = trim($request->input('payload'));
        $uuid = $this->extractUuid($payload);
        if (!$uuid) {
            return response()->json([
                'ok' => false,
                'message' => 'QR tidak valid: UUID tidak ditemukan.'
            ], 422);
        }

        // Pastikan aplikasi valid & approved
        $application = StatusLamaran::with('user', 'lowongan')
            ->where('uuid', $uuid)
            ->where('status', 'approved')
            ->first();

        if (!$application) {
            return response()->json([
                'ok' => false,
                'message' => 'Aplikasi magang tidak ditemukan / belum disetujui.'
            ], 404);
        }

        $today = Carbon::today('Asia/Jakarta')->toDateString();

        $already = AbsensiMagang::where('application_uuid', $uuid)
            ->where('tanggal', $today)
            ->first();

        if ($already) {
            return response()->json([
                'ok' => false,
                'message' => 'Peserta ini sudah absen hari ini.',
                'data' => [
                    'name' => $application->user->name ?? '-',
                    'posisi' => $application->lowongan->posisi ?? '-',
                    'tanggal' => $already->tanggal,
                    'waktu' => $already->waktu,
                ]
            ], 409);
        }

        // Catat absensi
        $absen = AbsensiMagang::create([
            'application_uuid' => $uuid,
            'tanggal' => $today,
            'waktu' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
            'keterangan' => 'Hadir'
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Absen berhasil dicatat.',
            'data' => [
                'name' => $application->user->name ?? '-',
                'posisi' => $application->lowongan->posisi ?? '-',
                'tanggal' => $absen->tanggal,
                'waktu' => $absen->waktu,
            ]
        ]);
    }

    private function extractUuid(string $payload): ?string
    {
        if (preg_match('/[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}/i', $payload, $m)) {
            return $m[0];
        }
        if (preg_match('/^[a-f0-9]{32}$/i', $payload)) {
            return $payload;
        }
        return null;
    }
}

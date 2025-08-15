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

    // Halaman absensi sendiri (riwayat) -> group per tanggal, ambil jam masuk/keluar
    public function userIndex()
    {
        $application = StatusLamaran::where('users_id', Auth::user()->uuid)
            ->where('status', 'approved')
            ->with('lowongan')
            ->first();

        $rekap = collect();
        if ($application) {
            $rows = AbsensiMagang::where('application_uuid', $application->uuid)
                ->orderByDesc('tanggal')
                ->orderBy('waktu')
                ->get();

            $rekap = $rows->groupBy('tanggal')->map(function ($g) {
                $masuk  = optional($g->firstWhere('keterangan', 'Masuk'))->waktu;
                $keluar = optional($g->firstWhere('keterangan', 'Keluar'))->waktu;
                return [
                    'tanggal'   => $g->first()->tanggal,
                    'jam_masuk' => $masuk,
                    'jam_keluar'=> $keluar,
                ];
            })->values()->sortByDesc('tanggal');
        }

        return view('user.absensi.index', [
            'application' => $application,
            'rekap'       => $rekap,
        ]);
    }

    // Tampilkan QR: hanya Masuk dulu; setelah Masuk tercatat (hari ini), baru tampil Keluar
    public function userQr()
    {
        $application = StatusLamaran::where('users_id', Auth::user()->uuid)
            ->where('status', 'approved')
            ->with('user.userDetail', 'lowongan')
            ->firstOrFail();

        $uuid  = $application->uuid;
        $today = Carbon::today('Asia/Jakarta')->toDateString();

        $hasMasukToday = AbsensiMagang::where('application_uuid', $uuid)
            ->where('tanggal', $today)
            ->where('keterangan', 'Masuk')
            ->exists();

        $qrMasuk  = 'IN|' . $uuid;
        $qrKeluar = 'OUT|' . $uuid;

        return view('user.absensi.qr', [
            'application'    => $application,
            'uuid'           => $uuid,
            'qrMasuk'        => $qrMasuk,
            'qrKeluar'       => $qrKeluar,
            'hasMasukToday'  => $hasMasukToday,
        ]);
    }

    // (Opsional) form manual (tidak diubah)
    public function formAbsen($uuid)
    {
        $application = StatusLamaran::with('user.userDetail', 'lowongan')
            ->where('uuid', $uuid)
            ->where('users_id', Auth::user()->uuid)
            ->where('status', 'approved')
            ->firstOrFail();

        $today = Carbon::today('Asia/Jakarta')->toDateString();
        $alreadyMasuk = AbsensiMagang::where('application_uuid', $uuid)->where('tanggal', $today)->where('keterangan', 'Masuk')->first();
        $alreadyKeluar = AbsensiMagang::where('application_uuid', $uuid)->where('tanggal', $today)->where('keterangan', 'Keluar')->first();

        return view('user.absensi.form', compact('application', 'alreadyMasuk', 'alreadyKeluar'));
    }

    // Submit absen mandiri (tetap support Masuk/Keluar)
    public function submitAbsen(Request $request, $uuid)
    {
        $application = StatusLamaran::where('uuid', $uuid)
            ->where('users_id', Auth::user()->uuid)
            ->where('status', 'approved')
            ->firstOrFail();

        $today   = Carbon::today('Asia/Jakarta')->toDateString();
        $nowTime = Carbon::now('Asia/Jakarta')->format('H:i:s');
        $tipe    = $this->normalizeType($request->input('tipe'));

        $hasMasuk  = AbsensiMagang::where('application_uuid', $uuid)->where('tanggal', $today)->where('keterangan', 'Masuk')->exists();
        $hasKeluar = AbsensiMagang::where('application_uuid', $uuid)->where('tanggal', $today)->where('keterangan', 'Keluar')->exists();

        if (!$tipe) {
            if (!$hasMasuk) $tipe = 'Masuk';
            elseif (!$hasKeluar) $tipe = 'Keluar';
            else return back()->with('error', 'Absensi Masuk & Keluar hari ini sudah lengkap.');
        }

        if ($tipe === 'Masuk' && $hasMasuk) return back()->with('error', 'Anda sudah absen Masuk hari ini!');
        if ($tipe === 'Keluar') {
            if (!$hasMasuk) return back()->with('error', 'Anda belum absen Masuk hari ini!');
            if ($hasKeluar) return back()->with('error', 'Anda sudah absen Keluar hari ini!');
        }

        AbsensiMagang::create([
            'application_uuid' => $uuid,
            'tanggal' => $today,
            'waktu' => $nowTime,
            'keterangan' => $tipe,
        ]);

        return back()->with('success', "Absen $tipe berhasil!");
    }

    // === ADMIN ===

    // Rekap per (peserta, tanggal) dengan jam masuk/keluar
    public function adminIndex(Request $request)
    {
        $keyword = $request->query('q');

        // Ambil page dari grouping query
        $groups = AbsensiMagang::select('application_uuid', 'tanggal')
            ->selectRaw("MIN(CASE WHEN keterangan = 'Masuk' THEN waktu END) AS jam_masuk")
            ->selectRaw("MIN(CASE WHEN keterangan = 'Keluar' THEN waktu END) AS jam_keluar")
            ->when($keyword, function ($q) use ($keyword) {
                // filter berdasarkan nama user
                $q->whereIn('application_uuid', function ($sub) use ($keyword) {
                    $sub->select('uuid')
                        ->from((new \App\Models\StatusLamaran)->getTable())
                        ->whereIn('users_id', function ($sub2) use ($keyword) {
                            $sub2->select('uuid')
                                ->from((new \App\Models\User)->getTable())
                                ->where('name', 'like', "%$keyword%");
                        });
                });
            })
            ->groupBy('application_uuid', 'tanggal')
            ->orderByDesc('tanggal')
            ->paginate(20);

        // Ambil data aplikasi (user & lowongan) untuk baris di page ini saja
        $appUuids = collect($groups->items())->pluck('application_uuid')->unique()->values();
        $apps = StatusLamaran::with('user', 'lowongan')
            ->whereIn('uuid', $appUuids)->get()
            ->keyBy('uuid');

        return view('admin.absensi.index', [
            'groups'  => $groups,
            'apps'    => $apps,      // map uuid -> application
            'keyword' => $keyword,
        ]);
    }

    public function adminScanPage()
    {
        return view('admin.absensi.scan');
    }

    public function adminScanStore(Request $request)
    {
        $request->validate(['payload' => 'required|string']);

        $payload = trim($request->input('payload'));
        [$uuid, $tipe] = $this->extractScanInfo($payload);

        if (!$uuid) {
            return response()->json(['ok' => false, 'message' => 'QR tidak valid: UUID tidak ditemukan.'], 422);
        }

        $application = StatusLamaran::with('user', 'lowongan')
            ->where('uuid', $uuid)->where('status', 'approved')->first();

        if (!$application) {
            return response()->json(['ok' => false, 'message' => 'Aplikasi magang tidak ditemukan / belum disetujui.'], 404);
        }

        $today   = Carbon::today('Asia/Jakarta')->toDateString();
        $nowTime = Carbon::now('Asia/Jakarta')->format('H:i:s');

        $hasMasuk  = AbsensiMagang::where('application_uuid', $uuid)->where('tanggal', $today)->where('keterangan', 'Masuk')->exists();
        $hasKeluar = AbsensiMagang::where('application_uuid', $uuid)->where('tanggal', $today)->where('keterangan', 'Keluar')->exists();

        if (!$tipe) $tipe = !$hasMasuk ? 'Masuk' : 'Keluar';

        if ($tipe === 'Masuk' && $hasMasuk) {
            return response()->json([
                'ok' => false,
                'message' => 'Peserta sudah absen Masuk hari ini.',
                'data' => [
                    'name' => $application->user->name ?? '-',
                    'posisi' => $application->lowongan->posisi ?? '-',
                    'tanggal' => $today,
                    'waktu' => $nowTime,
                    'jenis' => 'Masuk'
                ]
            ], 409);
        }
        if ($tipe === 'Keluar') {
            if (!$hasMasuk) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Belum ada absen Masuk hari ini.',
                    'data' => [
                        'name' => $application->user->name ?? '-',
                        'posisi' => $application->lowongan->posisi ?? '-',
                        'tanggal' => $today,
                        'waktu' => $nowTime,
                        'jenis' => 'Keluar'
                    ]
                ], 409);
            }
            if ($hasKeluar) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Peserta sudah absen Keluar hari ini.',
                    'data' => [
                        'name' => $application->user->name ?? '-',
                        'posisi' => $application->lowongan->posisi ?? '-',
                        'tanggal' => $today,
                        'waktu' => $nowTime,
                        'jenis' => 'Keluar'
                    ]
                ], 409);
            }
        }

        $absen = AbsensiMagang::create([
            'application_uuid' => $uuid,
            'tanggal' => $today,
            'waktu' => $nowTime,
            'keterangan' => $tipe,
        ]);

        return response()->json([
            'ok' => true,
            'message' => "Absen $tipe berhasil dicatat.",
            'data' => [
                'name' => $application->user->name ?? '-',
                'posisi' => $application->lowongan->posisi ?? '-',
                'tanggal' => $absen->tanggal,
                'waktu' => $absen->waktu,
                'jenis' => $tipe,
            ]
        ]);
    }

    private function extractScanInfo(string $payload): array
    {
        $payload = trim($payload);
        if (stripos($payload, 'IN|') === 0)  { return [$this->extractUuid(substr($payload, 3)), 'Masuk']; }
        if (stripos($payload, 'OUT|') === 0) { return [$this->extractUuid(substr($payload, 4)), 'Keluar']; }
        if (preg_match('/\bIN\|([a-f0-9-]{32,36})\b/i', $payload, $m))  { return [$this->extractUuid($m[1]), 'Masuk']; }
        if (preg_match('/\bOUT\|([a-f0-9-]{32,36})\b/i', $payload, $m)) { return [$this->extractUuid($m[1]), 'Keluar']; }
        return [$this->extractUuid($payload), null];
    }

    private function extractUuid(string $payload): ?string
    {
        if (preg_match('/[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}/i', $payload, $m)) return $m[0];
        if (preg_match('/^[a-f0-9]{32}$/i', $payload)) return $payload;
        return null;
    }

    private function normalizeType($raw): ?string
    {
        if (!$raw) return null;
        $raw = trim(strtolower($raw));
        if (in_array($raw, ['in','masuk','checkin','check-in'])) return 'Masuk';
        if (in_array($raw, ['out','keluar','checkout','check-out'])) return 'Keluar';
        return null;
    }
}

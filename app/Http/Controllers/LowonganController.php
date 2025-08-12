<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\StatusLamaran;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    public function __construct()
    {
        $this->middleware('profile.complete')->only(['showApplyForm', 'apply']);
    }

    // LowonganController.php
    public function index()
    {
        $lowongan = Lowongan::paginate(10);

        $appliedJobs = [];
        $isProfileComplete = false;

        if (auth()->check()) {
            $appliedJobs = StatusLamaran::where('users_id', auth()->user()->uuid)
                ->pluck('posisi')
                ->toArray();

            $isProfileComplete = $this->isProfileComplete(auth()->user());
        }

        return view('user.lowongan.index', compact('lowongan', 'appliedJobs', 'isProfileComplete'));
    }

    // app/Http/Controllers/LowonganController.php
private function isProfileComplete($user)
{
    // Pastikan UserDetail ada
    if (!$user->userDetail) {
        return false;
    }

    // Field wajib dari UserDetail
    $userDetailRequired = ['alamat', 'no_telpon', 'CV'];
    foreach ($userDetailRequired as $field) {
        if (empty($user->userDetail->$field)) {
            return false;
        }
    }

    // Pastikan relasi pengalaman ada dan pendidikan terisi
    if (!$user->userDetail->pengalaman || empty($user->userDetail->pengalaman->first()->pendidikan)) {
        return false;
    }

    return true;
}

    public function show(Lowongan $lowongan)
    {
        $hasApplied = false;
        if (auth()->check()) {
            $hasApplied = StatusLamaran::where('users_id', auth()->user()->uuid)
                ->where('posisi', $lowongan->posisi)
                ->exists();
        }

        return view('user.lowongan.show', compact('lowongan', 'hasApplied'));
    }
    public function showApplyForm(Lowongan $lowongan)
    {
        return view('user.lowongan.apply', compact('lowongan'));
    }

    public function apply(Request $request, Lowongan $lowongan)
    {
        $existingApplication = StatusLamaran::where('users_id', auth()->user()->uuid)
            ->where('posisi', $lowongan->posisi)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this position!');
        }

        StatusLamaran::create([
            'users_id' => auth()->user()->uuid,
            'posisi' => $lowongan->posisi,
            'status' => 'pending',
        ]);

        return redirect()->route('user.applications')->with('success', 'Application submitted successfully!');
    }
    // Admin methods
    public function adminIndex()
    {
        $lowongan = Lowongan::paginate(10);
        return view('admin.lowongan.index', compact('lowongan'));
    }

    public function create()
    {
        return view('admin.lowongan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'posisi' => 'required|string|max:255',
            'jenis_pekerjaan' => 'required|string',
            'role_pekerjaan' => 'required|string',
            'deskripsi' => 'required|string',
        ]);

        Lowongan::create($request->all());

        return redirect()->route('admin.lowongan.index')->with('success', 'Job created successfully!');
    }

    public function edit(Lowongan $lowongan)
    {
        return view('admin.lowongan.edit', compact('lowongan'));
    }

    public function update(Request $request, Lowongan $lowongan)
    {
        $request->validate([
            'posisi' => 'required|string|max:255',
            'jenis_pekerjaan' => 'required|string',
            'role_pekerjaan' => 'required|string',
            'deskripsi' => 'required|string',
        ]);

        $lowongan->update($request->all());

        return redirect()->route('admin.lowongan.index')->with('success', 'Job updated successfully!');
    }

    public function destroy(Lowongan $lowongan)
    {
        $lowongan->delete();
        return redirect()->route('admin.lowongan.index')->with('success', 'Job deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pengalaman;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $pengalaman = $userDetail ? $userDetail->pengalaman->first() : null;

        return view('user.profile.index', compact('user', 'userDetail', 'pengalaman'));
    }

    public function edit()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $pengalaman = $userDetail ? $userDetail->pengalaman->first() : null;

        return view('user.profile.edit', compact('user', 'userDetail', 'pengalaman'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'provinsi' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'no_telpon' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB
            'CV' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB
            'pengalaman_kerja' => 'nullable|string|max:500',
            'pengalaman_organisasi' => 'nullable|string|max:500',
            'pengalaman_sertifikasi_pelatihan' => 'nullable|string|max:500',
            'pendidikan' => 'nullable|string|max:255',
        ], [
            // Custom error messages
            'name.required' => 'Nama lengkap wajib diisi.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            'CV.mimes' => 'Format CV harus PDF, DOC, atau DOCX.',
            'CV.max' => 'Ukuran CV maksimal 5MB.',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update user name
            $user->update(['name' => $validated['name']]);

            // 2. Handle user detail (buat jika belum ada)
            $userDetail = $user->userDetail;
            if (!$userDetail) {
                $userDetail = new UserDetail();
                $userDetail->users_id = $user->uuid; // atau $user->id sesuai dengan struktur database
            }

            // 3. Handle file uploads
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($userDetail->foto && Storage::exists('public/' . $userDetail->foto)) {
                    Storage::delete('public/' . $userDetail->foto);
                }
                // Simpan foto baru
                $fotoPath = $request->file('foto')->store('photos', 'public');
                $userDetail->foto = $fotoPath;
            }

            if ($request->hasFile('CV')) {
                // Hapus CV lama jika ada
                if ($userDetail->CV && Storage::exists('public/' . $userDetail->CV)) {
                    Storage::delete('public/' . $userDetail->CV);
                }
                // Simpan CV baru
                $cvPath = $request->file('CV')->store('cvs', 'public');
                $userDetail->CV = $cvPath;
            }

            // 4. Update data user detail
            $userDetail->fill($request->only([
                'alamat',
                'provinsi',
                'kota',
                'kode_pos',
                'no_telpon'
            ]));
            $userDetail->save();

            // 5. Handle pengalaman (buat jika belum ada)
            $pengalaman = $userDetail->pengalaman->first();
            if (!$pengalaman) {
                $pengalaman = new Pengalaman();
                $pengalaman->users_details_id = $userDetail->uuid; // atau $userDetail->id
            }

            $pengalaman->fill($request->only([
                'pengalaman_kerja',
                'pengalaman_organisasi',
                'pengalaman_sertifikasi_pelatihan',
                'pendidikan'
            ]));
            $pengalaman->save();

            DB::commit();

            return redirect()
                ->route('profile.show')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
}

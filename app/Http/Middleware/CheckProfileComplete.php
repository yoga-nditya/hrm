<?php

// app/Http/Middleware/CheckProfileComplete.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckProfileComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && !$this->isProfileComplete($user)) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Silakan lengkapi profil Anda terlebih dahulu sebelum melamar pekerjaan.');
        }

        return $next($request);
    }

    private function isProfileComplete($user)
    {
        // Field yang wajib diisi berdasarkan model yang diberikan
        $requiredFields = [
            'name', // Dari model User
            'userDetail.alamat', // Dari model UserDetail
            'userDetail.no_telpon', // Dari model UserDetail
            'userDetail.pengalaman', // Dari model Pengalaman
            'userDetail.CV' // Dari model UserDetail
        ];

        foreach ($requiredFields as $field) {
            $parts = explode('.', $field);
            $current = $user;

            foreach ($parts as $part) {
                if (is_null($current->$part ?? null)) {
                    return false;
                }
                $current = $current->$part;
            }
        }

        return true;
    }
}

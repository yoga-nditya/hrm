<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\StatusLamaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userDashboard()
{
    $user = Auth::user();

    // Check if user profile is complete
    $isProfileComplete = $this->checkProfileComplete($user);

    // Calculate profile completion percentage
    $profileCompletionPercentage = $this->getProfileCompletionPercentage($user);

    // Get job recommendations (latest 5 jobs)
    $lowongan = Lowongan::where('is_active', true)
        ->latest()
        ->take(5)
        ->get();

    // Get user's applications
    $myApplications = StatusLamaran::where('users_id', $user->uuid)
        ->with(['lowongan' => function($query) {
            $query->select('uuid', 'posisi', 'department', 'jenis_pekerjaan');
        }])
        ->latest()
        ->get();

    // Get application statistics
    $applicationStats = [
        'total' => $myApplications->count(),
        'pending' => $myApplications->where('status', 'pending')->count(),
        'approved' => $myApplications->where('status', 'approved')->count(),
        'rejected' => $myApplications->where('status', 'rejected')->count(),
    ];

    return view('user.dashboard', compact(
        'lowongan',
        'myApplications',
        'applicationStats',
        'isProfileComplete',
        'profileCompletionPercentage'
    ));
}

    public function adminDashboard()
    {
        $totalUsers = User::where('roles', 'User')->count();
        $totalLowongan = Lowongan::count();
        $totalApplications = StatusLamaran::count();
        $pendingApplications = StatusLamaran::where('status', 'pending')->count();
        $approvedApplications = StatusLamaran::where('status', 'approved')->count();
        $rejectedApplications = StatusLamaran::where('status', 'rejected')->count();

        // Recent activities
        $recentApplications = StatusLamaran::with(['user', 'lowongan'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::where('roles', 'User')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalLowongan',
            'totalApplications',
            'pendingApplications',
            'approvedApplications',
            'rejectedApplications',
            'recentApplications',
            'recentUsers'
        ));
    }

    /**
     * Check if user profile is complete
     */
    private function checkProfileComplete($user)
    {
        // Define required fields for complete profile
        $requiredFields = [
            'name',
            'email',
            'phone',
            'address',
            'date_of_birth',
            'gender'
        ];

        foreach ($requiredFields as $field) {
            if (empty($user->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get dashboard statistics for API
     */
    public function getDashboardStats()
    {
        $user = Auth::user();

        if ($user->roles === 'User') {
            $myApplications = StatusLamaran::where('users_id', $user->uuid)->get();
            $availableJobs = Lowongan::where('is_active')->count();

            return response()->json([
                'user_type' => 'user',
                'stats' => [
                    'available_jobs' => $availableJobs,
                    'total_applications' => $myApplications->count(),
                    'pending_applications' => $myApplications->where('status', 'pending')->count(),
                    'approved_applications' => $myApplications->where('status', 'approved')->count(),
                    'rejected_applications' => $myApplications->where('status', 'rejected')->count(),
                ]
            ]);
        }

        // Admin stats
        return response()->json([
            'user_type' => 'admin',
            'stats' => [
                'total_users' => User::where('roles', 'User')->count(),
                'total_jobs' => Lowongan::count(),
                'total_applications' => StatusLamaran::count(),
                'pending_applications' => StatusLamaran::where('status', 'pending')->count(),
                'approved_applications' => StatusLamaran::where('status', 'approved')->count(),
                'rejected_applications' => StatusLamaran::where('status', 'rejected')->count(),
            ]
        ]);
    }

    /**
     * Check profile completion status
     */
    public function checkProfile()
    {
        $user = Auth::user();
        $isComplete = $this->checkProfileComplete($user);

        $missingFields = [];
        if (!$isComplete) {
            $requiredFields = [
                'phone' => 'Nomor Telepon',
                'address' => 'Alamat',
                'date_of_birth' => 'Tanggal Lahir',
                'gender' => 'Jenis Kelamin'
            ];

            foreach ($requiredFields as $field => $label) {
                if (empty($user->$field)) {
                    $missingFields[] = $label;
                }
            }
        }

        return response()->json([
            'is_complete' => $isComplete,
            'missing_fields' => $missingFields,
            'profileCompletionPercentage' => $this->getProfileCompletionPercentage($user),
        ]);
    }

    /**
     * Get profile completion percentage
     */
    private function getProfileCompletionPercentage($user)
    {
        $requiredFields = ['name', 'email', 'phone', 'address', 'date_of_birth', 'gender'];
        $completedFields = 0;

        foreach ($requiredFields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }

        return round(($completedFields / count($requiredFields)) * 100);
    }
}

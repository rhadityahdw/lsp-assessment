<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Certificate;
use App\Models\Schedule;
use App\Models\Scheme;
use App\Models\User;
use App\Models\Attempt;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        
        $data = [];
        
        switch ($role) {
            case 'admin':
                $data = $this->getAdminDashboardData();
                break;
            case 'asesor':
                $data = $this->getAsesorDashboardData($user);
                break;
            case 'asesi':
                $data = $this->getAsesiDashboardData($user);
                break;
        }
        
        return Inertia::render('Dashboard', [
            'dashboardData' => $data,
            'userRole' => $role
        ]);
    }
    
    private function getAdminDashboardData()
    {
        return [
            'stats' => [
                'totalUsers' => User::count(),
                'totalSchemes' => Scheme::count(),
                'totalAssessments' => Assessment::count(),
                'activeSchedules' => Schedule::where('status', 'scheduled')->count(),
                'certificatesIssued' => Certificate::count(),
            ],
            'recentUsers' => User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']),
            'recentAssessments' => Assessment::with(['scheme', 'createdBy'])
                ->latest()->take(5)->get(),
            'pendingAttempts' => Attempt::where('status', 'submitted')
                ->with(['user', 'scheme'])->take(5)->get(),
            'recentCertificates' => Certificate::with(['user', 'scheme'])
                ->latest()->take(5)->get(),
            'usersByRole' => [
                'admin' => User::role('admin')->count(),
                'asesor' => User::role('asesor')->count(),
                'asesi' => User::role('asesi')->count(),
            ]
        ];
    }
    
    private function getAsesorDashboardData($user)
    {
        return [
            'stats' => [
                'totalAssessments' => Assessment::where('created_by', $user->id)->count(),
                'activeSchedules' => Schedule::where('asesor_id', $user->id)
                    ->where('status', 'scheduled')->count(),
                'completedSchedules' => Schedule::where('asesor_id', $user->id)
                    ->where('status', 'completed')->count(),
                'pendingGrading' => Schedule::where('asesor_id', $user->id)
                    ->where('status', 'completed')
                    ->whereDoesntHave('asesis', function($query) {
                        $query->whereNotNull('asesi_schedule.score');
                    })->count(),
            ],
            'upcomingSchedules' => Schedule::where('asesor_id', $user->id)
                ->where('status', 'scheduled')
                ->with(['assessment', 'asesis'])
                ->orderBy('schedule_time')
                ->take(5)->get(),
            'pendingGradingSchedules' => Schedule::where('asesor_id', $user->id)
                ->where('status', 'completed')
                ->whereDoesntHave('asesiSchedules', function($query) {
                    $query->whereNotNull('asesi_schedule.score');
                })
                ->with(['assessment', 'asesis'])
                ->take(5)->get(),
            'recentAssessments' => Assessment::where('created_by', $user->id)
                ->with('scheme')
                ->latest()->take(5)->get(),
        ];
    }
    
    private function getAsesiDashboardData($user)
    {
        return [
            'stats' => [
                'totalAttempts' => Attempt::where('user_id', $user->id)->count(),
                'approvedAttempts' => Attempt::where('user_id', $user->id)
                    ->where('status', 'approved')->count(),
                'pendingAttempts' => Attempt::where('user_id', $user->id)
                    ->where('status', 'submitted')->count(),
                'certificates' => Certificate::where('user_id', $user->id)->count(),
            ],
            'upcomingSchedules' => Schedule::whereHas('asesis', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->where('status', 'scheduled')
                ->with(['assessment', 'asesor'])
                ->orderBy('schedule_time')
                ->take(5)->get(),
            'recentAttempts' => Attempt::where('user_id', $user->id)
                ->with('scheme')
                ->latest()->take(5)->get(),
            'certificates' => Certificate::where('user_id', $user->id)
                ->with('scheme')
                ->latest()->take(5)->get(),
        ];
    }
}
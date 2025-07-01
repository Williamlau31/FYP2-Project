<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\Appointment;
use App\Models\QueueItem;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get dashboard statistics
        $stats = [
            'total_patients' => Patient::count(),
            'total_staff' => Staff::count(),
            'today_appointments' => Appointment::whereDate('date', Carbon::today())->count(),
            'queue_count' => QueueItem::where('status', 'waiting')->count(),
            'scheduled_appointments' => Appointment::where('status', 'scheduled')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
            'cancelled_appointments' => Appointment::where('status', 'cancelled')->count(),
        ];

        // Get recent activities
        $recent_activities = $this->getRecentActivities();

        // Get today's appointments
        $today_appointments = Appointment::with(['patient', 'staff'])
            ->whereDate('date', Carbon::today())
            ->orderBy('time')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('user', 'stats', 'recent_activities', 'today_appointments'));
    }

    private function getRecentActivities()
    {
        $activities = collect();

        // Recent appointments
        $recent_appointments = Appointment::with(['patient', 'staff'])
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        foreach ($recent_appointments as $appointment) {
            $activities->push([
                'type' => 'appointment',
                'icon' => 'calendar',
                'color' => 'primary',
                'title' => 'New Appointment Scheduled',
                'description' => $appointment->patient->name . ' with ' . $appointment->staff->name,
                'time' => $appointment->created_at->diffForHumans(),
            ]);
        }

        // Recent patients
        $recent_patients = Patient::where('created_at', '>=', Carbon::now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        foreach ($recent_patients as $patient) {
            $activities->push([
                'type' => 'patient',
                'icon' => 'person-add',
                'color' => 'success',
                'title' => 'New Patient Registered',
                'description' => $patient->name . ' added to system',
                'time' => $patient->created_at->diffForHumans(),
            ]);
        }

        return $activities->sortByDesc('time')->take(5);
    }
}


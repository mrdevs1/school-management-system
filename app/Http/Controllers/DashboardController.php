<?php
// ============================================================
// app/Http/Controllers/DashboardController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\{Student, Teacher, FeeCollection, Attendance, Notice};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents  = Student::where('status', 'active')->count();
        $totalTeachers  = Teacher::where('status', 'active')->count();
        $monthlyCollection = FeeCollection::whereYear('payment_date', now()->year)
                                ->whereMonth('payment_date', now()->month)->sum('paid_amount');
        $totalDue       = FeeCollection::sum('due_amount');
        $todayPresent   = Attendance::whereDate('date', today())->where('status', 'present')->count();
        $todayAbsent    = Attendance::whereDate('date', today())->where('status', 'absent')->count();
        $todayLate      = Attendance::whereDate('date', today())->where('status', 'late')->count();
        $recentStudents = Student::with('studentClass')->latest()->limit(8)->get();
        $latestNotices  = Notice::where('publish_date', '<=', today())
                                ->orderByDesc('publish_date')->limit(5)->get();
        $dueFees        = FeeCollection::select('student_id', DB::raw('SUM(due_amount) as total_due'))
                                ->with('student.studentClass')
                                ->groupBy('student_id')
                                ->having('total_due', '>', 0)
                                ->orderByDesc('total_due')
                                ->limit(8)->get();
        $currentSession = \App\Models\Session::where('is_current', true)->first();

        return view('dashboard.index', compact(
            'totalStudents','totalTeachers','monthlyCollection','totalDue',
            'todayPresent','todayAbsent','todayLate',
            'recentStudents','latestNotices','dueFees','currentSession'
        ));
    }
}

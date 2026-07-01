<?php
namespace App\Http\Controllers;
use App\Models\{Student, Teacher, FeeCollection, MealPlan, WelfareFund, Attendance, Classes, Session};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $currentSession = Session::where('is_current',true)->first();
        return view('reports.index', compact('currentSession'));
    }

    public function financial(Request $request)
    {
        $year  = $request->year ?? now()->year;
        $month = $request->month;

        $feeQuery     = FeeCollection::query();
        $mealQuery    = MealPlan::query();
        $welfareQuery = WelfareFund::query();

        if ($month) {
            $feeQuery->whereYear('payment_date',$year)->whereMonth('payment_date',$month);
            $mealQuery->where('month_year', $year.'-'.str_pad($month,2,'0',STR_PAD_LEFT));
            $welfareQuery->whereYear('date',$year)->whereMonth('date',$month);
        } else {
            $feeQuery->whereYear('payment_date',$year);
            $mealQuery->where('month_year','like',$year.'%');
            $welfareQuery->whereYear('date',$year);
        }

        $data = [
            'fee_collected'       => $feeQuery->sum('paid_amount'),
            'fee_due'             => FeeCollection::sum('due_amount'),
            'meal_total'          => $mealQuery->sum('total_amount'),
            'meal_student'        => $mealQuery->sum('student_paid'),
            'meal_institution'    => $mealQuery->sum('institution_paid'),
            'meal_due'            => $mealQuery->sum('due_amount'),
            'welfare_total'       => $welfareQuery->sum('total_amount'),
            'welfare_institution' => $welfareQuery->sum('institution_contribution'),
            'welfare_donor'       => $welfareQuery->sum('donor_contribution'),
        ];

        $monthlyFee = FeeCollection::select(
            DB::raw('MONTH(payment_date) as month'),
            DB::raw('SUM(paid_amount) as total')
        )->whereYear('payment_date',$year)->groupBy('month')->orderBy('month')->get();

        $currentSession = Session::where('is_current',true)->first();
        return view('reports.financial', compact('data','monthlyFee','year','month','currentSession'));
    }

    public function students(Request $request)
    {
        $classes = Classes::withCount([
            'students as total'  => fn($q) => $q->where('status','active'),
            'students as male'   => fn($q) => $q->where('status','active')->where('gender','male'),
            'students as female' => fn($q) => $q->where('status','active')->where('gender','female'),
        ])->orderBy('numeric_name')->get();

        $totalStudents  = $classes->sum('total');
        $totalMale      = $classes->sum('male');
        $totalFemale    = $classes->sum('female');
        $currentSession = Session::where('is_current',true)->first();
        return view('reports.students', compact('classes','totalStudents','totalMale','totalFemale','currentSession'));
    }

    public function attendance(Request $request)
    {
        $month    = $request->month ?? now()->format('Y-m');
        $class_id = $request->class_id;
        $classes  = Classes::orderBy('numeric_name')->get();
        $data     = collect();

        if ($class_id) {
            [$year,$mon] = explode('-',$month);
            $data = Student::where('class_id',$class_id)->where('status','active')
                ->with(['attendances' => fn($q) => $q->whereYear('date',$year)->whereMonth('date',$mon)])
                ->orderBy('roll_number')->get()
                ->map(fn($s) => [
                    'student' => $s,
                    'present' => $s->attendances->where('status','present')->count(),
                    'absent'  => $s->attendances->where('status','absent')->count(),
                    'late'    => $s->attendances->where('status','late')->count(),
                    'leave'   => $s->attendances->where('status','leave')->count(),
                ]);
        }

        $currentSession = Session::where('is_current',true)->first();
        return view('reports.attendance', compact('data','month','class_id','classes','currentSession'));
    }

    public function welfare(Request $request)
    {
        $year = $request->year ?? now()->year;

        $byType = WelfareFund::select(
            'type',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total_amount) as total'),
            DB::raw('SUM(institution_contribution) as institution'),
            DB::raw('SUM(student_contribution) as student'),
            DB::raw('SUM(donor_contribution) as donor')
        )->whereYear('date',$year)->groupBy('type')->get();

        $currentSession = Session::where('is_current',true)->first();
        return view('reports.welfare', compact('byType','year','currentSession'));
    }

    public function meal(Request $request)
    {
        $month    = $request->month ?? now()->format('Y-m');
        $class_id = $request->class_id;
        $classes  = Classes::orderBy('numeric_name')->get();

        $data = MealPlan::with('student.studentClass')
            ->where('month_year',$month)
            ->when($class_id, fn($q) => $q->whereHas('student',fn($sq)=>$sq->where('class_id',$class_id)))
            ->get();

        $stats = [
            'total'       => $data->sum('total_amount'),
            'student'     => $data->sum('student_paid'),
            'institution' => $data->sum('institution_paid'),
            'due'         => $data->sum('due_amount'),
        ];

        $currentSession = Session::where('is_current',true)->first();
        return view('reports.meal', compact('data','stats','month','class_id','classes','currentSession'));
    }
}

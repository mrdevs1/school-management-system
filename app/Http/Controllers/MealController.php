<?php
namespace App\Http\Controllers;
use App\Models\{MealPlan, Student, Classes};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MealController extends Controller
{
    public function index(Request $request)
    {
        $month    = $request->month ?? now()->format('Y-m');
        $class_id = $request->class_id;
        $classes  = Classes::orderBy('numeric_name')->get();
        $meals    = collect();
        $stats    = [];

        if ($class_id) {
            $meals = MealPlan::with('student.studentClass')
                ->where('month_year', $month)
                ->whereHas('student', fn($q) => $q->where('class_id', $class_id))
                ->orderBy('id')->paginate(25);

            $stats = [
                'total_amount'       => MealPlan::where('month_year',$month)->whereHas('student',fn($q)=>$q->where('class_id',$class_id))->sum('total_amount'),
                'student_paid'       => MealPlan::where('month_year',$month)->whereHas('student',fn($q)=>$q->where('class_id',$class_id))->sum('student_paid'),
                'institution_paid'   => MealPlan::where('month_year',$month)->whereHas('student',fn($q)=>$q->where('class_id',$class_id))->sum('institution_paid'),
                'due_amount'         => MealPlan::where('month_year',$month)->whereHas('student',fn($q)=>$q->where('class_id',$class_id))->sum('due_amount'),
            ];
        }

        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('meals.index', compact('meals','month','class_id','classes','stats','currentSession'));
    }

    public function create(Request $request)
    {
        $classes  = Classes::orderBy('numeric_name')->get();
        $students = Student::where('status','active')
            ->when($request->class_id, fn($q) => $q->where('class_id',$request->class_id))
            ->orderBy('name')->get();
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('meals.form', compact('classes','students','currentSession'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'       => 'required|exists:students,id',
            'month_year'       => 'required|string',
            'total_days'       => 'required|integer|min:1',
            'present_days'     => 'required|integer|min:0',
            'rate_per_day'     => 'required|numeric|min:0',
            'student_paid'     => 'required|numeric|min:0',
            'institution_paid' => 'required|numeric|min:0',
            'note'             => 'nullable|string',
        ]);

        $total  = $data['present_days'] * $data['rate_per_day'];
        $due    = max(0, $total - $data['student_paid'] - $data['institution_paid']);

        MealPlan::updateOrCreate(
            ['student_id'=>$data['student_id'],'month_year'=>$data['month_year']],
            array_merge($data, [
                'total_amount' => $total,
                'due_amount'   => $due,
                'created_by'   => auth()->id(),
            ])
        );

        return redirect()->route('meals.index')->with('success','খোরাকি তথ্য সংরক্ষণ হয়েছে!');
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'month_year'   => 'required|string',
            'class_id'     => 'required|exists:classes,id',
            'rate_per_day' => 'required|numeric|min:0',
            'entries'      => 'required|array',
        ]);

        $count = 0;
        foreach ($request->entries as $studentId => $entry) {
            if (empty($entry['present_days'])) continue;
            $total = $entry['present_days'] * $request->rate_per_day;
            $studentPaid = $entry['student_paid'] ?? 0;
            $instPaid    = $entry['institution_paid'] ?? 0;
            $due         = max(0, $total - $studentPaid - $instPaid);

            MealPlan::updateOrCreate(
                ['student_id'=>$studentId,'month_year'=>$request->month_year],
                [
                    'total_days'       => $entry['total_days'] ?? 30,
                    'present_days'     => $entry['present_days'],
                    'rate_per_day'     => $request->rate_per_day,
                    'total_amount'     => $total,
                    'student_paid'     => $studentPaid,
                    'institution_paid' => $instPaid,
                    'due_amount'       => $due,
                    'created_by'       => auth()->id(),
                ]
            );
            $count++;
        }

        return back()->with('success',"{$count} জন ছাত্রের খোরাকি তথ্য সংরক্ষণ হয়েছে!");
    }

    public function destroy(MealPlan $meal)
    {
        $meal->delete();
        return back()->with('success','মুছে ফেলা হয়েছে!');
    }
}

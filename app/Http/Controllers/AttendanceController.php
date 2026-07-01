<?php
namespace App\Http\Controllers;
use App\Models\{Attendance, Student, Classes, Section};
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date     = $request->date ?? today()->format('Y-m-d');
        $classes  = Classes::orderBy('numeric_name')->get();
        $sections = Section::when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->get();
        $students = collect();
        if ($request->class_id) {
            $students = Student::where('class_id', $request->class_id)
                ->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))
                ->where('status', 'active')->orderBy('roll_number')
                ->with(['attendances' => fn($q) => $q->whereDate('date', $date)])->get();
        }
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('attendance.index', compact('students','date','classes','sections','currentSession'));
    }

    public function store(Request $request)
    {
        $request->validate(['date'=>'required|date','class_id'=>'required','attendances'=>'required|array']);
        foreach ($request->attendances as $studentId => $status) {
            Attendance::updateOrCreate(
                ['student_id'=>$studentId,'date'=>$request->date],
                ['class_id'=>$request->class_id,'section_id'=>$request->section_id,'status'=>$status,'remarks'=>$request->remarks[$studentId]??null,'taken_by'=>auth()->id()]
            );
        }
        return back()->with('success','হাজিরা সফলভাবে সংরক্ষণ হয়েছে!');
    }

    public function monthly(Request $request)
    {
        $month    = $request->month ?? now()->format('Y-m');
        $class_id = $request->class_id;
        $classes  = Classes::orderBy('numeric_name')->get();
        $data     = collect();
        if ($class_id) {
            [$year,$mon] = explode('-', $month);
            $days = Carbon::parse($month)->daysInMonth;
            $students = Student::where('class_id',$class_id)->where('status','active')
                ->with(['attendances'=>fn($q)=>$q->whereYear('date',$year)->whereMonth('date',$mon)])
                ->orderBy('roll_number')->get();
            $data = $students->map(fn($s)=>[
                'student'=>$s,
                'present'=>$s->attendances->where('status','present')->count(),
                'absent' =>$s->attendances->where('status','absent')->count(),
                'late'   =>$s->attendances->where('status','late')->count(),
                'leave'  =>$s->attendances->where('status','leave')->count(),
                'total'  =>$days,
            ]);
        }
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('attendance.monthly', compact('data','month','class_id','classes','currentSession'));
    }

    public function teacherAttendance()
    {
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('attendance.teacher', compact('currentSession'));
    }
}

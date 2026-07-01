<?php
namespace App\Http\Controllers;
use App\Models\{WelfareFund, Student, Classes};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelfareController extends Controller
{
    public function index(Request $request)
    {
        $welfare = WelfareFund::with('student.studentClass')
            ->when($request->class_id, fn($q) => $q->whereHas('student',fn($sq)=>$sq->where('class_id',$request->class_id)))
            ->when($request->type, fn($q) => $q->where('type',$request->type))
            ->when($request->month, fn($q) => $q->where('month_year',$request->month))
            ->when($request->search, fn($q) => $q->whereHas('student',fn($sq)=>$sq->where('name','like',"%{$request->search}%")))
            ->orderByDesc('date')->paginate(25);

        $stats = [
            'total'       => WelfareFund::sum('total_amount'),
            'institution' => WelfareFund::sum('institution_contribution'),
            'student'     => WelfareFund::sum('student_contribution'),
            'donor'       => WelfareFund::sum('donor_contribution'),
        ];

        $classes = Classes::orderBy('numeric_name')->get();
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('welfare.index', compact('welfare','stats','classes','currentSession'));
    }

    public function create()
    {
        $students = Student::where('status','active')->orderBy('name')->get();
        $classes  = Classes::orderBy('numeric_name')->get();
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('welfare.form', compact('students','classes','currentSession'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'              => 'required|exists:students,id',
            'title'                   => 'required|string|max:255',
            'type'                    => 'required|in:scholarship,book,food,clothing,medical,other',
            'month_year'              => 'nullable|string',
            'total_amount'            => 'required|numeric|min:0',
            'student_contribution'    => 'nullable|numeric|min:0',
            'institution_contribution'=> 'nullable|numeric|min:0',
            'donor_contribution'      => 'nullable|numeric|min:0',
            'donor_name'              => 'nullable|string',
            'date'                    => 'required|date',
            'note'                    => 'nullable|string',
        ]);

        $data['created_by'] = auth()->id();
        WelfareFund::create($data);
        return redirect()->route('welfare.index')->with('success','সহায়তার তথ্য সংরক্ষণ হয়েছে!');
    }

    public function edit(WelfareFund $welfare)
    {
        $students = Student::where('status','active')->orderBy('name')->get();
        $classes  = Classes::orderBy('numeric_name')->get();
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('welfare.form', compact('welfare','students','classes','currentSession'));
    }

    public function update(Request $request, WelfareFund $welfare)
    {
        $data = $request->validate([
            'student_id'              => 'required|exists:students,id',
            'title'                   => 'required|string',
            'type'                    => 'required|in:scholarship,book,food,clothing,medical,other',
            'month_year'              => 'nullable|string',
            'total_amount'            => 'required|numeric|min:0',
            'student_contribution'    => 'nullable|numeric|min:0',
            'institution_contribution'=> 'nullable|numeric|min:0',
            'donor_contribution'      => 'nullable|numeric|min:0',
            'donor_name'              => 'nullable|string',
            'date'                    => 'required|date',
            'note'                    => 'nullable|string',
        ]);
        $welfare->update($data);
        return redirect()->route('welfare.index')->with('success','আপডেট হয়েছে!');
    }

    public function destroy(WelfareFund $welfare)
    {
        $welfare->delete();
        return back()->with('success','মুছে ফেলা হয়েছে!');
    }

    public function studentReport(Student $student)
    {
        $welfare   = WelfareFund::where('student_id',$student->id)->orderByDesc('date')->get();
        $totalHelp = $welfare->sum('total_amount');
        $instTotal = $welfare->sum('institution_contribution');
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('welfare.student-report', compact('student','welfare','totalHelp','instTotal','currentSession'));
    }
}

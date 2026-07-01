<?php
namespace App\Http\Controllers;
use App\Models\{Student, Classes, Section};
use App\Models\Session as AcademicSession;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with(['studentClass','section'])
            ->when($request->search, fn($q) => $q->where('name','like',"%{$request->search}%")->orWhere('student_id','like',"%{$request->search}%"))
            ->when($request->class_id,   fn($q) => $q->where('class_id',   $request->class_id))
            ->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))
            ->when($request->gender,     fn($q) => $q->where('gender',     $request->gender))
            ->when($request->status,     fn($q) => $q->where('status',     $request->status))
            ->latest()->paginate(25);
        $classes  = Classes::orderBy('numeric_name')->get();
        $sections = Section::when($request->class_id, fn($q) => $q->where('class_id',$request->class_id))->get();
        $currentSession = AcademicSession::where('is_current',true)->first();
        return view('students.index', compact('students','classes','sections','currentSession'));
    }

    public function create()
    {
        $classes        = Classes::orderBy('numeric_name')->get();
        $sections       = Section::all();
        $sessions       = AcademicSession::orderByDesc('id')->get();
        $currentSession = AcademicSession::where('is_current',true)->first();
        return view('students.form', compact('classes','sections','sessions','currentSession'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:255',
            'name_en'            => 'nullable|string',
            'date_of_birth'      => 'required|date',
            'gender'             => 'required|in:male,female',
            'religion'           => 'nullable|string',
            'blood_group'        => 'nullable|string',
            'address'            => 'required|string',
            'father_name'        => 'required|string',
            'mother_name'        => 'required|string',
            'guardian_phone'     => 'required|string|max:20',
            'guardian_email'     => 'nullable|email',
            'guardian_occupation'=> 'nullable|string',
            'father_nid'         => 'nullable|string',
            'class_id'           => 'required|exists:classes,id',
            'section_id'         => 'required|exists:sections,id',
            'session_id'         => 'required|exists:academic_sessions,id',
            'roll_number'        => 'nullable|integer',
            'admission_date'     => 'required|date',
            'previous_school'    => 'nullable|string',
            'previous_class'     => 'nullable|string',
            'tc_number'          => 'nullable|string',
            'status'             => 'required|in:active,inactive,transferred',
            'photo'              => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students','public');
        }
        Student::create($data);
        return redirect()->route('students.index')->with('success','ছাত্র সফলভাবে ভর্তি হয়েছে!');
    }

    public function show(Student $student)
    {
        $student->load(['studentClass','section','feeCollections.feeCategory','attendances']);
        $totalPaid   = $student->feeCollections->sum('paid_amount');
        $totalDue    = $student->feeCollections->sum('due_amount');
        $totalDays   = $student->attendances->count();
        $presentDays = $student->attendances->where('status','present')->count();
        $currentSession = AcademicSession::where('is_current',true)->first();
        return view('students.show', compact('student','totalPaid','totalDue','totalDays','presentDays','currentSession'));
    }

    public function edit(Student $student)
    {
        $classes        = Classes::orderBy('numeric_name')->get();
        $sections       = Section::where('class_id',$student->class_id)->get();
        $sessions       = AcademicSession::orderByDesc('id')->get();
        $currentSession = AcademicSession::where('is_current',true)->first();
        return view('students.form', compact('student','classes','sections','sessions','currentSession'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string',
            'date_of_birth'  => 'required|date',
            'gender'         => 'required|in:male,female',
            'religion'       => 'nullable|string',
            'blood_group'    => 'nullable|string',
            'address'        => 'required|string',
            'father_name'    => 'required|string',
            'mother_name'    => 'required|string',
            'guardian_phone' => 'required|string',
            'guardian_email' => 'nullable|email',
            'class_id'       => 'required|exists:classes,id',
            'section_id'     => 'required|exists:sections,id',
            'session_id'     => 'required|exists:academic_sessions,id',
            'roll_number'    => 'nullable|integer',
            'admission_date' => 'required|date',
            'status'         => 'required|in:active,inactive,transferred',
            'photo'          => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            if ($student->photo) \Storage::disk('public')->delete($student->photo);
            $data['photo'] = $request->file('photo')->store('students','public');
        }
        $student->update($data);
        return redirect()->route('students.index')->with('success','তথ্য আপডেট হয়েছে!');
    }

    public function destroy(Student $student)
    {
        if ($student->photo) \Storage::disk('public')->delete($student->photo);
        $student->delete();
        return redirect()->route('students.index')->with('success','ছাত্র মুছে ফেলা হয়েছে!');
    }

    public function idCard(Student $student)
    {
        $student->load('studentClass','section');
        $currentSession = AcademicSession::where('is_current',true)->first();
        return view('students.id-card', compact('student','currentSession'));
    }

    public function export(Request $request)
    {
        $filters = [
            'class_id' => $request->class_id,
            'status'   => $request->status ?? 'active',
        ];
        $filename = 'students-' . now()->format('Y-m-d') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\StudentsExport($filters),
            $filename
        );
    }
}

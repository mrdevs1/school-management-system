<?php
namespace App\Http\Controllers;
use App\Models\{Exam, Session};
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index() {
        $exams = Exam::with('session')->orderByDesc('id')->paginate(20);
        $sessions = Session::orderByDesc('id')->get();
        $currentSession = Session::where('is_current',true)->first();
        return view('exams.index', compact('exams','sessions','currentSession'));
    }
    public function create() { return $this->index(); }
    public function show(Exam $exam) { return $this->index(); }
    public function edit(Exam $exam) { return $this->index(); }
    public function store(Request $request) {
        Exam::create($request->validate(['name'=>'required|string','session_id'=>'required|exists:academic_sessions,id','start_date'=>'required|date','end_date'=>'required|date','description'=>'nullable|string']));
        return redirect()->route('exams.index')->with('success','পরীক্ষা তৈরি হয়েছে!');
    }
    public function update(Request $request, Exam $exam) {
        $exam->update($request->validate(['name'=>'required|string','session_id'=>'required|exists:academic_sessions,id','start_date'=>'required|date','end_date'=>'required|date']));
        return redirect()->route('exams.index')->with('success','আপডেট হয়েছে!');
    }
    public function destroy(Exam $exam) {
        $exam->delete();
        return redirect()->route('exams.index')->with('success','মুছে ফেলা হয়েছে!');
    }
}

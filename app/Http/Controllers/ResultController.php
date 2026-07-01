<?php
namespace App\Http\Controllers;
use App\Models\{Result, Student, Exam, Subject, Classes};
use Illuminate\Http\Request;

class ResultController extends Controller
{
    private function calculateGrade(float $marks): array
    {
        return match(true) {
            $marks >= 80 => ['A+', 5.0], $marks >= 70 => ['A', 4.0],
            $marks >= 60 => ['A-', 3.5], $marks >= 50 => ['B', 3.0],
            $marks >= 40 => ['C', 2.0],  $marks >= 33 => ['D', 1.0],
            default      => ['F', 0.0],
        };
    }

    public function index(Request $request)
    {
        $exams    = Exam::orderByDesc('id')->get();
        $classes  = Classes::orderBy('numeric_name')->get();
        $students = collect();
        $subjects = collect();
        $existingResults = [];

        if ($request->exam_id && $request->class_id) {
            $students = Student::where('class_id',$request->class_id)->where('status','active')->orderBy('roll_number')->get();
            $subjects = Subject::where('class_id',$request->class_id)->get();
            $results  = Result::where('exam_id',$request->exam_id)->whereIn('student_id',$students->pluck('id'))->get();
            foreach ($results as $r) $existingResults[$r->student_id][$r->subject_id] = $r;
        }

        $viewResults = null;
        if ($request->tab === 'view' && $request->exam_id_view && $request->class_id_view)
            $viewResults = $this->buildResultSummary($request->exam_id_view, $request->class_id_view);

        $meritList = null;
        if ($request->tab === 'merit' && $request->exam_id_merit && $request->class_id_merit)
            $meritList = $this->buildResultSummary($request->exam_id_merit, $request->class_id_merit, true);

        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('results.index', compact('exams','classes','students','subjects','existingResults','viewResults','meritList','currentSession'));
    }

    private function buildResultSummary($examId, $classId, $sorted = false)
    {
        $students = Student::where('class_id',$classId)->where('status','active')->get();
        $data = $students->map(function($student) use ($examId) {
            $results = Result::where('student_id',$student->id)->where('exam_id',$examId)->with('subject')->get();
            if ($results->isEmpty()) return null;
            $failed = $results->where('grade','F')->count();
            $gpa    = $failed > 0 ? 0.0 : round($results->avg('grade_point'),2);
            $total  = $results->sum('marks_obtained');
            $avg    = $results->count() ? round($total/$results->count(),1) : 0;
            [$oGrade] = $this->calculateGrade($avg);
            return (object)['student'=>$student,'student_id'=>$student->id,'total_marks'=>$total,'average'=>$avg,'gpa'=>$gpa,'passed'=>$failed===0,'overall_grade'=>$failed>0?'F':$oGrade];
        })->filter();
        return $sorted ? $data->sortByDesc('gpa')->values() : $data;
    }

    public function store(Request $request)
    {
        $request->validate(['exam_id'=>'required|exists:exams,id','results'=>'required|array']);
        $stored = 0;
        foreach ($request->results as $studentId => $subjects) {
            foreach ($subjects as $subjectId => $marks) {
                if ($marks === '' || $marks === null) continue;
                [$grade,$gp] = $this->calculateGrade((float)$marks);
                Result::updateOrCreate(
                    ['student_id'=>$studentId,'exam_id'=>$request->exam_id,'subject_id'=>$subjectId],
                    ['marks_obtained'=>$marks,'grade'=>$grade,'grade_point'=>$gp]
                );
                $stored++;
            }
        }
        return back()->with('success',"{$stored}টি নম্বর সংরক্ষণ হয়েছে!");
    }

    public function marksheet(int $studentId, int $examId)
    {
        $student = Student::with('studentClass','section')->findOrFail($studentId);
        $exam    = Exam::with('session')->findOrFail($examId);
        $results = Result::where('student_id',$studentId)->where('exam_id',$examId)->with('subject')->get();
        $failed  = $results->where('grade','F')->count();
        $gpa     = $failed > 0 ? 0.0 : round($results->avg('grade_point'),2);
        $avg     = $results->count() ? $results->avg('marks_obtained') : 0;
        [$overallGrade] = $this->calculateGrade($avg);
        if ($failed > 0) $overallGrade = 'F';
        return view('results.marksheet', compact('student','exam','results','gpa','overallGrade'));
    }

    public function meritPdf(int $examId, int $classId)
    {
        $exam   = Exam::findOrFail($examId);
        $class  = Classes::findOrFail($classId);
        $merit  = $this->buildResultSummary($examId,$classId,true);
        $school = ['name'=>config('school.name','বিদ্যাপীঠ'),'address'=>config('school.address','')];
        $pdf = Pdf::loadView('results.merit-list', compact('merit','exam','class','school'));
        return $pdf->stream("merit-list.pdf");
    }
}

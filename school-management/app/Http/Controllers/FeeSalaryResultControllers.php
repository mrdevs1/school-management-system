<?php
// ============================================================
// app/Http/Controllers/AttendanceController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\{Attendance, Student, Classes, Section};
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date       = $request->date ?? today()->format('Y-m-d');
        $classes    = Classes::orderBy('numeric_name')->get();
        $sections   = Section::when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->get();

        $students = collect();
        if ($request->class_id) {
            $students = Student::where('class_id', $request->class_id)
                ->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))
                ->where('status', 'active')
                ->orderBy('roll_number')
                ->with(['attendances' => fn($q) => $q->whereDate('date', $date)])
                ->get();
        }

        return view('attendance.index', compact('students', 'date', 'classes', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'       => 'required|date',
            'class_id'   => 'required|exists:classes,id',
            'attendances'=> 'required|array',
        ]);

        foreach ($request->attendances as $studentId => $status) {
            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $request->date],
                [
                    'class_id'   => $request->class_id,
                    'section_id' => $request->section_id,
                    'status'     => $status,
                    'remarks'    => $request->remarks[$studentId] ?? null,
                    'taken_by'   => auth()->id(),
                ]
            );
        }

        return back()->with('success', 'হাজিরা সফলভাবে সংরক্ষণ হয়েছে!');
    }

    public function monthly(Request $request)
    {
        $month    = $request->month ?? now()->format('Y-m');
        $class_id = $request->class_id;
        $classes  = Classes::orderBy('numeric_name')->get();
        $data     = collect();

        if ($class_id) {
            [$year, $mon] = explode('-', $month);
            $days = Carbon::parse($month)->daysInMonth;

            $students = Student::where('class_id', $class_id)->where('status','active')
                ->with(['attendances' => fn($q) => $q->whereYear('date', $year)->whereMonth('date', $mon)])
                ->orderBy('roll_number')->get();

            $data = $students->map(fn($s) => [
                'student'  => $s,
                'present'  => $s->attendances->where('status','present')->count(),
                'absent'   => $s->attendances->where('status','absent')->count(),
                'late'     => $s->attendances->where('status','late')->count(),
                'leave'    => $s->attendances->where('status','leave')->count(),
                'total'    => $days,
            ]);
        }

        return view('attendance.monthly', compact('data', 'month', 'class_id', 'classes'));
    }
}


// ============================================================
// app/Http/Controllers/FeeController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\{FeeCollection, FeeCategory, Student};
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $collections = FeeCollection::with(['student.studentClass', 'feeCategory'])
            ->when($request->search, function($q) use ($request) {
                $q->where('receipt_no', 'like', "%{$request->search}%")
                  ->orWhereHas('student', fn($sq) =>
                      $sq->where('name', 'like', "%{$request->search}%")
                         ->orWhere('student_id', 'like', "%{$request->search}%")
                  );
            })
            ->when($request->month, function($q) use ($request) {
                [$y, $m] = explode('-', $request->month);
                $q->whereYear('payment_date', $y)->whereMonth('payment_date', $m);
            })
            ->orderByDesc('payment_date')
            ->paginate(20);

        $feeCategories     = FeeCategory::all();
        $monthlyCollection = FeeCollection::whereYear('payment_date', now()->year)
                                ->whereMonth('payment_date', now()->month)->sum('paid_amount');
        $todayCollection   = FeeCollection::whereDate('payment_date', today())->sum('paid_amount');
        $totalDue          = FeeCollection::sum('due_amount');
        $dueStudentCount   = FeeCollection::where('due_amount', '>', 0)->distinct('student_id')->count();
        $currentSession    = \App\Models\Session::where('is_current', true)->first();

        return view('fees.index', compact(
            'collections','feeCategories','monthlyCollection',
            'todayCollection','totalDue','dueStudentCount','currentSession'
        ));
    }

    public function collect(Request $request)
    {
        $data = $request->validate([
            'student_id'      => 'required|exists:students,id',
            'fee_category_id' => 'required|exists:fee_categories,id',
            'paid_amount'     => 'required|numeric|min:0',
            'payment_method'  => 'required|in:cash,bkash,nagad,bank',
            'month_year'      => 'nullable|string',
            'transaction_id'  => 'nullable|string',
            'discount'        => 'nullable|numeric|min:0',
        ]);

        $category     = FeeCategory::findOrFail($data['fee_category_id']);
        $discount     = $data['discount'] ?? 0;
        $due          = max(0, $category->amount - $discount - $data['paid_amount']);

        // Auto-generate receipt number
        $year       = now()->year;
        $lastId     = FeeCollection::whereYear('created_at', $year)->max('id') ?? 0;
        $receiptNo  = 'RCP-' . $year . '-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        $fee = FeeCollection::create([
            'student_id'      => $data['student_id'],
            'fee_category_id' => $data['fee_category_id'],
            'amount'          => $category->amount,
            'discount'        => $discount,
            'paid_amount'     => $data['paid_amount'],
            'due_amount'      => $due,
            'month_year'      => $data['month_year'] ?? null,
            'payment_method'  => $data['payment_method'],
            'transaction_id'  => $data['transaction_id'] ?? null,
            'collected_by'    => auth()->id(),
            'payment_date'    => today(),
            'receipt_no'      => $receiptNo,
        ]);

        return redirect()->route('fees.receipt', $receiptNo)
                         ->with('success', 'ফি গ্রহণ সম্পন্ন! রশিদ নং: ' . $receiptNo);
    }

    public function receipt(string $receiptNo)
    {
        $fee = FeeCollection::with(['student.studentClass', 'feeCategory', 'collectedBy'])
                    ->where('receipt_no', $receiptNo)->firstOrFail();
        $school = [
            'name'    => config('school.name', 'বিদ্যাপীঠ'),
            'address' => config('school.address', ''),
            'phone'   => config('school.phone', ''),
        ];

        $pdf = Pdf::loadView('fees.receipt', compact('fee', 'school'))
                  ->setPaper([0, 0, 220, 300], 'portrait');
        return $pdf->stream("receipt-{$receiptNo}.pdf");
    }

    public function studentLedger(Student $student)
    {
        $fees      = FeeCollection::where('student_id', $student->id)->with('feeCategory')->orderByDesc('payment_date')->get();
        $totalPaid = $fees->sum('paid_amount');
        $totalDue  = $fees->sum('due_amount');
        $currentSession = \App\Models\Session::where('is_current', true)->first();

        return view('fees.ledger', compact('student', 'fees', 'totalPaid', 'totalDue', 'currentSession'));
    }

    public function due(Request $request)
    {
        $dueList = FeeCollection::select('student_id', DB::raw('SUM(due_amount) as total_due'))
            ->with('student.studentClass')
            ->groupBy('student_id')
            ->having('total_due', '>', 0)
            ->orderByDesc('total_due')
            ->when($request->class_id, fn($q) =>
                $q->whereHas('student', fn($sq) => $sq->where('class_id', $request->class_id))
            )
            ->paginate(25);

        $classes = \App\Models\Classes::all();
        $totalDue = FeeCollection::sum('due_amount');
        $currentSession = \App\Models\Session::where('is_current', true)->first();

        return view('fees.due', compact('dueList', 'classes', 'totalDue', 'currentSession'));
    }
}


// ============================================================
// app/Http/Controllers/ResultController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\{Result, Student, Exam, Subject, Classes};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ResultController extends Controller
{
    private function calculateGrade(float $marks): array
    {
        return match(true) {
            $marks >= 80 => ['A+', 5.0],
            $marks >= 70 => ['A',  4.0],
            $marks >= 60 => ['A-', 3.5],
            $marks >= 50 => ['B',  3.0],
            $marks >= 40 => ['C',  2.0],
            $marks >= 33 => ['D',  1.0],
            default      => ['F',  0.0],
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
            $students = Student::where('class_id', $request->class_id)
                ->where('status', 'active')->orderBy('roll_number')->get();
            $subjects = Subject::where('class_id', $request->class_id)->get();

            $results = Result::where('exam_id', $request->exam_id)
                ->whereIn('student_id', $students->pluck('id'))
                ->get();

            foreach ($results as $r) {
                $existingResults[$r->student_id][$r->subject_id] = $r;
            }
        }

        $viewResults = null;
        if ($request->tab === 'view' && $request->exam_id_view && $request->class_id_view) {
            $viewResults = $this->buildResultSummary($request->exam_id_view, $request->class_id_view);
        }

        $meritList = null;
        if ($request->tab === 'merit' && $request->exam_id_merit && $request->class_id_merit) {
            $meritList = $this->buildResultSummary($request->exam_id_merit, $request->class_id_merit, true);
        }

        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('results.index', compact('exams','classes','students','subjects','existingResults','viewResults','meritList','currentSession'));
    }

    private function buildResultSummary($examId, $classId, $sorted = false)
    {
        $students = Student::where('class_id', $classId)->where('status','active')->get();

        $data = $students->map(function($student) use ($examId) {
            $results = Result::where('student_id', $student->id)
                ->where('exam_id', $examId)->with('subject')->get();
            if ($results->isEmpty()) return null;

            $failed   = $results->where('grade','F')->count();
            $gpa      = $failed > 0 ? 0.0 : round($results->avg('grade_point'), 2);
            $total    = $results->sum('marks_obtained');
            $avg      = $results->count() ? round($total / $results->count(), 1) : 0;
            [$oGrade] = $this->calculateGrade($avg);

            return (object)[
                'student'       => $student,
                'student_id'    => $student->id,
                'total_marks'   => $total,
                'average'       => $avg,
                'gpa'           => $gpa,
                'passed'        => $failed === 0,
                'overall_grade' => $failed > 0 ? 'F' : $oGrade,
            ];
        })->filter();

        return $sorted ? $data->sortByDesc('gpa')->values() : $data;
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_id'   => 'required|exists:exams,id',
            'results'   => 'required|array',
        ]);

        $stored = 0;
        foreach ($request->results as $studentId => $subjects) {
            foreach ($subjects as $subjectId => $marks) {
                if ($marks === '' || $marks === null) continue;
                $marks = (float)$marks;
                [$grade, $gp] = $this->calculateGrade($marks);
                Result::updateOrCreate(
                    ['student_id' => $studentId, 'exam_id' => $request->exam_id, 'subject_id' => $subjectId],
                    ['marks_obtained' => $marks, 'grade' => $grade, 'grade_point' => $gp]
                );
                $stored++;
            }
        }

        return back()->with('success', "{$stored}টি নম্বর সংরক্ষণ হয়েছে!");
    }

    public function marksheet(int $studentId, int $examId)
    {
        $student = Student::with('studentClass','section')->findOrFail($studentId);
        $exam    = Exam::with('session')->findOrFail($examId);
        $results = Result::where('student_id', $studentId)
                         ->where('exam_id', $examId)
                         ->with('subject')->get();

        $failed = $results->where('grade','F')->count();
        $gpa    = $failed > 0 ? 0.0 : round($results->avg('grade_point'), 2);
        $avg    = $results->count() ? $results->avg('marks_obtained') : 0;
        [$overallGrade] = $this->calculateGrade($avg);
        if ($failed > 0) $overallGrade = 'F';

        $school = ['name' => config('school.name'), 'address' => config('school.address')];

        $pdf = Pdf::loadView('results.marksheet', compact('student','exam','results','gpa','overallGrade','school'));
        return $pdf->stream("marksheet-{$student->student_id}.pdf");
    }

    public function meritPdf(int $examId, int $classId)
    {
        $exam    = Exam::findOrFail($examId);
        $class   = Classes::findOrFail($classId);
        $merit   = $this->buildResultSummary($examId, $classId, true);
        $school  = ['name' => config('school.name'), 'address' => config('school.address')];

        $pdf = Pdf::loadView('results.merit-list', compact('merit','exam','class','school'));
        return $pdf->stream("merit-list.pdf");
    }
}


// ============================================================
// app/Http/Controllers/SalaryController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\{Teacher, SalaryPayment};
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $month    = $request->month ?? now()->format('Y-m');
        $teachers = Teacher::where('status','active')->orderBy('name')->get();
        $payments = SalaryPayment::with('teacher')
            ->where('month_year', $month)
            ->get()
            ->keyBy('teacher_id');

        $totalPaid    = SalaryPayment::where('month_year', $month)->sum('net_salary');
        $paidCount    = $payments->count();
        $unpaidCount  = $teachers->count() - $paidCount;
        $currentSession = \App\Models\Session::where('is_current', true)->first();

        return view('salary.index', compact('teachers','payments','month','totalPaid','paidCount','unpaidCount','currentSession'));
    }

    public function pay(Request $request)
    {
        $data = $request->validate([
            'teacher_id'     => 'required|exists:teachers,id',
            'month_year'     => 'required|string',
            'bonus'          => 'nullable|numeric|min:0',
            'deduction'      => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,bank,bkash',
            'note'           => 'nullable|string',
        ]);

        $teacher    = Teacher::findOrFail($data['teacher_id']);
        $bonus      = $data['bonus'] ?? 0;
        $deduction  = $data['deduction'] ?? 0;
        $netSalary  = $teacher->salary + $bonus - $deduction;

        $payment = SalaryPayment::updateOrCreate(
            ['teacher_id' => $teacher->id, 'month_year' => $data['month_year']],
            [
                'basic_salary'   => $teacher->salary,
                'bonus'          => $bonus,
                'deduction'      => $deduction,
                'net_salary'     => $netSalary,
                'payment_method' => $data['payment_method'],
                'payment_date'   => today(),
                'paid_by'        => auth()->id(),
                'note'           => $data['note'] ?? null,
            ]
        );

        return redirect()->route('salaries.slip', $payment->id)
                         ->with('success', "{$teacher->name}-এর বেতন পরিশোধ হয়েছে!");
    }

    public function payAll(Request $request)
    {
        $month   = $request->month_year;
        $teachers = Teacher::where('status','active')->get();
        $count = 0;

        foreach ($teachers as $teacher) {
            $already = SalaryPayment::where('teacher_id', $teacher->id)->where('month_year', $month)->exists();
            if (!$already) {
                SalaryPayment::create([
                    'teacher_id'     => $teacher->id,
                    'basic_salary'   => $teacher->salary,
                    'bonus'          => 0,
                    'deduction'      => 0,
                    'net_salary'     => $teacher->salary,
                    'month_year'     => $month,
                    'payment_method' => 'cash',
                    'payment_date'   => today(),
                    'paid_by'        => auth()->id(),
                ]);
                $count++;
            }
        }

        return back()->with('success', "{$count} জন শিক্ষকের বেতন পরিশোধ হয়েছে!");
    }

    public function slip(SalaryPayment $payment)
    {
        $payment->load('teacher', 'paidBy');
        $school = ['name' => config('school.name'), 'address' => config('school.address')];
        $pdf = Pdf::loadView('salary.slip', compact('payment', 'school'))
                  ->setPaper([0, 0, 220, 300], 'portrait');
        return $pdf->stream("salary-slip-{$payment->id}.pdf");
    }
}

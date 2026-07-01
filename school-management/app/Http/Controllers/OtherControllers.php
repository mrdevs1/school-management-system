<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::with('creator')->orderByDesc('publish_date')->paginate(20);
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('notices.index', compact('notices', 'currentSession'));
    }

    public function create()
    {
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('notices.form', compact('currentSession'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'audience'     => 'required|in:all,students,teachers,parents',
            'publish_date' => 'required|date',
            'expire_date'  => 'nullable|date|after:publish_date',
        ]);

        $data['created_by'] = auth()->id();
        Notice::create($data);
        return redirect()->route('notices.index')->with('success', 'নোটিশ প্রকাশিত হয়েছে!');
    }

    public function edit(Notice $notice)
    {
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('notices.form', compact('notice', 'currentSession'));
    }

    public function update(Request $request, Notice $notice)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'audience'     => 'required|in:all,students,teachers,parents',
            'publish_date' => 'required|date',
            'expire_date'  => 'nullable|date',
        ]);
        $notice->update($data);
        return redirect()->route('notices.index')->with('success', 'নোটিশ আপডেট হয়েছে!');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('notices.index')->with('success', 'নোটিশ মুছে ফেলা হয়েছে!');
    }
}


// ============================================================
// ExamController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\{Exam, Session};
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('session')->orderByDesc('id')->paginate(20);
        $currentSession = Session::where('is_current', true)->first();
        return view('exams.index', compact('exams', 'currentSession'));
    }

    public function create()
    {
        $sessions = Session::orderByDesc('id')->get();
        $currentSession = Session::where('is_current', true)->first();
        return view('exams.form', compact('sessions', 'currentSession'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'session_id'  => 'required|exists:sessions,id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);
        Exam::create($data);
        return redirect()->route('exams.index')->with('success', 'পরীক্ষা তৈরি হয়েছে!');
    }

    public function edit(Exam $exam)
    {
        $sessions = Session::orderByDesc('id')->get();
        $currentSession = Session::where('is_current', true)->first();
        return view('exams.form', compact('exam', 'sessions', 'currentSession'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'session_id' => 'required|exists:sessions,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);
        $exam->update($data);
        return redirect()->route('exams.index')->with('success', 'পরীক্ষা আপডেট হয়েছে!');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('exams.index')->with('success', 'পরীক্ষা মুছে ফেলা হয়েছে!');
    }
}


// ============================================================
// SubjectController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\{Subject, Classes};
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('studentClass')->orderBy('class_id')->get();
        $classes  = Classes::orderBy('numeric_name')->get();
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('subjects.index', compact('subjects', 'classes', 'currentSession'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'name_en'    => 'nullable|string',
            'code'       => 'required|string|unique:subjects',
            'class_id'   => 'required|exists:classes,id',
            'full_marks' => 'required|integer|min:1',
            'pass_marks' => 'required|integer|min:1',
            'type'       => 'required|in:theory,practical,viva',
        ]);
        Subject::create($data);
        return back()->with('success', 'বিষয় যোগ হয়েছে!');
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name'       => 'required|string',
            'full_marks' => 'required|integer',
            'pass_marks' => 'required|integer',
        ]);
        $subject->update($data);
        return back()->with('success', 'বিষয় আপডেট হয়েছে!');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return back()->with('success', 'বিষয় মুছে ফেলা হয়েছে!');
    }
}


// ============================================================
// FeeCategoryController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\FeeCategory;
use Illuminate\Http\Request;

class FeeCategoryController extends Controller
{
    public function index()
    {
        $categories = FeeCategory::all();
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('fee-categories.index', compact('categories', 'currentSession'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'frequency'   => 'required|in:monthly,yearly,once',
            'description' => 'nullable|string',
        ]);
        FeeCategory::create($data);
        return back()->with('success', 'ফি ক্যাটাগরি যোগ হয়েছে!');
    }

    public function update(Request $request, FeeCategory $feeCategory)
    {
        $data = $request->validate([
            'name'      => 'required|string',
            'amount'    => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,yearly,once',
        ]);
        $feeCategory->update($data);
        return back()->with('success', 'আপডেট হয়েছে!');
    }

    public function destroy(FeeCategory $feeCategory)
    {
        $feeCategory->delete();
        return back()->with('success', 'মুছে ফেলা হয়েছে!');
    }
}


// ============================================================
// SettingsController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\{Classes, Section, Session};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function index()
    {
        $sessions = Session::orderByDesc('id')->get();
        $classes  = Classes::with('sections')->orderBy('numeric_name')->get();
        $currentSession = Session::where('is_current', true)->first();
        return view('settings.index', compact('sessions', 'classes', 'currentSession'));
    }

    public function update(Request $request)
    {
        // Update school config via .env (simple approach)
        $this->setEnv([
            'SCHOOL_NAME'    => $request->school_name,
            'SCHOOL_ADDRESS' => $request->school_address,
            'SCHOOL_PHONE'   => $request->school_phone,
            'SCHOOL_EMAIL'   => $request->school_email,
        ]);

        Artisan::call('config:clear');
        return back()->with('success', 'সেটিংস আপডেট হয়েছে!');
    }

    private function setEnv(array $data)
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);
        foreach ($data as $key => $value) {
            $value = str_replace('"', '', $value);
            if (preg_match("/^{$key}=/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $envContent);
            } else {
                $envContent .= "\n{$key}=\"{$value}\"";
            }
        }
        file_put_contents($envPath, $envContent);
    }
}


// ============================================================
// ApiController.php  (AJAX endpoints)
// ============================================================
namespace App\Http\Controllers;

use App\Models\{Section, Student, Teacher, FeeCollection};
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function sections(Request $request)
    {
        $sections = Section::where('class_id', $request->class_id)->get(['id','name']);
        return response()->json($sections);
    }

    public function studentSearch(Request $request)
    {
        $q = $request->q;
        $students = Student::where('status', 'active')
            ->where(fn($query) =>
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('student_id', 'like', "%{$q}%")
                      ->orWhere('guardian_phone', 'like', "%{$q}%")
            )
            ->with('studentClass')
            ->limit(10)
            ->get()
            ->map(fn($s) => [
                'id'         => $s->id,
                'name'       => $s->name,
                'student_id' => $s->student_id,
                'class_name' => $s->studentClass->name ?? '',
            ]);
        return response()->json($students);
    }

    public function teacherSearch(Request $request)
    {
        $q = $request->q;
        $teachers = Teacher::where('status', 'active')
            ->where(fn($query) =>
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('teacher_id', 'like', "%{$q}%")
            )
            ->limit(10)
            ->get(['id','name','teacher_id','designation']);
        return response()->json($teachers);
    }

    public function studentFeeHistory(int $id)
    {
        $fees = FeeCollection::where('student_id', $id)
            ->with('feeCategory')
            ->orderByDesc('payment_date')
            ->limit(12)
            ->get()
            ->map(fn($f) => [
                'receipt_no'   => $f->receipt_no,
                'category'     => $f->feeCategory->name ?? '',
                'paid'         => $f->paid_amount,
                'due'          => $f->due_amount,
                'date'         => $f->payment_date->format('d/m/Y'),
                'month_year'   => $f->month_year,
            ]);
        return response()->json($fees);
    }
}


// ============================================================
// ClassController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\{Classes, Section, Teacher};
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::withCount('students')->with('sections.teacher')->orderBy('numeric_name')->get();
        $teachers = Teacher::where('status','active')->get();
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('classes.index', compact('classes', 'teachers', 'currentSession'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'name_en'      => 'nullable|string',
            'numeric_name' => 'nullable|integer',
            'type'         => 'required|in:school,madrasa,both',
        ]);
        $class = Classes::create($data);

        // Auto create section A
        Section::create(['class_id' => $class->id, 'name' => 'ক (A)']);

        return back()->with('success', 'শ্রেণী যোগ হয়েছে!');
    }

    public function update(Request $request, Classes $class)
    {
        $data = $request->validate([
            'name'         => 'required|string',
            'numeric_name' => 'nullable|integer',
            'type'         => 'required|in:school,madrasa,both',
        ]);
        $class->update($data);
        return back()->with('success', 'শ্রেণী আপডেট হয়েছে!');
    }

    public function destroy(Classes $class)
    {
        if ($class->students()->count() > 0) {
            return back()->with('error', 'এই শ্রেণীতে ছাত্র আছে, মুছে ফেলা যাবে না!');
        }
        $class->delete();
        return back()->with('success', 'শ্রেণী মুছে ফেলা হয়েছে!');
    }
}


// ============================================================
// SectionController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\{Section, Classes, Teacher};
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'name'       => 'required|string|max:50',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);
        Section::create($data);
        return back()->with('success', 'বিভাগ যোগ হয়েছে!');
    }

    public function update(Request $request, Section $section)
    {
        $data = $request->validate([
            'name'       => 'required|string',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);
        $section->update($data);
        return back()->with('success', 'বিভাগ আপডেট হয়েছে!');
    }

    public function destroy(Section $section)
    {
        if ($section->students()->count() > 0) {
            return back()->with('error', 'এই বিভাগে ছাত্র আছে!');
        }
        $section->delete();
        return back()->with('success', 'বিভাগ মুছে ফেলা হয়েছে!');
    }
}

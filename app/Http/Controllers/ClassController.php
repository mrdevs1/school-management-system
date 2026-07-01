<?php
namespace App\Http\Controllers;
use App\Models\{Classes, Section, Teacher};
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index() {
        $classes  = Classes::withCount('students')->with('sections.teacher')->orderBy('numeric_name')->get();
        $teachers = Teacher::where('status','active')->get();
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('classes.index', compact('classes','teachers','currentSession'));
    }
    public function store(Request $request) {
        $class = $data = $request->validate(['name'=>'required|string','name_en'=>'nullable|string','numeric_name'=>'nullable|integer','type'=>'nullable|in:school,madrasa,both']);
        $class = Classes::create($data);
        // Auto section removed
        return back()->with('success','শ্রেণী যোগ হয়েছে!');
    }
    public function update(Request $request, Classes $class) {
        $class->update($request->validate(['name'=>'required|string','numeric_name'=>'nullable|integer','type'=>'nullable|in:school,madrasa,both']));
        return back()->with('success','আপডেট হয়েছে!');
    }
    public function destroy(Classes $class) {
        if ($class->students()->count() > 0) {
            return back()->with('error','এই শ্রেণীতে ছাত্র আছে, মুছে ফেলা যাবে না!');
        }
        // Delete related data before removing class
        \App\Models\Subject::where('class_id', $class->id)->delete();
        \App\Models\Section::where('class_id', $class->id)->delete();
        $class->delete();
        return back()->with('success','শ্রেণী মুছে ফেলা হয়েছে!');
    }
    public function create() { return $this->index(); }
    public function show(Classes $class) { return $this->index(); }
    public function edit(Classes $class) { return $this->index(); }
}

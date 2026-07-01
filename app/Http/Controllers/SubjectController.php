<?php
namespace App\Http\Controllers;
use App\Models\{Subject, Classes};
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index() {
        $subjects = Subject::with('studentClass')->orderBy('class_id')->get();
        $classes  = Classes::orderBy('numeric_name')->get();
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('subjects.index', compact('subjects','classes','currentSession'));
    }
    public function create() { return $this->index(); }
    public function show(Subject $subject) { return $this->index(); }
    public function edit(Subject $subject) { return $this->index(); }
    public function store(Request $request) {
        Subject::create($request->validate([
            'name'       => 'required|string|max:255',
            'name_en'    => 'nullable|string',
            'code'       => 'required|string|unique:subjects',
            'class_id'   => 'required|exists:classes,id',
            'full_marks' => 'required|integer|min:1',
            'pass_marks' => 'required|integer|min:1',
            'type'       => 'required|in:theory,practical,viva',
        ]));
        return back()->with('success','বিষয় যোগ হয়েছে!');
    }
    public function update(Request $request, Subject $subject) {
        $subject->update($request->validate([
            'name'       => 'required|string',
            'full_marks' => 'required|integer',
            'pass_marks' => 'required|integer',
        ]));
        return back()->with('success','আপডেট হয়েছে!');
    }
    public function destroy(Subject $subject) {
        $subject->delete();
        return back()->with('success','মুছে ফেলা হয়েছে!');
    }
}

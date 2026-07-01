<?php
namespace App\Http\Controllers;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request) {
        Section::create($request->validate(['class_id'=>'required|exists:classes,id','name'=>'required|string|max:50','teacher_id'=>'nullable|exists:teachers,id']));
        return back()->with('success','বিভাগ যোগ হয়েছে!');
    }
    public function update(Request $request, Section $section) {
        $section->update($request->validate(['name'=>'required|string','teacher_id'=>'nullable|exists:teachers,id']));
        return back()->with('success','আপডেট হয়েছে!');
    }
    public function destroy(Section $section) {
        if ($section->students()->count() > 0) return back()->with('error','এই বিভাগে ছাত্র আছে!');
        $section->delete();
        return back()->with('success','মুছে ফেলা হয়েছে!');
    }
    public function index() { return back(); }
    public function create() { return back(); }
    public function show(Section $section) { return back(); }
    public function edit(Section $section) { return back(); }
}

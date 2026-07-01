<?php

namespace App\Http\Controllers;

use App\Models\{Teacher, SalaryPayment};
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('teacher_id', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->gender, fn($q) => $q->where('gender', $request->gender))
            ->latest()->paginate(25);

        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('teachers.index', compact('teachers', 'currentSession'));
    }

    public function create()
    {
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('teachers.form', compact('currentSession'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'name_en'          => 'nullable|string',
            'email'            => 'nullable|email|unique:teachers',
            'phone'            => 'required|string|max:20',
            'date_of_birth'    => 'nullable|date',
            'gender'           => 'required|in:male,female',
            'qualification'    => 'required|string',
            'designation'      => 'required|string',
            'department'       => 'nullable|string',
            'subject_specialty'=> 'nullable|string',
            'salary'           => 'required|numeric|min:0',
            'joining_date'     => 'required|date',
            'nid'              => 'nullable|string',
            'address'          => 'nullable|string',
            'status'           => 'required|in:active,inactive',
            'photo'            => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        Teacher::create($data);
        return redirect()->route('teachers.index')->with('success', 'শিক্ষক সফলভাবে যোগ হয়েছে!');
    }

    public function show(Teacher $teacher)
    {
        $salaryHistory = SalaryPayment::where('teacher_id', $teacher->id)
            ->orderByDesc('month_year')->limit(12)->get();
        $totalEarned = $salaryHistory->sum('net_salary');
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('teachers.show', compact('teacher', 'salaryHistory', 'totalEarned', 'currentSession'));
    }

    public function edit(Teacher $teacher)
    {
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('teachers.form', compact('teacher', 'currentSession'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'nullable|email|unique:teachers,email,' . $teacher->id,
            'phone'            => 'required|string|max:20',
            'gender'           => 'required|in:male,female',
            'qualification'    => 'required|string',
            'designation'      => 'required|string',
            'salary'           => 'required|numeric|min:0',
            'joining_date'     => 'required|date',
            'status'           => 'required|in:active,inactive',
            'photo'            => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($teacher->photo) \Storage::disk('public')->delete($teacher->photo);
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($data);
        return redirect()->route('teachers.index')->with('success', 'তথ্য আপডেট হয়েছে!');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->photo) \Storage::disk('public')->delete($teacher->photo);
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'শিক্ষক মুছে ফেলা হয়েছে!');
    }

    public function salaryHistory(Teacher $teacher)
    {
        $payments = SalaryPayment::where('teacher_id', $teacher->id)->orderByDesc('month_year')->get();
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('teachers.salary-history', compact('teacher', 'payments', 'currentSession'));
    }
}

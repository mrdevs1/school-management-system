<?php
namespace App\Http\Controllers;
use App\Models\{Section, Student, Teacher, FeeCollection};
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function sections(Request $request) {
        return response()->json(Section::where('class_id',$request->class_id)->get(['id','name']));
    }
    public function studentSearch(Request $request) {
        $q = $request->q;
        return response()->json(
            Student::where('status','active')
                ->where(fn($query)=>$query->where('name','like',"%{$q}%")->orWhere('student_id','like',"%{$q}%")->orWhere('guardian_phone','like',"%{$q}%"))
                ->with('studentClass')->limit(10)->get()
                ->map(fn($s)=>['id'=>$s->id,'name'=>$s->name,'student_id'=>$s->student_id,'class_name'=>$s->studentClass->name??''])
        );
    }
    public function teacherSearch(Request $request) {
        $q = $request->q;
        return response()->json(Teacher::where('status','active')->where(fn($query)=>$query->where('name','like',"%{$q}%")->orWhere('teacher_id','like',"%{$q}%"))->limit(10)->get(['id','name','teacher_id','designation']));
    }
    public function studentFeeHistory(int $id) {
        return response()->json(
            FeeCollection::where('student_id',$id)->with('feeCategory')->orderByDesc('payment_date')->limit(12)->get()
                ->map(fn($f)=>['receipt_no'=>$f->receipt_no,'category'=>$f->feeCategory->name??'','paid'=>$f->paid_amount,'due'=>$f->due_amount,'date'=>$f->payment_date->format('d/m/Y'),'month_year'=>$f->month_year])
        );
    }
}

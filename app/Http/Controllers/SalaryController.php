<?php
namespace App\Http\Controllers;
use App\Models\{Teacher, SalaryPayment};
use App\Models\Session as AcademicSession;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $month    = $request->month ?? now()->format('Y-m');
        $teachers = Teacher::where('status','active')->orderBy('name')->get();
        $payments = SalaryPayment::with('teacher')->where('month_year',$month)->get()->keyBy('teacher_id');
        $totalPaid   = SalaryPayment::where('month_year',$month)->sum('net_salary');
        $paidCount   = $payments->count();
        $unpaidCount = $teachers->count() - $paidCount;
        $currentSession = AcademicSession::where('is_current',true)->first();
        return view('salary.index', compact('teachers','payments','month','totalPaid','paidCount','unpaidCount','currentSession'));
    }

    public function pay(Request $request)
    {
        $data    = $request->validate([
            'teacher_id'     =>'required|exists:teachers,id',
            'month_year'     =>'required|string',
            'bonus'          =>'nullable|numeric|min:0',
            'deduction'      =>'nullable|numeric|min:0',
            'payment_method' =>'required|in:cash,bank,bkash',
            'note'           =>'nullable|string',
        ]);
        $teacher   = Teacher::findOrFail($data['teacher_id']);
        $bonus     = $data['bonus'] ?? 0;
        $deduction = $data['deduction'] ?? 0;
        $payment   = SalaryPayment::updateOrCreate(
            ['teacher_id'=>$teacher->id,'month_year'=>$data['month_year']],
            ['basic_salary'=>$teacher->salary,'bonus'=>$bonus,'deduction'=>$deduction,'net_salary'=>$teacher->salary+$bonus-$deduction,'payment_method'=>$data['payment_method'],'payment_date'=>today(),'paid_by'=>auth()->id(),'note'=>$data['note']??null]
        );
        return redirect()->route('salaries.slip',$payment->id)->with('success',"{$teacher->name}-এর বেতন পরিশোধ হয়েছে!");
    }

    public function payAll(Request $request)
    {
        $month = $request->month_year;
        $count = 0;
        foreach (Teacher::where('status','active')->get() as $teacher) {
            if (!SalaryPayment::where('teacher_id',$teacher->id)->where('month_year',$month)->exists()) {
                SalaryPayment::create(['teacher_id'=>$teacher->id,'basic_salary'=>$teacher->salary,'bonus'=>0,'deduction'=>0,'net_salary'=>$teacher->salary,'month_year'=>$month,'payment_method'=>'cash','payment_date'=>today(),'paid_by'=>auth()->id()]);
                $count++;
            }
        }
        return back()->with('success',"{$count} জন শিক্ষকের বেতন পরিশোধ হয়েছে!");
    }

    public function slip(SalaryPayment $payment)
    {
        $payment->load('teacher','paidBy');
        return view('salary.slip', compact('payment'));
    }
}

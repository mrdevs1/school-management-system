<?php
namespace App\Http\Controllers;
use App\Models\{FeeCollection, FeeCategory, Student};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $collections = FeeCollection::with(['student.studentClass','feeCategory'])
            ->when($request->search, fn($q)=>$q->where('receipt_no','like',"%{$request->search}%")->orWhereHas('student',fn($sq)=>$sq->where('name','like',"%{$request->search}%")))
            ->when($request->month, function($q) use($request){ [$y,$m]=explode('-',$request->month); $q->whereYear('payment_date',$y)->whereMonth('payment_date',$m); })
            ->orderByDesc('payment_date')->paginate(20);
        $feeCategories     = FeeCategory::all();
        $monthlyCollection = FeeCollection::whereYear('payment_date',now()->year)->whereMonth('payment_date',now()->month)->sum('paid_amount');
        $todayCollection   = FeeCollection::whereDate('payment_date',today())->sum('paid_amount');
        $totalDue          = FeeCollection::sum('due_amount');
        $dueStudentCount   = FeeCollection::where('due_amount','>',0)->distinct('student_id')->count();
        $currentSession    = \App\Models\Session::where('is_current',true)->first();
        return view('fees.index', compact('collections','feeCategories','monthlyCollection','todayCollection','totalDue','dueStudentCount','currentSession'));
    }

    public function collect(Request $request)
    {
        $data = $request->validate([
            'student_id'      =>'required|exists:students,id',
            'fee_category_id' =>'required|exists:fee_categories,id',
            'paid_amount'     =>'required|numeric|min:0',
            'payment_method'  =>'required',
            'month_year'      =>'nullable|string',
            'transaction_id'  =>'nullable|string',
            'discount'        =>'nullable|numeric|min:0',
        ]);
        $category  = FeeCategory::findOrFail($data['fee_category_id']);
        $discount  = $data['discount'] ?? 0;
        $due       = max(0, $category->amount - $discount - $data['paid_amount']);
        $year      = now()->year;
        $lastId    = FeeCollection::whereYear('created_at',$year)->max('id') ?? 0;
        $receiptNo = 'RCP-'.$year.'-'.str_pad($lastId+1,5,'0',STR_PAD_LEFT);
        FeeCollection::create([
            'student_id'      =>$data['student_id'],
            'fee_category_id' =>$data['fee_category_id'],
            'amount'          =>$category->amount,
            'discount'        =>$discount,
            'paid_amount'     =>$data['paid_amount'],
            'due_amount'      =>$due,
            'month_year'      =>$data['month_year']??null,
            'payment_method'  =>$data['payment_method'],
            'transaction_id'  =>$data['transaction_id']??null,
            'collected_by'    =>auth()->id(),
            'payment_date'    =>today(),
            'receipt_no'      =>$receiptNo,
        ]);
        return redirect()->route('fees.receipt',$receiptNo)->with('success','ফি গ্রহণ সম্পন্ন! রশিদ নং: '.$receiptNo);
    }

    public function receipt(string $receiptNo)
    {
        $fee = FeeCollection::with(['student.studentClass','feeCategory','collectedBy'])->where('receipt_no',$receiptNo)->firstOrFail();
        return view('fees.receipt_print', compact('fee'));
    }

    public function studentLedger(Student $student)
    {
        $fees      = FeeCollection::where('student_id',$student->id)->with('feeCategory')->orderByDesc('payment_date')->get();
        $totalPaid = $fees->sum('paid_amount');
        $totalDue  = $fees->sum('due_amount');
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('fees.ledger', compact('student','fees','totalPaid','totalDue','currentSession'));
    }

    public function due(Request $request)
    {
        $dueList = FeeCollection::select('student_id',DB::raw('SUM(due_amount) as total_due'))
            ->with('student.studentClass')->groupBy('student_id')->having('total_due','>',0)->orderByDesc('total_due')
            ->when($request->class_id,fn($q)=>$q->whereHas('student',fn($sq)=>$sq->where('class_id',$request->class_id)))
            ->paginate(25);
        $classes  = \App\Models\Classes::all();
        $totalDue = FeeCollection::sum('due_amount');
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('fees.due', compact('dueList','classes','totalDue','currentSession'));
    }

    public function report()
    {
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('fees.report', compact('currentSession'));
    }
}

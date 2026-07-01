@extends('layouts.app')
@section('page-title', 'বকেয়া বেতন')
@section('breadcrumb', 'Fees / Due')
@section('content')
<div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px; margin-bottom:20px;">
    <div style="font-size:13px; color:var(--text-muted); margin-bottom:4px;">মোট বকেয়া</div>
    <div style="font-size:26px; font-weight:700; color:#dc2626;">৳{{ number_format($totalDue) }}</div>
</div>
<div class="card">
    <div class="card-header" style="padding:16px 20px;">
        <div class="card-title"><i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;"></i> বকেয়া তালিকা</div>
        <form method="GET" action="{{ route('fees.due') }}">
            <select name="class_id" class="form-select" onchange="this.form.submit()" style="min-width:150px;">
                <option value="">সব শ্রেণী</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ request('class_id')==$class->id?'selected':'' }}>{{ $class->name }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>ছাত্র</th><th>শ্রেণী</th><th style="text-align:right">বকেয়া পরিমাণ</th><th style="text-align:center">কার্যক্রম</th></tr></thead>
            <tbody>
                @forelse($dueList as $due)
                <tr>
                    <td>
                        <div style="font-weight:600; font-size:13.5px;">{{ $due->student->name??'—' }}</div>
                        <div style="font-size:11.5px; color:var(--text-muted);">{{ $due->student->student_id??'' }}</div>
                    </td>
                    <td><span class="badge badge-blue">{{ $due->student->studentClass->name??'—' }}</span></td>
                    <td style="text-align:right; font-weight:700; color:#dc2626; font-family:var(--font-en);">৳{{ number_format($due->total_due) }}</td>
                    <td style="text-align:center;">
                        <a href="{{ route('fees.ledger',$due->student_id) }}" class="btn btn-outline btn-sm"><i class="bi bi-eye"></i> ইতিহাস</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4"><div class="empty-state"><i class="bi bi-check-circle" style="color:#16a34a;"></i><p style="color:#15803d;">কোনো বকেয়া নেই!</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($dueList->hasPages())
    <div class="pagination-wrap">{{ $dueList->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

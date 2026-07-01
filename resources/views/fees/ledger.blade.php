@extends('layouts.app')
@section('page-title', 'ফি ইতিহাস')
@section('breadcrumb', 'Fees / Ledger')
@section('content')
<div style="display:grid; grid-template-columns:280px 1fr; gap:20px; align-items:start;">
    <div class="card" style="position:sticky; top:84px;">
        <div class="card-body" style="text-align:center; padding:24px 16px;">
            <div class="avatar-placeholder" style="width:70px; height:70px; font-size:28px; margin:0 auto 12px;">{{ mb_substr($student->name,0,1) }}</div>
            <div style="font-weight:700; font-size:16px; margin-bottom:4px;">{{ $student->name }}</div>
            <div style="font-size:12px; color:var(--text-muted); margin-bottom:16px; font-family:var(--font-en);">{{ $student->student_id }}</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div style="background:var(--green-light); border-radius:8px; padding:10px;">
                    <div style="font-size:16px; font-weight:700; color:var(--green);">৳{{ number_format($totalPaid) }}</div>
                    <div style="font-size:11px; color:var(--green);">পরিশোধ</div>
                </div>
                <div style="background:{{ $totalDue>0?'#fee2e2':'#dcfce7' }}; border-radius:8px; padding:10px;">
                    <div style="font-size:16px; font-weight:700; color:{{ $totalDue>0?'#dc2626':'#16a34a' }};">৳{{ number_format($totalDue) }}</div>
                    <div style="font-size:11px; color:{{ $totalDue>0?'#dc2626':'#16a34a' }};">বকেয়া</div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" style="padding:16px 20px;">
            <div class="card-title"><i class="bi bi-clock-history"></i> পেমেন্ট ইতিহাস</div>
            <a href="{{ route('fees.index') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> ফিরে যান</a>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>রশিদ নং</th><th>ফি ধরন</th><th>মাস</th><th style="text-align:right">পরিশোধ</th><th>বকেয়া</th><th>তারিখ</th><th></th></tr></thead>
                <tbody>
                    @forelse($fees as $fee)
                    <tr>
                        <td style="font-family:var(--font-en); font-size:12px; color:var(--green); font-weight:600;">{{ $fee->receipt_no }}</td>
                        <td style="font-size:13px;">{{ $fee->feeCategory->name??'—' }}</td>
                        <td style="font-size:12px; color:var(--text-muted);">{{ $fee->month_year??'—' }}</td>
                        <td style="text-align:right; font-family:var(--font-en); font-weight:600; color:#16a34a;">৳{{ number_format($fee->paid_amount) }}</td>
                        <td>
                            @if($fee->due_amount>0)<span class="badge badge-red">৳{{ number_format($fee->due_amount) }}</span>
                            @else<span class="badge badge-green">✓</span>@endif
                        </td>
                        <td style="font-size:12px; color:var(--text-muted); font-family:var(--font-en);">{{ $fee->payment_date->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('fees.receipt',$fee->receipt_no) }}" class="btn btn-icon btn-outline btn-sm" target="_blank"><i class="bi bi-printer"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-cash"></i><p>কোনো পেমেন্ট নেই</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

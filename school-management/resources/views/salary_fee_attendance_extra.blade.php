{{-- ================================================================ --}}
{{-- resources/views/salary/slip.blade.php  (PDF) --}}
{{-- ================================================================ --}}
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 10mm; }
    body { font-family: 'SolaimanLipi', 'Kalpurush', Arial; font-size: 11px; color: #1a2e28; }
    .header { text-align: center; border-bottom: 2px solid #0f7a55; padding-bottom: 10px; margin-bottom: 14px; }
    .school-name { font-size: 18px; font-weight: 900; color: #0f7a55; }
    .slip-title { font-size: 15px; font-weight: 700; margin: 8px 0 4px; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    td { padding: 7px 10px; border-bottom: 1px solid #e0ebe7; }
    td:first-child { color: #666; width: 50%; }
    td:last-child { font-weight: 600; text-align: right; }
    .total-row td { font-size: 14px; font-weight: 900; background: #e6f4f0; color: #0f7a55; border: none; padding: 10px; }
    .paid-stamp { text-align: center; border: 3px solid #16a34a; color: #16a34a; font-size: 18px; font-weight: 900; padding: 6px; border-radius: 5px; margin: 14px 0 6px; }
    .signatures { display: flex; justify-content: space-between; margin-top: 30px; }
    .sig { text-align: center; }
    .sig-line { border-top: 1px solid #666; width: 100px; margin: 0 auto 4px; }
</style>
</head>
<body>
<div class="header">
    <div class="school-name">{{ $school['name'] }}</div>
    <div style="font-size:10px; color:#666;">{{ $school['address'] }}</div>
    <div class="slip-title">বেতন স্লিপ / Salary Slip</div>
    <div style="font-size:10px; color:#666;">
        মাস: <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $payment->month_year)->format('F Y') }}</strong>
        | তারিখ: {{ $payment->payment_date->format('d/m/Y') }}
    </div>
</div>

<table>
    <tr><td>শিক্ষকের নাম</td><td>{{ $payment->teacher->name }}</td></tr>
    <tr><td>আইডি</td><td>{{ $payment->teacher->teacher_id }}</td></tr>
    <tr><td>পদবি</td><td>{{ $payment->teacher->designation }}</td></tr>
    <tr><td>যোগদান</td><td>{{ $payment->teacher->joining_date->format('d/m/Y') }}</td></tr>
</table>

<table>
    <tr><td>মূল বেতন</td><td>৳{{ number_format($payment->basic_salary, 2) }}</td></tr>
    @if($payment->bonus > 0)
    <tr><td>বোনাস / ভাতা</td><td style="color: #16a34a;">+ ৳{{ number_format($payment->bonus, 2) }}</td></tr>
    @endif
    @if($payment->deduction > 0)
    <tr><td>কর্তন</td><td style="color: #dc2626;">- ৳{{ number_format($payment->deduction, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td>নিট বেতন</td><td>৳{{ number_format($payment->net_salary, 2) }}</td></tr>
</table>

<div class="paid-stamp">✅ পরিশোধিত</div>
<div style="font-size: 10px; color: #666; text-align: center;">
    পেমেন্ট পদ্ধতি: {{ ['cash'=>'নগদ','bank'=>'ব্যাংক','bkash'=>'bKash'][$payment->payment_method] }}
    @if($payment->note) | মন্তব্য: {{ $payment->note }} @endif
</div>

<div class="signatures">
    <div class="sig"><div class="sig-line"></div><div>শিক্ষকের স্বাক্ষর</div></div>
    <div class="sig"><div class="sig-line"></div><div>পরিশোধকারী: {{ $payment->paidBy->name ?? '—' }}</div></div>
    <div class="sig"><div class="sig-line"></div><div>প্রধান শিক্ষক</div></div>
</div>
</body>
</html>


{{-- ================================================================ --}}
{{-- resources/views/fees/ledger.blade.php --}}
{{-- ================================================================ --}}
@extends('layouts.app')
@section('page-title', $student->name . ' — ফি ইতিহাস')
@section('breadcrumb', 'Fees / Student Ledger')
@section('content')

<div style="display:grid; grid-template-columns:280px 1fr; gap:20px; align-items:start;">
    <div class="card" style="position:sticky; top:84px;">
        <div class="card-body" style="text-align:center; padding:24px 16px;">
            <div class="avatar-placeholder" style="width:70px; height:70px; font-size:28px; margin:0 auto 12px;">
                {{ mb_substr($student->name,0,1) }}
            </div>
            <div style="font-weight:700; font-size:16px; margin-bottom:4px;">{{ $student->name }}</div>
            <div style="font-size:12px; color:var(--text-muted); font-family:var(--font-en); margin-bottom:14px;">{{ $student->student_id }}</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div style="background:var(--green-light); border-radius:8px; padding:10px;">
                    <div style="font-size:16px; font-weight:700; color:var(--green);">৳{{ number_format($totalPaid) }}</div>
                    <div style="font-size:11px; color:var(--green);">পরিশোধ</div>
                </div>
                <div style="background:{{ $totalDue > 0 ? '#fee2e2' : '#dcfce7' }}; border-radius:8px; padding:10px;">
                    <div style="font-size:16px; font-weight:700; color:{{ $totalDue > 0 ? '#dc2626' : '#16a34a' }};">৳{{ number_format($totalDue) }}</div>
                    <div style="font-size:11px; color:{{ $totalDue > 0 ? '#dc2626' : '#16a34a' }};">বকেয়া</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="padding:16px 22px;">
            <div class="card-title"><i class="bi bi-clock-history"></i> সম্পূর্ণ পেমেন্ট ইতিহাস</div>
            <a href="{{ route('students.show', $student) }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> ফিরে যান</a>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>রশিদ নং</th><th>ফি ধরন</th><th>মাস</th><th style="text-align:right">পরিমাণ</th><th style="text-align:right">পরিশোধ</th><th style="text-align:right">বকেয়া</th><th>পদ্ধতি</th><th>তারিখ</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($fees as $fee)
                    <tr>
                        <td style="font-family:var(--font-en); font-size:12px; color:var(--green); font-weight:600;">{{ $fee->receipt_no }}</td>
                        <td style="font-size:13px;">{{ $fee->feeCategory->name ?? '—' }}</td>
                        <td style="font-size:12px; color:var(--text-muted);">{{ $fee->month_year ?? '—' }}</td>
                        <td style="text-align:right; font-family:var(--font-en);">৳{{ number_format($fee->amount) }}</td>
                        <td style="text-align:right; font-family:var(--font-en); font-weight:600; color:#16a34a;">৳{{ number_format($fee->paid_amount) }}</td>
                        <td style="text-align:right;">
                            @if($fee->due_amount > 0)
                            <span class="badge badge-red">৳{{ number_format($fee->due_amount) }}</span>
                            @else
                            <span class="badge badge-green">✓</span>
                            @endif
                        </td>
                        <td style="font-size:12px;">{{ ['cash'=>'নগদ','bkash'=>'bKash','nagad'=>'Nagad','bank'=>'ব্যাংক'][$fee->payment_method] ?? '—' }}</td>
                        <td style="font-size:12px; color:var(--text-muted); font-family:var(--font-en);">{{ $fee->payment_date->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('fees.receipt', $fee->receipt_no) }}" class="btn btn-icon btn-outline btn-sm" target="_blank" data-tooltip="রশিদ">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9"><div class="empty-state"><i class="bi bi-cash"></i><p>কোনো পেমেন্ট নেই</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


{{-- ================================================================ --}}
{{-- resources/views/attendance/monthly.blade.php --}}
{{-- ================================================================ --}}
@extends('layouts.app')
@section('page-title', 'মাসিক উপস্থিতি রিপোর্ট')
@section('breadcrumb', 'Attendance / Monthly Report')
@section('content')

<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 22px;">
        <form method="GET" action="{{ route('attendance.monthly') }}">
            <div class="filter-bar">
                <div>
                    <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">মাস</label>
                    <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()">
                </div>
                <div>
                    <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">শ্রেণী</label>
                    <select name="class_id" class="form-select" onchange="this.form.submit()">
                        <option value="">শ্রেণী বেছে নিন</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if($class_id && $data->count())
                <button type="button" onclick="window.print()" class="btn btn-outline" style="align-self:flex-end;">
                    <i class="bi bi-printer"></i> প্রিন্ট করুন
                </button>
                @endif
            </div>
        </form>
    </div>
</div>

@if($data->count())
<div class="card">
    <div class="card-header" style="padding:16px 22px;">
        <div class="card-title"><i class="bi bi-calendar-month"></i>
            {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }} — উপস্থিতি রিপোর্ট
        </div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ছাত্র</th>
                    <th style="text-align:center; background:#dcfce7; color:#15803d;">উপস্থিত</th>
                    <th style="text-align:center; background:#fee2e2; color:#dc2626;">অনুপস্থিত</th>
                    <th style="text-align:center; background:#fef3c7; color:#d97706;">দেরিতে</th>
                    <th style="text-align:center; background:#dbeafe; color:#1d4ed8;">ছুটি</th>
                    <th style="text-align:center;">মোট দিন</th>
                    <th style="text-align:center;">হার</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                @php $pct = $row['total'] > 0 ? round(($row['present']/$row['total'])*100) : 0; @endphp
                <tr>
                    <td>
                        <div style="font-weight:600; font-size:13.5px;">{{ $row['student']->name }}</div>
                        <div style="font-size:11.5px; color:var(--text-muted);">{{ $row['student']->student_id }}</div>
                    </td>
                    <td style="text-align:center; font-weight:700; color:#16a34a; font-family:var(--font-en);">{{ $row['present'] }}</td>
                    <td style="text-align:center; font-weight:700; color:#dc2626; font-family:var(--font-en);">{{ $row['absent'] }}</td>
                    <td style="text-align:center; font-weight:700; color:#d97706; font-family:var(--font-en);">{{ $row['late'] }}</td>
                    <td style="text-align:center; font-weight:700; color:#1d4ed8; font-family:var(--font-en);">{{ $row['leave'] }}</td>
                    <td style="text-align:center; font-family:var(--font-en);">{{ $row['total'] }}</td>
                    <td style="text-align:center;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div class="progress-bar-wrap" style="flex:1; height:5px;">
                                <div class="progress-bar-fill" style="width:{{ $pct }}%; background:{{ $pct >= 75 ? 'var(--green)' : '#f59e0b' }};"></div>
                            </div>
                            <span style="font-size:12px; font-weight:600; font-family:var(--font-en); color:{{ $pct >= 75 ? 'var(--green)' : '#d97706' }}; min-width:35px;">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif($class_id)
<div class="card">
    <div class="empty-state" style="padding:50px;"><i class="bi bi-calendar-x"></i><p>এই মাসে কোনো হাজিরার রেকর্ড নেই</p></div>
</div>
@else
<div class="card">
    <div class="empty-state" style="padding:60px;">
        <i class="bi bi-calendar-month" style="font-size:52px; color:var(--border);"></i>
        <p style="margin-top:14px; font-size:16px; font-weight:600;">মাস ও শ্রেণী বেছে নিন</p>
    </div>
</div>
@endif
@endsection

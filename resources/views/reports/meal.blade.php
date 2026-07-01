@extends('layouts.app')
@section('page-title', 'খোরাকি রিপোর্ট')
@section('breadcrumb', 'Reports / Meal')
@section('content')
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('reports.meal') }}">
            <div class="filter-bar">
                <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()">
                <select name="class_id" class="form-select" onchange="this.form.submit()">
                    <option value="">সব শ্রেণী</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $class_id==$class->id?'selected':'' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
                <button onclick="window.print()" type="button" class="btn btn-outline"><i class="bi bi-printer"></i> প্রিন্ট</button>
            </div>
        </form>
    </div>
</div>

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px;">
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:16px; text-align:center;">
        <div style="font-size:11px; color:var(--text-muted); text-transform:uppercase; margin-bottom:6px;">মোট খরচ</div>
        <div style="font-size:20px; font-weight:700;">৳{{ number_format($stats['total']) }}</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:16px; text-align:center;">
        <div style="font-size:11px; color:var(--text-muted); text-transform:uppercase; margin-bottom:6px;">ছাত্র প্রদান</div>
        <div style="font-size:20px; font-weight:700; color:#16a34a;">৳{{ number_format($stats['student']) }}</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:16px; text-align:center;">
        <div style="font-size:11px; color:var(--text-muted); text-transform:uppercase; margin-bottom:6px;">প্রতিষ্ঠান</div>
        <div style="font-size:20px; font-weight:700; color:#1d4ed8;">৳{{ number_format($stats['institution']) }}</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:16px; text-align:center;">
        <div style="font-size:11px; color:var(--text-muted); text-transform:uppercase; margin-bottom:6px;">বকেয়া</div>
        <div style="font-size:20px; font-weight:700; color:#dc2626;">৳{{ number_format($stats['due']) }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="padding:16px 20px;">
        <div class="card-title"><i class="bi bi-cup-hot-fill"></i> খোরাকি রিপোর্ট — {{ \Carbon\Carbon::createFromFormat('Y-m',$month)->format('F Y') }}</div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>ছাত্র</th><th>শ্রেণী</th><th style="text-align:center">উপস্থিত</th><th style="text-align:right">মোট</th><th style="text-align:right">ছাত্র</th><th style="text-align:right">প্রতিষ্ঠান</th><th style="text-align:right">বকেয়া</th></tr></thead>
            <tbody>
                @forelse($data as $meal)
                <tr>
                    <td style="font-weight:600; font-size:13px;">{{ $meal->student->name }}</td>
                    <td><span class="badge badge-blue">{{ $meal->student->studentClass->name??'—' }}</span></td>
                    <td style="text-align:center; font-family:var(--font-en);">{{ $meal->present_days }}/{{ $meal->total_days }}</td>
                    <td style="text-align:right; font-family:var(--font-en); font-weight:600;">৳{{ number_format($meal->total_amount) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#16a34a;">৳{{ number_format($meal->student_paid) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#1d4ed8;">৳{{ number_format($meal->institution_paid) }}</td>
                    <td style="text-align:right;">
                        @if($meal->due_amount>0)<span class="badge badge-red">৳{{ number_format($meal->due_amount) }}</span>
                        @else<span class="badge badge-green">পরিশোধ</span>@endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><i class="bi bi-cup-hot"></i><p>কোনো তথ্য নেই</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
